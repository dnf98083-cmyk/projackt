<?php
require_once __DIR__ . '/../inc/db.php';
$id = 'dnf826';
// Update level to 1 (Admin)
$result = db_update_delete("UPDATE members SET level = 1 WHERE id = ?", [$id]);

// Verify
$user = db_select("SELECT id, name, level FROM members WHERE id = ?", [$id]);

if ($user && $user[0]['level'] == 1) {
    echo "성공: " . $user[0]['name'] . "(" . $user[0]['id'] . ") 님이 최고 관리자(Level 1)로 설정되었습니다.";
} else {
    echo "실패: 업데이트에 실패했습니다.";
}
?>