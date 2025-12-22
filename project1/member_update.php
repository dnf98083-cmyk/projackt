<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 1. 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];

// 2. 입력값 수집
$current_pass = $_POST["current_pass"] ?? null;
$pass = $_POST["pass"] ?? null;
$pass_confirm = $_POST["pass_confirm"] ?? null;
$name = $_POST["name"] ?? null;
$phone = $_POST["phone"] ?? null;
$birth = $_POST["birth"] ?? null;
$email1 = $_POST["email1"] ?? null;
$email2 = $_POST["email2"] ?? null;

$email = trim($email1 . "@" . $email2, '@');

// 3. 유효성 검사
if (empty($current_pass)) {
    echo "<script>alert('현재 비밀번호를 입력해주세요.'); history.back();</script>";
    exit;
}

if (empty($name) || empty($phone)) {
    echo "<script>alert('이름과 전화번호는 필수 입력 항목입니다.'); history.back();</script>";
    exit;
}

// 3-1. 현재 비밀번호 확인
$member_data = db_select("SELECT pass FROM members WHERE id = ?", array($member_id));
if (empty($member_data)) {
    echo "<script>alert('회원 정보를 찾을 수 없습니다.'); location.href='login.php';</script>";
    exit;
}

if (!password_verify($current_pass, $member_data[0]['pass'])) {
    echo "<script>alert('현재 비밀번호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 4. 업데이트 쿼리 구성
$params = array();
$sql = "UPDATE members SET name = ?, phone = ?, birth = ?, email = ?";
$params[] = $name;
$params[] = $phone;
$params[] = $birth;
$params[] = $email;

// 비밀번호 변경 요청이 있는 경우
if (!empty($pass)) {
    if ($pass !== $pass_confirm) {
        echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit;
    }
    $sql .= ", pass = ?";
    $params[] = password_hash($pass, PASSWORD_BCRYPT);
}

$sql .= " WHERE id = ?";
$params[] = $member_id;

// 5. DB 업데이트 실행
$pdo = db_get_pdo();
try {
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);

    if ($result) {
        // 세션 정보 업데이트 (이름이 변경되었을 수 있으므로)
        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            $_SESSION['user']['name'] = $name;
        }

        echo "<script>
            alert('회원정보가 수정되었습니다.');
            location.href = 'my-page_mem_info.php';
        </script>";
    } else {
        echo "<script>alert('정보 수정에 실패했습니다.'); history.back();</script>";
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>alert('시스템 오류가 발생했습니다.'); history.back();</script>";
}
?>