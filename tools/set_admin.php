<?php
require_once '../inc/db.php';

// 사용법: 브라우저에서 http://localhost/examplemall_kwr/tools/set_admin.php?id=사용자아이디 로 접속
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "사용법: ?id=사용자아이디 형태로 접속하세요.<br>";
    echo "예: set_admin.php?id=song";
    exit;
}

$member = db_select("SELECT * FROM members WHERE id = ?", [$id]);

if (empty($member)) {
    echo "존재하지 않는 아이디입니다: " . htmlspecialchars($id);
    exit;
}

$result = db_update_delete("UPDATE members SET level = 1 WHERE id = ?", [$id]);

if ($result) {
    echo "성공! '{$id}' 사용자가 최고 관리자(Level 1)로 설정되었습니다.<br>";
    echo "<a href='../index.php'>메인으로 이동</a>";
} else {
    echo "실패했습니다. DB 오류일 수 있습니다.";
}
?>