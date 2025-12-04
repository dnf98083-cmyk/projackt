<?php
require_once 'inc/db.php';
try {
    $pdo = db_get_pdo(); // Assuming get_pdo() is available in db.php or I can use db_select
    $stmt = $pdo->query("DESCRIBE members");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>