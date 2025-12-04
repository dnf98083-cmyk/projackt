<?php
require_once 'inc/db.php';

try {
    $pdo = db_get_pdo();

    // inquiries 테이블 생성
    $sql = "CREATE TABLE IF NOT EXISTS inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id VARCHAR(50) NOT NULL,
        type VARCHAR(50) NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        status VARCHAR(20) DEFAULT 'waiting',
        answer TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        answered_at DATETIME NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "inquiries table created successfully.<br>";

    // 샘플 데이터 추가 (테스트용)
    $check = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
    if ($check == 0) {
        $sql = "INSERT INTO inquiries (member_id, type, title, content, status, created_at) VALUES 
        ('testuser', '배송', '배송이 언제 되나요?', '주문한지 3일 지났습니다.', 'waiting', NOW()),
        ('user2', '상품', '상품 불량입니다.', '교환해주세요.', 'answered', NOW())";
        $pdo->exec($sql);
        echo "Sample data inserted.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>