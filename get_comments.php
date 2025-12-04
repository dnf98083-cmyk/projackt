<?php
require_once("inc/db.php");

header('Content-Type: application/json');

$review_id = $_GET['review_id'] ?? '';

if (empty($review_id)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT c.*, m.name as writer_name 
        FROM review_comments c 
        LEFT JOIN members m ON c.writer_id = m.id 
        WHERE c.review_id = ? 
        ORDER BY c.reg_date ASC";
$comments = db_select($sql, [$review_id]);

echo json_encode($comments);
?>
