<?php
// 모든 출력 버퍼 시작 및 에러 출력 방지
ob_start();
error_reporting(0);

// DB 헬퍼 및 세션 로드
require_once("inc/db.php");
require_once("inc/session.php");

// 버퍼 비우기 (include 파일의 불필요한 출력 제거)
ob_clean();

// JSON 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 로그인 확인
$user_id = $_SESSION['member_id'] ?? null;

if (empty($user_id)) {
    echo json_encode(['success' => false, 'error' => 'NOT_AUTH'], JSON_UNESCAPED_UNICODE);
    exit;
}

// cart_ids 받기
$cart_ids = $_POST['cart_ids'] ?? '';

if (empty($cart_ids)) {
    echo json_encode(['success' => false, 'error' => 'NO_IDS'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 배열로 변환
$ids = explode(',', $cart_ids);
$ids = array_map('trim', $ids);
$ids = array_filter($ids);

if (empty($ids)) {
    echo json_encode(['success' => false, 'error' => 'NO_IDS'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 삭제 쿼리
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$params = array_merge([$user_id], $ids);
$query = "DELETE FROM cart WHERE user_id = ? AND cart_id IN ($placeholders)";

try {
    $result = db_update_delete($query, $params);
    
    if ($result === false) {
        echo json_encode(['success' => false, 'error' => 'DB_ERROR'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => true, 'deleted_count' => $result], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
?>