<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/session.php';
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['member_id'])) {
    echo json_encode(['ok' => false, 'error' => 'NOT_AUTH']);
    exit;
}

$member_id = $_SESSION['member_id'];
$input = json_decode(file_get_contents('php://input'), true);
$codes = $input['codes'] ?? [];

if (empty($codes) || !is_array($codes)) {
    echo json_encode(['ok' => true, 'wishlisted' => []]);
    exit;
}

// 찜한 상품 코드 조회
$placeholders = implode(',', array_fill(0, count($codes), '?'));
$params = array_merge([$member_id], $codes);
$query = "SELECT content_code FROM wishlist WHERE member_id = ? AND content_code IN ($placeholders)";
$results = db_select($query, $params);

$wishlisted = [];
foreach ($results as $row) {
    $wishlisted[] = $row['content_code'];
}

echo json_encode(['ok' => true, 'wishlisted' => $wishlisted]);
