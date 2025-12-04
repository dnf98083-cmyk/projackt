<?php
require_once __DIR__ . "/../inc/db.php";

echo "판매량 재집계 시작...\n";

try {
    $pdo = db_get_pdo();

    // 0. 컬럼 존재 여부 확인 및 추가
    try {
        $pdo->query("SELECT content_sales FROM contents LIMIT 1");
    } catch (Exception $e) {
        echo "content_sales 컬럼이 없어 생성합니다...\n";
        $pdo->exec("ALTER TABLE contents ADD COLUMN content_sales INT DEFAULT 0");
    }

    // 1. 모든 상품의 판매량 0으로 초기화
    echo "모든 상품 판매량 초기화 중...\n";
    $pdo->exec("UPDATE contents SET content_sales = 0");

    // 2. 모든 주문 내역 가져오기
    echo "주문 내역 조회 중...\n";
    $stmt = $pdo->query("SELECT order_contents FROM pay");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sales_map = []; // content_code => total_sales

    foreach ($orders as $order) {
        $json = $order['order_contents'];
        if (empty($json)) continue;

        $items = json_decode($json, true);
        if (!is_array($items)) continue;

        foreach ($items as $item) {
            $code = $item['content_code'] ?? null;
            $amount = (int)($item['content_amount'] ?? 0);

            if ($code && $amount > 0) {
                if (!isset($sales_map[$code])) {
                    $sales_map[$code] = 0;
                }
                $sales_map[$code] += $amount;
            }
        }
    }

    // 3. 집계된 판매량 업데이트
    echo "판매량 업데이트 중...\n";
    $update_stmt = $pdo->prepare("UPDATE contents SET content_sales = ? WHERE content_code = ?");
    
    $count = 0;
    foreach ($sales_map as $code => $total) {
        $update_stmt->execute([$total, $code]);
        $count++;
        // echo "상품 $code : $total 개 판매됨\n";
    }

    echo "총 {$count}개 상품의 판매량이 업데이트 되었습니다.\n";
    echo "완료!\n";

} catch (Exception $e) {
    echo "에러 발생: " . $e->getMessage() . "\n";
}
