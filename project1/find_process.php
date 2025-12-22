<?php
require_once 'inc/db.php';
require_once 'inc/session.php';

header('Content-Type: application/json');

$mode = $_POST['mode'] ?? '';

if ($mode === 'find_id') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // 전화번호 하이픈 제거 등 정규화 필요 시 추가
    // $phone = str_replace('-', '', $phone);

    $member = db_select("SELECT id FROM members WHERE name = ? AND phone = ?", [$name, $phone]);

    if (!empty($member)) {
        $id = $member[0]['id'];
        // 아이디 마스킹 (앞 2글자 제외하고 *)
        $masked_id = mb_substr($id, 0, 2) . str_repeat('*', max(0, mb_strlen($id) - 2));
        
        echo json_encode(['status' => 'success', 'id' => $masked_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => '일치하는 회원 정보가 없습니다.']);
    }
    exit;
}

if ($mode === 'find_pw') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $member = db_select("SELECT num FROM members WHERE id = ? AND name = ? AND phone = ?", [$id, $name, $phone]);

    if (!empty($member)) {
        // 인증 성공: 세션에 재설정 권한 부여
        $_SESSION['reset_uid'] = $id;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '일치하는 회원 정보가 없습니다.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
?>