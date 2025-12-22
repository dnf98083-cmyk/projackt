<?php
require_once 'inc/db.php';
require_once 'inc/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

$member_id = $_SESSION['member_id'];
$action = $_POST['action'] ?? '';

try {
    $pdo = db_get_pdo();

    if ($action === 'attendance') {
        // 오늘 이미 출석했는지 확인
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM point_history 
            WHERE member_id = ? AND type = 'attendance' AND DATE(reg_date) = CURDATE()
        ");
        $stmt->execute([$member_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => '오늘 이미 출석체크를 하셨습니다.']);
            exit;
        }

        // 포인트 지급 및 기록
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE members SET point = point + 10 WHERE id = ?");
        $stmt->execute([$member_id]);

        $stmt = $pdo->prepare("INSERT INTO point_history (member_id, amount, type, description, reg_date) VALUES (?, 10, 'attendance', '일일 출석체크', NOW())");
        $stmt->execute([$member_id]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => '출석체크 완료! 10포인트가 적립되었습니다.', 'points' => 10]);

    } elseif ($action === 'ad') {
        // 광고 시청은 횟수 제한이 없다고 가정 (또는 필요시 제한 추가 가능)
        // 포인트 지급 및 기록
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE members SET point = point + 10 WHERE id = ?");
        $stmt->execute([$member_id]);

        $stmt = $pdo->prepare("INSERT INTO point_history (member_id, amount, type, description, reg_date) VALUES (?, 10, 'ad', '광고 시청 보상', NOW())");
        $stmt->execute([$member_id]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => '광고 시청 완료! 10포인트가 적립되었습니다.', 'points' => 10]);

    } else {
        echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => '오류가 발생했습니다: ' . $e->getMessage()]);
}
?>
