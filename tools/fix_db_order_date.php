<?php
require_once("../inc/db.php");

try {
    $pdo = db_get_pdo();
    echo "Attempting to add order_date column...<br>";
    
    // 컬럼이 있는지 확인하는 것이 좋지만, 그냥 ADD COLUMN 하고 에러나면 무시하는 방식이 간단함 (Duplicate column name error)
    // 하지만 여기서는 명확히 하기 위해 try-catch
    try {
        $pdo->exec("ALTER TABLE pay ADD COLUMN order_date DATETIME DEFAULT CURRENT_TIMESTAMP");
        echo "Successfully added 'order_date' column.<br>";
    } catch (PDOException $e) {
        echo "Column 'order_date' might already exist or error: " . $e->getMessage() . "<br>";
    }
    
    // 확인
    $stmt = $pdo->query("SHOW COLUMNS FROM pay LIKE 'order_date'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($col) {
        echo "Verification: Column 'order_date' exists.<br>";
    } else {
        echo "Verification: Column 'order_date' DOES NOT exist.<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>