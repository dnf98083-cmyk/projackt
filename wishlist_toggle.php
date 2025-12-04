<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/session.php';
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['member_id'])) {
    echo json_encode(['ok' => false, 'error' => 'NOT_AUTH']);
    exit;
}
$member_id = $_SESSION['member_id'];

$code = isset($_POST['content_code']) ? trim($_POST['content_code']) : '';
if ($code === '') {
    echo json_encode(['ok' => false, 'error' => 'INVALID_CODE']);
    exit;
}

// Ensure table exists
try {
    $createSql = "CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id VARCHAR(64) NOT NULL,
        content_code VARCHAR(64) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_member_code (member_id, content_code)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    db_update_delete($createSql);
} catch (Exception $e) {
    // ignore
}

// Toggle: if exists -> delete, else -> insert
$exists = db_select("SELECT id FROM wishlist WHERE member_id = ? AND content_code = ? LIMIT 1", [$member_id, $code]);
if (!empty($exists)) {
    $ok = db_update_delete("DELETE FROM wishlist WHERE member_id = ? AND content_code = ?", [$member_id, $code]);
    echo json_encode(['ok' => (bool)$ok, 'toggled' => 'removed']);
    exit;
} else {
    $ok = db_insert("INSERT INTO wishlist (member_id, content_code) VALUES (?, ?)", [$member_id, $code]);
    echo json_encode(['ok' => (bool)$ok, 'toggled' => 'added']);
    exit;
}
?>