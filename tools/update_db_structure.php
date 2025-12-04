<?php
require_once __DIR__ . "/../inc/db.php";

try {
    $pdo = db_get_pdo();
    echo "DB 연결 성공.\n";

    // 1. content_content 컬럼 추가 (상품 설명)
    try {
        $pdo->exec("ALTER TABLE contents ADD COLUMN content_content TEXT");
        echo "content_content 컬럼 추가 완료.\n";
    } catch (Exception $e) {
        echo "content_content 컬럼 추가 실패 (이미 존재할 수 있음): " . $e->getMessage() . "\n";
    }

    // 2. registrant_id 컬럼 추가 (등록자 ID)
    try {
        $pdo->exec("ALTER TABLE contents ADD COLUMN registrant_id VARCHAR(50)");
        echo "registrant_id 컬럼 추가 완료.\n";
    } catch (Exception $e) {
        echo "registrant_id 컬럼 추가 실패 (이미 존재할 수 있음): " . $e->getMessage() . "\n";
    }

    // 3. regist_date 컬럼 추가 (등록일) - 기존 데이터는 NULL일 수 있음
    try {
        $pdo->exec("ALTER TABLE contents ADD COLUMN regist_date DATETIME DEFAULT CURRENT_TIMESTAMP");
        echo "regist_date 컬럼 추가 완료.\n";
    } catch (Exception $e) {
        echo "regist_date 컬럼 추가 실패 (이미 존재할 수 있음): " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "치명적 오류: " . $e->getMessage();
}
