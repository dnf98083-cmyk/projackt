<?php
require_once __DIR__ . '/../inc/db.php';

try {
    $pdo = db_get_pdo();
    // Check if review_comments table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'review_comments'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'review_comments' exists.\n";
    } else {
        echo "Table 'review_comments' does NOT exist.\n";
        // Create it if it doesn't exist (it should, based on history, but let's be safe)
        $sql = "CREATE TABLE IF NOT EXISTS `review_comments` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `review_id` CHAR(15) NOT NULL,
            `member_id` CHAR(15) NOT NULL,
            `comment` TEXT NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($sql);
        echo "Table 'review_comments' created.\n";
    }

    // Check if 'views' column exists in 'review' table
    $stmt = $pdo->query("SHOW COLUMNS FROM review LIKE 'views'");
    if ($stmt->rowCount() > 0) {
        echo "Column 'views' in 'review' table exists.\n";
    } else {
        echo "Column 'views' in 'review' table does NOT exist. Adding it...\n";
        $pdo->exec("ALTER TABLE review ADD COLUMN views INT DEFAULT 0");
        echo "Column 'views' added.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
