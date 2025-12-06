<?php
require_once __DIR__ . "/../inc/db.php";

try {
    $pdo = db_get_pdo();
    
    $query = "CREATE TABLE IF NOT EXISTS delivery_address (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id VARCHAR(50) NOT NULL,
        recipient_name VARCHAR(50) NOT NULL,
        recipient_phone VARCHAR(20) NOT NULL,
        address VARCHAR(255) NOT NULL,
        address_detail VARCHAR(255),
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (member_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($query);
    echo "delivery_address table created successfully.";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>