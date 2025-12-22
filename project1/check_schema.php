<?php
require_once 'inc/db.php';
try {
    $pdo = db_get_pdo();
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
    
    if (in_array('contents', $tables)) {
        echo "\n\ncontents table structure:\n";
        $stmt = $pdo->query("DESCRIBE contents");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    if (in_array('review', $tables)) {
        echo "\n\nreview table structure:\n";
        $stmt = $pdo->query("DESCRIBE review");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>