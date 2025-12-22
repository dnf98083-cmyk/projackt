<?php
require_once("inc/db.php");
require_once("inc/session.php");

header('Content-Type: application/json');

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

$current_pass = $_POST['current_pass'] ?? '';

if (empty($current_pass)) {
    echo json_encode(['status' => 'error', 'message' => '비밀번호를 입력해주세요.']);
    exit;
}

$member_id = $_SESSION['member_id'];
$member_data = db_select("SELECT pass FROM members WHERE id = ?", array($member_id));

if (empty($member_data)) {
    echo json_encode(['status' => 'error', 'message' => '회원 정보를 찾을 수 없습니다.']);
    exit;
}

if (password_verify($current_pass, $member_data[0]['pass'])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'fail', 'message' => '비밀번호가 일치하지 않습니다.']);
}
?>