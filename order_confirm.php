<?php
require_once("inc/db.php");
require_once("inc/session.php");

$user_id = $_SESSION['member_id'] ?? null;
if (empty($user_id)) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='login.php';</script>";
    exit;
}

$order_id = $_POST['order_id'] ?? null;

if (empty($order_id)) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 본인 주문인지 확인
$order = db_select("SELECT * FROM pay WHERE order_id = ? AND member_id = ?", [$order_id, $user_id]);
if (empty($order)) {
    echo "<script>alert('주문 정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}

// 상태 업데이트
try {
    $pdo = db_get_pdo();
    $stmt = $pdo->prepare("UPDATE pay SET status = '구매확정' WHERE order_id = ?");
    $stmt->execute([$order_id]);
    
    echo "<script>alert('구매가 확정되었습니다.'); location.href='my-page_order.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('처리 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
