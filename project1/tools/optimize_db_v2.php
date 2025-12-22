<?php
require_once __DIR__ . '/../inc/db.php';

function add_index_if_not_exists($table, $column, $index_name) {
    try {
        // 인덱스 존재 여부 확인
        $check = db_select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index_name]);
        if (empty($check)) {
            // 인덱스 추가
            db_update_delete("ALTER TABLE {$table} ADD INDEX {$index_name} ({$column})");
            echo "Added index {$index_name} on {$table}({$column})<br>";
        } else {
            echo "Index {$index_name} already exists on {$table}<br>";
        }
    } catch (Exception $e) {
        echo "Error adding index {$index_name}: " . $e->getMessage() . "<br>";
    }
}

echo "Starting Database Optimization...<br><br>";

// 1. contents 테이블 최적화
add_index_if_not_exists('contents', 'category_large', 'idx_category_large');
add_index_if_not_exists('contents', 'content_price', 'idx_content_price');
add_index_if_not_exists('contents', 'content_sales', 'idx_content_sales');

// 2. review 테이블 최적화 (상품별 리뷰 카운트/평점 계산 속도 향상)
add_index_if_not_exists('review', 'content_code', 'idx_review_content_code');

// 3. pay 테이블 최적화 (마이페이지 주문내역 조회 속도 향상)
add_index_if_not_exists('pay', 'member_id', 'idx_pay_member_id');
add_index_if_not_exists('pay', 'order_date', 'idx_pay_order_date');

echo "<br>Database Optimization Completed!";
?>