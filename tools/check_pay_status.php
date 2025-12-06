<?php
require_once 'inc/db.php';

try {
    $pdo = db_get_pdo();
    
    // Check pay table columns
    $stmt = $pdo->query("DESCRIBE pay");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Pay table columns: " . implode(", ", $columns) . "\n";

    // Check distinct status values
    $stmt = $pdo->query("SELECT DISTINCT status FROM pay");
    $statuses = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Distinct statuses: " . implode(", ", $statuses) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>