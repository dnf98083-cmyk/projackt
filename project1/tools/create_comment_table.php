<?php
require_once("inc/db.php");

$sql = "
CREATE TABLE IF NOT EXISTS review_comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    review_id VARCHAR(50) NOT NULL,
    writer_id VARCHAR(50) NOT NULL,
    comment_content TEXT NOT NULL,
    reg_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (review_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $pdo = db_get_pdo();
    $pdo->exec($sql);
    echo "Table 'review_comments' created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
