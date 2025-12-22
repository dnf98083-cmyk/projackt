<?php
// DB 헬퍼 로드 (경로 고정)
require_once(__DIR__ . "/inc/db.php");

// 메인 목록
$result = db_select("SELECT * FROM contents");

// 정렬용
$result_price_H = db_select("SELECT * FROM contents ORDER BY content_price DESC"); // 높은가격순
$result_price_L = db_select("SELECT * FROM contents ORDER BY content_price ASC");  // 낮은가격순

// 안전 가드(쿼리 실패시 false 방지)
if (!is_array($result)) $result = [];
if (!is_array($result_price_H)) $result_price_H = [];
if (!is_array($result_price_L)) $result_price_L = [];
