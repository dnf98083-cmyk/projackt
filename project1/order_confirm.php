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
    
    // [추가] 구매 확정 시 포인트 지급 (100P)
    db_update_delete("UPDATE members SET point = point + 100 WHERE id = ?", [$user_id]);
    // 포인트 히스토리 기록
    db_update_delete("INSERT INTO point_history (member_id, amount, type, description, reg_date) VALUES (?, 100, 'purchase', '구매 확정 보상', NOW())", [$user_id]);

    echo "<script>alert('구매가 확정되었습니다. (포인트 100P 적립)'); location.href='my-page_order.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('처리 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
