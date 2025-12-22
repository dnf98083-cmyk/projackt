<?php
require_once __DIR__ . "/../inc/db.php";

try {
    $pdo = db_get_pdo();

    // 1. Notices Table
    $sql = "CREATE TABLE IF NOT EXISTS `notices` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `writer` VARCHAR(50) DEFAULT 'Admin',
        `views` INT DEFAULT 0,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "Notices table created.\n";

    // 2. FAQs Table
    $sql = "CREATE TABLE IF NOT EXISTS `faqs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `category` VARCHAR(50) NOT NULL,
        `question` VARCHAR(255) NOT NULL,
        `answer` TEXT NOT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "FAQs table created.\n";

    // 3. Inquiries Table
    $sql = "CREATE TABLE IF NOT EXISTS `inquiries` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `member_id` VARCHAR(50) NOT NULL,
        `type` VARCHAR(50) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `answer` TEXT DEFAULT NULL,
        `status` VARCHAR(20) DEFAULT 'waiting',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "Inquiries table created.\n";

    // Insert dummy data if empty
    $count = $pdo->query("SELECT COUNT(*) FROM notices")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO notices (title, content, created_at) VALUES 
            ('추석 연휴 배송 마감 안내', '추석 연휴로 인해 9월 10일 배송이 마감됩니다.', '2025-09-01 10:00:00'),
            ('신규 회원 가입 혜택 안내', '신규 회원 가입 시 3,000원 쿠폰을 드립니다.', '2025-08-01 09:00:00'),
            ('시스템 점검 안내', '새벽 2시부터 4시까지 시스템 점검이 있습니다.', '2025-10-05 14:00:00')
        ");
        echo "Dummy notices inserted.\n";
    }

    $count = $pdo->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO faqs (category, question, answer) VALUES 
            ('주문/결제', '주문을 취소하고 싶어요.', '주문 취소는 마이페이지 > 주문내역에서 가능합니다. 배송 준비 중일 경우 고객센터로 문의해주세요.'),
            ('배송', '배송지 변경은 어떻게 하나요?', '배송지 변경은 주문 상태가 입금대기, 결제완료 상태일 때만 가능합니다.'),
            ('상품', '신선식품 보관 방법이 궁금해요.', '수령 즉시 냉장 보관해주시기 바랍니다.'),
            ('배송', '제주/도서산간 지역 배송 안내', '제주 및 도서산간 지역은 추가 배송비가 발생할 수 있습니다.'),
            ('교환/반품', '상품에 문제가 있어요.', '상품 수령 후 7일 이내에 고객센터로 연락 주시면 교환/반품 절차를 안내해 드립니다.')
        ");
        echo "Dummy FAQs inserted.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
