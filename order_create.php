<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
$user_id = $_SESSION['member_id'] ?? null;
if (empty($user_id)) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='login.php';</script>";
    exit;
}

$mode = $_POST['mode'] ?? 'direct'; // 'direct' or 'cart'

$order_contents = [];
$total_price = 0;

if ($mode === 'direct') {
    // 바로구매
    $content_code = $_POST['content_code'] ?? null;
    $content_options = $_POST['content_options'] ?? '기본';
    $content_amount = $_POST['content_amount'] ?? 1;

    if (empty($content_code)) {
        echo "<script>alert('상품 정보가 없습니다.'); history.back();</script>";
        exit;
    }

    // 상품 정보 조회
    $product = db_select("SELECT * FROM contents WHERE content_code = ?", [$content_code]);
    if (empty($product)) {
        echo "<script>alert('존재하지 않는 상품입니다.'); history.back();</script>";
        exit;
    }
    $p = $product[0];

    $price = (int)$p['content_price'];
    $amount = (int)$content_amount;
    $sum = $price * $amount;

    $order_contents[] = [
        'content_code' => $content_code,
        'content_name' => $p['content_name'],
        'content_img' => $p['content_img'],
        'content_options' => $content_options,
        'content_amount' => $amount,
        'content_price' => $price,
        'total_price' => $sum
    ];

    $total_price += $sum;

} elseif ($mode === 'cart') {
    // 장바구니 주문
    $cart_ids_str = $_POST['cart_ids'] ?? '';
    if (empty($cart_ids_str)) {
        echo "<script>alert('주문할 상품이 없습니다.'); history.back();</script>";
        exit;
    }

    $cart_ids = explode(',', $cart_ids_str);
    
    foreach ($cart_ids as $cid) {
        $cid = trim($cid);
        if (empty($cid)) continue;

        // 장바구니 정보 조회
        $cart_item = db_select("SELECT c.*, p.content_name, p.content_img, p.content_price 
                                FROM cart c 
                                JOIN contents p ON c.content_code = p.content_code 
                                WHERE c.cart_id = ? AND c.user_id = ?", [$cid, $user_id]);
        
        if (!empty($cart_item)) {
            $item = $cart_item[0];
            $price = (int)$item['content_price'];
            $amount = (int)$item['content_amount'];
            $sum = $price * $amount;

            $order_contents[] = [
                'content_code' => $item['content_code'],
                'content_name' => $item['content_name'],
                'content_img' => $item['content_img'],
                'content_options' => $item['content_options'],
                'content_amount' => $amount,
                'content_price' => $price,
                'total_price' => $sum
            ];

            $total_price += $sum;
        }
    }

    if (empty($order_contents)) {
        echo "<script>alert('주문할 상품 정보를 찾을 수 없습니다.'); history.back();</script>";
        exit;
    }
}

// 배송비 계산 (4만원 이상 무료)
$shipping_fee = ($total_price >= 40000) ? 0 : 3000;
$final_price = $total_price + $shipping_fee;

// 주문 ID 생성
$order_id = date("YmdHis") . rand(1000, 9999);

// DB 저장
// pay 테이블 구조 보정 및 생성
try {
    $pdo = db_get_pdo();
    
    // 1. 테이블 생성 (없을 경우)
    $create_sql = "CREATE TABLE IF NOT EXISTS pay (
        order_id VARCHAR(30) PRIMARY KEY,
        member_id VARCHAR(50) NOT NULL,
        order_contents LONGTEXT,
        total_price INT,
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) DEFAULT '결제완료',
        review CHAR(1) DEFAULT 'N'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($create_sql);

    // 2. 기존 테이블이 있을 경우 컬럼 추가/수정 시도 (에러 무시)
    try { $pdo->exec("ALTER TABLE pay MODIFY COLUMN order_id VARCHAR(50)"); } catch(Exception $e){}
    try { $pdo->exec("ALTER TABLE pay ADD COLUMN status VARCHAR(20) DEFAULT '결제완료'"); } catch(Exception $e){}
    try { $pdo->exec("ALTER TABLE pay ADD COLUMN review CHAR(1) DEFAULT 'N'"); } catch(Exception $e){}
    try { $pdo->exec("ALTER TABLE pay MODIFY COLUMN order_contents LONGTEXT"); } catch(Exception $e){}
    try { $pdo->exec("ALTER TABLE pay ADD COLUMN total_price INT DEFAULT 0"); } catch(Exception $e){}
    try { $pdo->exec("ALTER TABLE pay ADD COLUMN order_date DATETIME DEFAULT CURRENT_TIMESTAMP"); } catch(Exception $e){}

} catch (Exception $e) {
    // 테이블 생성/수정 중 에러는 무시하고 진행 (INSERT에서 잡힘)
}

$json_contents = json_encode($order_contents, JSON_UNESCAPED_UNICODE);

if ($json_contents === false) {
    // JSON 인코딩 실패 시 (주로 인코딩 문제)
    $error_msg = json_last_error_msg();
    echo "<script>alert('주문 데이터 처리 중 오류가 발생했습니다. (JSON Error: " . addslashes($error_msg) . ")'); history.back();</script>";
    exit;
}

$insert_sql = "INSERT INTO pay (order_id, member_id, order_contents, total_price, status) VALUES (?, ?, ?, ?, ?)";

try {
    $pdo = db_get_pdo();
    $st = $pdo->prepare($insert_sql);
    $result = $st->execute([$order_id, $user_id, $json_contents, $final_price, '결제완료']);

    if ($result) {
        // ✅ 판매량(content_sales) 업데이트 로직 추가
        try {
            $update_sales_sql = "UPDATE contents SET content_sales = content_sales + ? WHERE content_code = ?";
            $st_sales = $pdo->prepare($update_sales_sql);
            
            foreach ($order_contents as $item) {
                $qty = (int)$item['content_amount'];
                $c_code = $item['content_code'];
                if ($qty > 0 && !empty($c_code)) {
                    $st_sales->execute([$qty, $c_code]);
                }
            }
        } catch (Exception $e) {
            // 판매량 업데이트 실패는 주문 실패로 처리하지 않음 (로그만 남기거나 무시)
        }

        // 장바구니 주문인 경우 장바구니 비우기
        if ($mode === 'cart') {
            $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
            $delete_sql = "DELETE FROM cart WHERE user_id = ? AND cart_id IN ($placeholders)";
            $params = array_merge([$user_id], $cart_ids);
            
            $st_del = $pdo->prepare($delete_sql);
            $st_del->execute($params);
        }

        // 주문 완료 페이지로 이동
        echo "<script>location.href='order_complete.php?order_id={$order_id}';</script>";
        exit;
    }
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo "<script>alert('주문 처리 중 오류가 발생했습니다.\\n에러 내용: " . addslashes($msg) . "'); history.back();</script>";
    exit;
}
?>
