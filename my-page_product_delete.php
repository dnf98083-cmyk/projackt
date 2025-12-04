<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];
$content_code = $_GET['code'] ?? '';

if (empty($content_code)) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 상품 존재 여부 및 본인 상품 확인
$product = db_select("SELECT * FROM contents WHERE content_code = ? AND registrant_id = ?", [$content_code, $member_id]);

if (empty($product)) {
    echo "<script>alert('상품을 찾을 수 없거나 삭제 권한이 없습니다.'); history.back();</script>";
    exit;
}

$product_info = $product[0];

// DB에서 삭제
try {
    $pdo = db_get_pdo();
    $stmt = $pdo->prepare("DELETE FROM contents WHERE content_code = ? AND registrant_id = ?");
    $stmt->execute([$content_code, $member_id]);

    // 이미지 파일 삭제 (선택 사항)
    if (!empty($product_info['content_img']) && file_exists($product_info['content_img'])) {
        @unlink($product_info['content_img']);
    }

    echo "<script>alert('상품이 삭제되었습니다.'); location.href='my-page_product_list.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('삭제 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
