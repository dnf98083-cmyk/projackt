<?php
require_once 'inc/db.php';

$sql = "
CREATE TABLE IF NOT EXISTS `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `writer` varchar(50) NOT NULL,
  `views` int(11) DEFAULT 0,
  `reg_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

$pdo = db_get_pdo();
try {
    $pdo->exec($sql);
    echo "Notice table created successfully.";
    
    // Add some sample data
    $check = db_select("SELECT count(*) as cnt FROM notice");
    if ($check[0]['cnt'] == 0) {
        db_insert("INSERT INTO notice (title, content, writer) VALUES (?, ?, ?)", ["서비스 점검 안내", "새벽 2시부터 4시까지 점검이 있습니다.", "admin"]);
        db_insert("INSERT INTO notice (title, content, writer) VALUES (?, ?, ?)", ["신규 회원 혜택 안내", "가입 시 3000포인트 지급!", "admin"]);
        echo " Sample data added.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>