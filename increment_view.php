<?php
require_once __DIR__ . '/inc/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Invalid method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$review_id = $data['review_id'] ?? '';

if (empty($review_id)) {
    echo json_encode(['ok' => false, 'error' => 'Missing review_id']);
    exit;
}

try {
    $pdo = db_get_pdo();
    $stmt = $pdo->prepare("UPDATE review SET views = views + 1 WHERE review_id = ?");
    $stmt->execute([$review_id]);
    
    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>