<?php
/**
 * 카테고리별 이미지를 데이터베이스에 추가하는 스크립트
 * 
 * '밀키트 사진 캡쳐본' 폴더에서 이미지를 읽어서
 * organized 폴더로 복사하고 데이터베이스에 추가
 */

require_once __DIR__ . '/../inc/db.php';

// 카테고리 매핑
$categories = [
    '한식' => 'korean',
    '중식' => 'chinese',
    '일식' => 'japanese',
    '양식' => 'western',
    '건강식' => 'health',
    '기타' => 'other'
];

// 기본 경로 설정
$base_path = __DIR__ . '/../img';
$source_base = $base_path . '/meal_kit_captures';
$target_base = $base_path . '/organized';

echo "=== 카테고리별 이미지 추가 시작 ===\n\n";

try {
    $pdo = db_get_pdo();
    
    // 현재 가장 큰 content_code 번호 찾기
    $query = "SELECT content_code FROM contents ORDER BY content_code DESC LIMIT 1";
    $result = db_select($query);
    
    $last_number = 0;
    if (!empty($result)) {
        $last_code = $result[0]['content_code'];
        // MK-20251126-0001 형식에서 숫자 추출
        preg_match('/MK-\d{8}-(\d+)/', $last_code, $matches);
        if (isset($matches[1])) {
            $last_number = intval($matches[1]);
        }
    }
    
    $current_number = $last_number;
    $today = date('Ymd');
    
    $total_added = 0;
    $total_copied = 0;
    
    foreach ($categories as $korean_name => $english_name) {
        $source_dir = $source_base . '/' . $korean_name;
        $target_dir = $target_base . '/' . $korean_name;
        
        if (!is_dir($source_dir)) {
            echo "⚠️  폴더 없음: $source_dir\n";
            continue;
        }
        
        // 타겟 디렉토리 생성
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
            echo "📁 디렉토리 생성: $target_dir\n";
        }
        
        echo "\n📂 처리 중: $korean_name ($english_name)\n";
        echo str_repeat('-', 60) . "\n";
        
        // 이미지 파일 읽기
        $files = scandir($source_dir);
        $image_files = array_filter($files, function($file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });
        
        foreach ($image_files as $filename) {
            $current_number++;
            $content_code = sprintf('MK-%s-%04d', $today, $current_number);
            
            // 파일명에서 확장자 제거하여 상품명으로 사용
            $content_name = pathinfo($filename, PATHINFO_FILENAME);
            
            // 원본 파일 경로
            $source_file = $source_dir . '/' . $filename;
            
            // 타겟 파일명 (확장자를 png로 통일)
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $target_filename = $content_code . '.' . $ext;
            $target_file = $target_dir . '/' . $target_filename;
            
            // 데이터베이스에 저장할 이미지 경로 (웹 경로)
            $web_path = 'img/organized/' . $korean_name . '/' . $target_filename;
            
            // 파일 복사
            if (copy($source_file, $target_file)) {
                $total_copied++;
                
                // 데이터베이스에 삽입
                $insert_query = "
                    INSERT INTO contents 
                    (content_code, content_img, deliv_today, content_name, discount_rate, content_cost, content_price, category_large) 
                    VALUES 
                    (:content_code, :content_img, 'N', :content_name, 0, 0, 0, :category_large)
                ";
                
                $params = [
                    ':content_code' => $content_code,
                    ':content_img' => $web_path,
                    ':content_name' => $content_name,
                    ':category_large' => $korean_name
                ];
                
                db_insert($insert_query, $params);
                $total_added++;
                
                echo "✅ $content_code - $content_name\n";
                echo "   파일: $filename → $target_filename\n";
            } else {
                echo "❌ 파일 복사 실패: $filename\n";
            }
        }
        
        echo "완료: " . count($image_files) . "개 처리\n";
    }
    
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "✨ 작업 완료!\n";
    echo "총 복사된 파일: $total_copied 개\n";
    echo "총 추가된 상품: $total_added 개\n";
    echo "마지막 코드: " . sprintf('MK-%s-%04d', $today, $current_number) . "\n";
    echo str_repeat('=', 60) . "\n";
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
