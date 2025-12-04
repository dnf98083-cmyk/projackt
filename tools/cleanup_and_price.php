<?php
require_once __DIR__ . '/../inc/db.php';

echo "=== 이미지 없는 상품 삭제 및 가격 설정 ===\n\n";

try {
    $pdo = db_get_pdo();
    
    // 1. 이미지가 없는 상품 삭제
    echo "1. 이미지가 없는 상품 삭제 중...\n";
    $delete_sql = "DELETE FROM contents WHERE content_img IS NULL OR content_img = '' OR content_img = 'img/no_image.png'";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "   삭제된 상품: {$deleted}개\n\n";
    
    // 2. 가격이 0원인 상품 확인
    echo "2. 가격이 0원인 상품 확인 중...\n";
    $check_sql = "SELECT content_code, content_name, category_large FROM contents WHERE content_price = 0 OR content_price IS NULL";
    $zero_price_products = db_select($check_sql);
    echo "   가격 설정 필요: " . count($zero_price_products) . "개\n\n";
    
    // 3. 카테고리별 가격 설정
    $category_prices = [
        '한식' => [15000, 40000],
        '양식' => [13000, 35000],
        '중식' => [10000, 30000],
        '일식' => [15000, 40000],
        '건강식' => [8000, 25000],
        '기타' => [10000, 30000]
    ];
    
    echo "3. 카테고리별 가격 설정 중...\n";
    $updated = 0;
    
    foreach ($zero_price_products as $product) {
        $category = $product['category_large'] ?? '기타';
        $price_range = $category_prices[$category] ?? [10000, 30000];
        
        // 랜덤 가격 설정 (1000원 단위)
        $min = $price_range[0];
        $max = $price_range[1];
        $price = rand($min / 1000, $max / 1000) * 1000;
        
        // 할인율 설정 (0, 10, 20, 30% 중 랜덤)
        $discount_rates = [0, 10, 20, 30];
        $discount_rate = $discount_rates[array_rand($discount_rates)];
        
        // 원가 계산
        $cost = $discount_rate > 0 ? round($price / (1 - $discount_rate / 100)) : $price;
        
        // 업데이트
        $update_sql = "UPDATE contents SET content_price = ?, content_cost = ?, discount_rate = ? WHERE content_code = ?";
        db_update_delete($update_sql, [$price, $cost, $discount_rate, $product['content_code']]);
        
        $updated++;
        echo "   {$product['content_name']} ({$category}): {$price}원 (할인: {$discount_rate}%)\n";
    }
    
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "✨ 작업 완료!\n";
    echo "삭제된 상품: {$deleted}개\n";
    echo "가격 설정된 상품: {$updated}개\n";
    echo str_repeat('=', 60) . "\n";
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
