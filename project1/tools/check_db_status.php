<?php
require_once __DIR__ . "/../inc/db.php";

function check_table($pdo, $tableName) {
    echo "--------------------------------------------------\n";
    echo "테이블 점검: [$tableName]\n";
    
    // 1. 행 개수 확인
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $tableName");
        $count = $stmt->fetchColumn();
        echo "- 총 데이터 수: $count 개\n";
    } catch (Exception $e) {
        echo "- 테이블이 존재하지 않거나 오류 발생: " . $e->getMessage() . "\n";
        return;
    }

    // 2. 컬럼 정보 확인
    try {
        $stmt = $pdo->query("DESCRIBE $tableName");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "- 주요 컬럼: " . implode(", ", $columns) . "\n";
        
        // 3. 샘플 데이터 1건 확인
        $stmt = $pdo->query("SELECT * FROM $tableName LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "- 샘플 데이터(1건): " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
        }
    } catch (Exception $e) {
        echo "- 컬럼 정보 조회 실패\n";
    }
}

try {
    $pdo = db_get_pdo();
    echo "데이터베이스 연결 성공.\n";
    
    $tables = ['contents', 'members', 'pay', 'review', 'cart', 'wishlist'];
    
    foreach ($tables as $table) {
        check_table($pdo, $table);
    }
    
} catch (Exception $e) {
    echo "DB 연결 실패: " . $e->getMessage();
}
