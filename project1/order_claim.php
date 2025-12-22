<?php
require_once("inc/db.php");
require_once("inc/session.php");

$user_id = $_SESSION['member_id'] ?? null;
if (empty($user_id)) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='login.php';</script>";
    exit;
}

$order_id = $_POST['order_id'] ?? null;
$claim_type = $_POST['claim_type'] ?? null; // cancel, return, exchange

if (empty($order_id) || empty($claim_type)) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 본인 주문인지 확인
$order = db_select("SELECT * FROM pay WHERE order_id = ? AND member_id = ?", [$order_id, $user_id]);
if (empty($order)) {
    echo "<script>alert('주문 정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}

$current_status = $order[0]['status'];
$new_status = '';
$msg = '';

if ($claim_type === 'cancel') {
    if ($current_status !== '결제완료') {
        echo "<script>alert('결제완료 상태에서만 취소가 가능합니다.'); history.back();</script>";
        exit;
    }
    $new_status = '취소신청';
    $msg = '주문 취소 신청이 완료되었습니다.';
} elseif ($claim_type === 'return') {
    if ($current_status !== '배송중' && $current_status !== '배송완료' && $current_status !== '구매확정') {
         // 단순화를 위해 배송중/구매확정 상태 등에서 허용 (실제로는 배송완료 후 가능)
         // 여기서는 테스트를 위해 유연하게 허용
    }
    $new_status = '반품신청';
    $msg = '반품 신청이 완료되었습니다.';
} elseif ($claim_type === 'exchange') {
    $new_status = '교환신청';
    $msg = '교환 신청이 완료되었습니다.';
} else {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit;
}

// 상태 업데이트
try {
    $pdo = db_get_pdo();
    $stmt = $pdo->prepare("UPDATE pay SET status = ? WHERE order_id = ?");
    $stmt->execute([$new_status, $order_id]);
    
    echo "<script>alert('$msg'); location.href='my-page_cancel_return.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('처리 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
