<?php
require_once("inc/db.php"); // PDO 헬퍼 함수 로드
require_once("inc/session.php"); // 세션 헬퍼 로드

// ----------------------------------------------------
// 1. 입력 데이터 수집 및 정리
// ----------------------------------------------------
$id   = $_POST["id"] ?? null;
$pass = $_POST["pass"] ?? null;
$name = $_POST["name"] ?? null;
$phone = $_POST["phone"] ?? null;
$birth = $_POST["birth"] ?? null;
$email1  = $_POST["email1"] ?? null;
$email2  = $_POST["email2"] ?? null;

// 이메일 주소 결합
$email = trim($email1 . "@" . $email2, '@');

// 비밀번호 암호화 (로그인 시 password_verify를 위해 필수)
$bcrypt_pw = password_hash($pass, PASSWORD_BCRYPT);

// DB에 저장할 기본값 설정
$regist_day = date("Y-m-d (H:i)");
$level = 9; // 기본 레벨 (haru.sql 기준)
$point = 0;

// ----------------------------------------------------
// 2. DB 삽입 (PDO 함수 사용)
// ----------------------------------------------------
$last_id = db_insert(
    "INSERT INTO members(id, pass, name, phone, birth, email, refferer, regist_day, level, point) 
    VALUES (:id, :pass, :name, :phone, :birth, :email, :refferer, :regist_day, :level, :point)",

    array(
        'id' => $id,
        'pass' => $bcrypt_pw,
        'name' => $name,
        'phone' => $phone,
        'birth' => $birth,
        'email' => $email,
        'refferer' => $_POST["refferer"] ?? null,
        'regist_day' => $regist_day,
        'level' => $level,
        'point' => $point
    )
);

// ----------------------------------------------------
// 3. 결과 처리
// ----------------------------------------------------

if ($last_id) {
    // 삽입 성공 시
    echo "
        <script>
            alert('회원가입에 성공했습니다.');
            location.href = 'success.php';
        </script>";
} else {
    // 삽입 실패 시 (db_insert 함수에서 이미 오류를 기록했지만, 사용자에게 알림)
    echo "
        <script>
            alert('회원가입 중 데이터베이스 오류가 발생했습니다. 다시 시도해 주세요.');
            history.back();
        </script>";
}

?>