<?php
require_once __DIR__ . '/../inc/db.php';

$pdo = db_get_pdo();

// 가격 범위(원 단위)
$price_ranges = [
    '한식' => [7000, 28000],
    '양식' => [9000, 32000],
    '중식' => [6000, 24000],
    '일식' => [8000, 35000],
    '건강식' => [5000, 14000],
    '기타' => [3000, 18000],
];

// MK-로 시작하는 상품만 업데이트
$stmt = $pdo->query("SELECT content_code, category_large FROM contents WHERE content_code LIKE 'MK-%'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($rows);
$updated = 0;
$details = [];

foreach ($rows as $r) {
    $code = $r['content_code'];
    $cat = $r['category_large'] ?: '기타';
    $range = $price_ranges[$cat] ?? $price_ranges['기타'];

    // 무작위 가격 결정
    $min = $range[0]; $max = $range[1];
    $price = rand($min, $max);
    
    // 50% 확률로 900원 단위, 50% 확률로 000원 단위
    if (rand(0, 1) == 0) {
        $price = round($price / 1000) * 1000 - 100; // 예: 12900
    } else {
        $price = round($price / 1000) * 1000; // 예: 13000
    }
    
    // 최소 가격 보정
    if ($price < $min) $price = $min + 900;

    // 원가 = 약 60~70% (정수)
    $cost_ratio = rand(60, 70) / 100;
    $cost = (int) floor($price * $cost_ratio / 100) * 100;

    // 할인율: 20% 확률로 5~30% 할인 적용
    $discount = 0;
    if (rand(1, 5) == 1) {
        $discount = rand(5, 30);
    }

    try {
        $u = $pdo->prepare("UPDATE contents SET content_price = ?, content_cost = ?, discount_rate = ? WHERE content_code = ?");
        $res = $u->execute([$price, $cost, $discount, $code]);
        if ($res) {
            $updated++;
            $details[] = [ 'code' => $code, 'category' => $cat, 'price' => $price, 'cost' => $cost ];
        }
    } catch (Exception $e) {
        // 무시하고 계속
    }
}

echo "총 대상: $total\n";
echo "업데이트 완료: $updated\n";
// 출력 샘플 10개
$sample = array_slice($details, 0, 10);
foreach ($sample as $s) {
    echo "{$s['code']} | {$s['category']} | 가격: {$s['price']}원 | 원가: {$s['cost']}원\n";
}

?>