<?php
require_once("inc/db.php");
require_once("inc/session.php");

if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];
$mode = $_POST['mode'] ?? '';

if ($mode === 'insert') {
    $recipient_name = $_POST['recipient_name'];
    $recipient_phone = $_POST['recipient_phone'];
    $address = $_POST['address'];
    $address_detail = $_POST['address_detail'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    if ($is_default) {
        // 기존 기본 배송지 해제
        db_update_delete("UPDATE delivery_address SET is_default = 0 WHERE member_id = ?", [$member_id]);
    } else {
        // 첫 배송지라면 기본으로 설정
        $count = db_select("SELECT COUNT(*) as cnt FROM delivery_address WHERE member_id = ?", [$member_id]);
        if ($count[0]['cnt'] == 0) {
            $is_default = 1;
        }
    }

    db_insert("INSERT INTO delivery_address (member_id, recipient_name, recipient_phone, address, address_detail, is_default) VALUES (?, ?, ?, ?, ?, ?)", 
        [$member_id, $recipient_name, $recipient_phone, $address, $address_detail, $is_default]);

} elseif ($mode === 'delete') {
    $id = $_POST['id'];
    db_update_delete("DELETE FROM delivery_address WHERE id = ? AND member_id = ?", [$id, $member_id]);

} elseif ($mode === 'set_default') {
    $id = $_POST['id'];
    db_update_delete("UPDATE delivery_address SET is_default = 0 WHERE member_id = ?", [$member_id]);
    db_update_delete("UPDATE delivery_address SET is_default = 1 WHERE id = ? AND member_id = ?", [$id, $member_id]);
}

echo "<script>location.href='my-page_address.php';</script>";
?>