<?php
require_once 'inc/db.php';
require_once 'inc/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('잘못된 요청입니다.');
}

$uid = $_SESSION['reset_uid'] ?? null;
$new_pw = $_POST['new_pw'] ?? '';

if (!$uid || !$new_pw) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='login.php';</script>";
    exit;
}

// 비밀번호 해싱
$hashed_pw = password_hash($new_pw, PASSWORD_DEFAULT);

// DB 업데이트
$result = db_update_delete("UPDATE members SET pass = ? WHERE id = ?", [$hashed_pw, $uid]);

if ($result) {
    // 세션 정리
    unset($_SESSION['reset_uid']);
    echo "<script>alert('비밀번호가 성공적으로 변경되었습니다. 로그인해주세요.'); location.href='login.php';</script>";
} else {
    echo "<script>alert('비밀번호 변경에 실패했습니다. 다시 시도해주세요.'); history.back();</script>";
}
?>