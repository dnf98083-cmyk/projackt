<?php
require_once __DIR__ . '/../inc/db.php';

echo "=== 이미지 파일이 없는 상품 정리 ===\n\n";

try {
    // 모든 상품 조회
    $all_products = db_select("SELECT content_code, content_name, content_img FROM contents");
    
    $base_path = __DIR__ . '/../';
    $deleted_count = 0;
    $checked_count = 0;
    
    echo "총 " . count($all_products) . "개 상품 확인 중...\n\n";
    
    foreach ($all_products as $product) {
        $checked_count++;
        $img_path = $product['content_img'];
        
        // 이미지 경로가 비어있거나 no_image인 경우
        if (empty($img_path) || strpos($img_path, 'no_image') !== false || strpos($img_path, 'no-image') !== false) {
            db_update_delete("DELETE FROM contents WHERE content_code = ?", [$product['content_code']]);
            echo "❌ 삭제: {$product['content_name']} (이미지 경로 없음)\n";
            $deleted_count++;
            continue;
        }
        
        // 실제 파일 존재 여부 확인
        $full_path = $base_path . ltrim($img_path, '/');
        
        if (!file_exists($full_path)) {
            db_update_delete("DELETE FROM contents WHERE content_code = ?", [$product['content_code']]);
            echo "❌ 삭제: {$product['content_name']} (파일 없음: {$img_path})\n";
            $deleted_count++;
        } else {
            // 파일이 존재하면 간략하게 표시
            if ($checked_count % 20 == 0) {
                echo "✓ {$checked_count}개 확인 완료...\n";
            }
        }
    }
    
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "✨ 작업 완료!\n";
    echo "확인한 상품: {$checked_count}개\n";
    echo "삭제된 상품: {$deleted_count}개\n";
    echo "남은 상품: " . ($checked_count - $deleted_count) . "개\n";
    echo str_repeat('=', 60) . "\n";
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
}
