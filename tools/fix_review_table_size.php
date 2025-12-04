<?php
require_once __DIR__ . '/../inc/db.php';

try {
    $pdo = db_get_pdo();
    
    // review 테이블의 content_code 컬럼 크기 변경
    $sql = "ALTER TABLE review MODIFY content_code VARCHAR(50) NOT NULL";
    $pdo->exec($sql);
    
    echo "Successfully updated review table structure.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
