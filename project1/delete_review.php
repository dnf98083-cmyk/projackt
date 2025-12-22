<?php
require_once 'inc/db.php';
require_once 'inc/session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
    exit;
}

$review_id = $_POST['review_id'] ?? null;
if (!$review_id) {
    echo json_encode(['status' => 'error', 'message' => '리뷰 ID가 없습니다.']);
    exit;
}

// 로그인 체크
$current_user = current_user(); 
$member_id = $_SESSION['member_id'] ?? ($current_user['id'] ?? null);

if (!$member_id) {
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

// 리뷰 정보 조회 (작성자 확인용)
$review = db_select("SELECT writer_id FROM review WHERE review_id = ?", [$review_id]);

if (empty($review)) {
    echo json_encode(['status' => 'error', 'message' => '존재하지 않는 리뷰입니다.']);
    exit;
}

$writer_id = $review[0]['writer_id'];
$is_admin = is_manager();
$is_writer = ($member_id === $writer_id);

if (!$is_admin && !$is_writer) {
    echo json_encode(['status' => 'error', 'message' => '삭제 권한이 없습니다.']);
    exit;
}

// 삭제 트랜잭션 (댓글도 같이 삭제해야 함)
try {
    // 댓글 삭제
    db_update_delete("DELETE FROM review_comments WHERE review_id = ?", [$review_id]);
    
    // 리뷰 삭제
    $result = db_update_delete("DELETE FROM review WHERE review_id = ?", [$review_id]);
    
    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'DB 삭제 실패']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => '삭제 중 오류가 발생했습니다.']);
}
?>