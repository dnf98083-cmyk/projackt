<?php
require_once 'inc/session.php';
require_once 'inc/db.php';

// 관리자 권한 확인
if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

// POST 데이터 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('잘못된 접근입니다.'); location.href='manager_notice.php';</script>";
    exit;
}

$id = isset($_POST['id']) ? $_POST['id'] : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

// 유효성 검사
if (empty($id) || empty($title) || empty($content)) {
    echo "<script>alert('필수 항목이 누락되었습니다.'); history.back();</script>";
    exit;
}

try {
    // DB 업데이트
    $sql = "UPDATE notice SET title = ?, content = ? WHERE id = ?";
    db_update_delete($sql, [$title, $content, $id]);

    echo "<script>alert('공지사항이 수정되었습니다.'); location.href='manager_notice.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('수정 중 오류가 발생했습니다.'); history.back();</script>";
}
?>