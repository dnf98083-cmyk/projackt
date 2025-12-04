<?php
// DB 헬퍼 로드
require_once __DIR__ . "/inc/db.php";

// 헤더 설정: JSON 응답임을 명시
header('Content-Type: application/json');

// ----------------------------------------------------
// 1. 요청 아이디 가져오기
// ----------------------------------------------------
// AJAX 요청에서 'id' 매개변수를 받습니다.
$member_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';

// 2. 기본 유효성 검사 (아이디가 비어있는 경우)
if (empty($member_id)) {
    echo json_encode(['result' => 'error', 'message' => '아이디를 입력해 주세요.']);
    exit;
}

// ----------------------------------------------------
// 3. 데이터베이스 조회
// ----------------------------------------------------
try {
    // 'members' 테이블에 해당 'id'를 가진 레코드가 있는지 확인
    $query = "SELECT COUNT(*) AS count FROM members WHERE id = ?";
    $result = db_select($query, [$member_id]);

    $is_duplicate = false;
    if (!empty($result) && $result[0]['count'] > 0) {
        $is_duplicate = true;
    }

    // ----------------------------------------------------
    // 4. 결과 반환 (JSON 형식)
    // ----------------------------------------------------
    if ($is_duplicate) {
        echo json_encode(['result' => 'fail', 'message' => '"' . htmlspecialchars($member_id) . '"은(는) 이미 사용 중인 아이디입니다.']);
    } else {
        echo json_encode(['result' => 'success', 'message' => '"' . htmlspecialchars($member_id) . '"은(는) 사용 가능한 아이디입니다.']);
    }

} catch (Exception $e) {
    // DB 연결 또는 쿼리 실행 중 오류 발생 시
    error_log("Check ID DB Error: " . $e->getMessage());
    echo json_encode(['result' => 'error', 'message' => '서버 오류가 발생했습니다.']);
}

exit;
?>