<?php
// ✅ PHP 경고 메시지 제거
// echo("<script>alert('login.post.php로 들어옴');</script>"); 
require_once("inc/db.php");
require_once("inc/session.php"); // 세션 시작은 여기서 진행합니다.

// ✅ 폼 필드 이름 통일: login.php에서 name="member_id"와 name="member_pw"를 사용했습니다.
$login_id = $_POST['member_id'] ?? null; 
$login_pw = $_POST['member_pw'] ?? null; 


// 파라미터 체크
if ($login_id == null || $login_pw == null){    
    echo("<script>alert('아이디와 비밀번호를 모두 입력하여 주세요.');</script>");
    exit();
}

// 회원 데이터 조회
$member_data = db_select("select * from members where id = ?", array($login_id));

// 회원 데이터가 없다면
if (empty($member_data)){
    echo("<script>alert('존재하지 않는 아이디입니다.'); history.back();</script>");
    exit();
}

// 비밀번호 일치 여부 검증
$is_match_password = password_verify($login_pw, $member_data[0]['pass']);

// 비밀번호 불일치
if ($is_match_password === false){
    echo("<script>alert('비밀번호가 틀렸습니다.'); history.back();</script>");
    exit();
}

// ✅ 로그인 성공: 세션에 정보 저장
$_SESSION['member_id'] = $member_data[0]['id'];

// 기존에 쓰던 이름 세션
$_SESSION['name'] = $member_data[0]['name'];

// ⭐ 마이페이지에서 쓰는 이름 세션 (my-page_order.php에서 사용)
$_SESSION['member_name'] = $member_data[0]['name'];

$_SESSION['level'] = $member_data[0]['level'];
$_SESSION['point'] = $member_data[0]['point'];

// ⭐ inc/session.php의 is_manager() 함수 호환을 위해 user 배열 추가
$_SESSION['user'] = [
    'id' => $member_data[0]['id'],
    'name' => $member_data[0]['name'],
    'level' => $member_data[0]['level'],
    'point' => $member_data[0]['point']
];

// 메인페이지로 이동
header("Location: index.php");
?>
