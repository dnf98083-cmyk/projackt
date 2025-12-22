<?php
// ----------------------------------------------------
// 0. 공통 모듈 로드
// ----------------------------------------------------
require_once(__DIR__ . "/inc/db.php");
require_once(__DIR__ . "/inc/session.php");

// ----------------------------------------------------
// 1. 로그인 여부 확인
// ----------------------------------------------------
$user_id = $_SESSION['member_id'] ?? null;

// 로그인 안 했으면 장바구니는 비어 있게 처리
if (empty($user_id)) {
    $result = [];
    $_SESSION['cart_count'] = 0;
    return; 
}

// ----------------------------------------------------
// 2. DB에서 장바구니 조회
// ----------------------------------------------------
// cart 테이블 구조 :
// cart_id, content_code, content_options, content_amount, user_id
$query = "SELECT * FROM cart WHERE user_id = ? ORDER BY cart_id DESC";
$cart_result = db_select($query, [$user_id]);

// 조회 실패 시 안전처리
if (!is_array($cart_result)) {
    $cart_result = [];
}

// cart.php에서 사용 가능한 변수명으로 맞춰줌
$result = $cart_result;

// ----------------------------------------------------
// 3. cart.php 반복문에서 사용할 cart_count 저장
// ----------------------------------------------------
$_SESSION['cart_count'] = count($result);

// 디버깅용 : 필요하면 열기
// var_dump($result);
?>
