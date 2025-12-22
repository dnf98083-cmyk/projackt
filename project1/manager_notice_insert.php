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

$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$writer = $_SESSION['member_name']; // 현재 로그인한 관리자 이름

// 유효성 검사
if (empty($title) || empty($content)) {
    echo "<script>alert('제목과 내용을 모두 입력해주세요.'); history.back();</script>";
    exit;
}

try {
    // DB 입력
    $sql = "INSERT INTO notice (title, content, writer, views, reg_date) VALUES (?, ?, ?, 0, NOW())";
    db_insert($sql, [$title, $content, $writer]);

    echo "<script>alert('공지사항이 등록되었습니다.'); location.href='manager_notice.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('등록 중 오류가 발생했습니다.'); history.back();</script>";
}
?>