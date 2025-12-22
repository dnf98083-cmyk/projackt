<?php
require_once("inc/db.php");
require_once("inc/session.php");

header('Content-Type: application/json');

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

$review_id = $_POST['review_id'] ?? '';
$content = $_POST['content'] ?? '';
$writer_id = $_SESSION['member_id'];

if (empty($review_id) || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => '내용을 입력해주세요.']);
    exit;
}

$sql = "INSERT INTO review_comments (review_id, writer_id, comment_content) VALUES (?, ?, ?)";
$result = db_insert($sql, [$review_id, $writer_id, $content]);

if ($result) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => '등록 실패']);
}
?>
