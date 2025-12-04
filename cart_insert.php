<?php
// DB 헬퍼 및 세션 로드
require_once("inc/db.php");
require_once("inc/session.php");

// ----------------------------------------------------
// 1. 로그인 및 필수 데이터 확인
// ----------------------------------------------------
$user_id = $_SESSION['member_id'] ?? null;

if (empty($user_id)) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='login.php';</script>";
    exit;
}

// POST 데이터 수집
$content_code    = $_POST["content_code"] ?? null;
$content_options = $_POST["content_options"] ?? null; // 예: #FFFFFF / free
$content_amount  = $_POST["content_amount"] ?? 1;

// 필수값 체크
if (empty($content_code) || empty($content_options) || $content_amount < 1) {
    echo "<script>alert('상품 정보 또는 옵션/수량이 유효하지 않아 장바구니에 담을 수 없습니다.'); history.back();</script>";
    exit;
}

// ----------------------------------------------------
// 2. cart_id 생성
// ----------------------------------------------------
$cart_id = date("YmdHis");

// ----------------------------------------------------
// 3. 기존 동일 상품 여부 확인 -> 있으면 수량 합치기
// ----------------------------------------------------
$check_query = "
    SELECT cart_id, content_amount 
    FROM cart 
    WHERE user_id = ? AND content_code = ? AND content_options = ?
";
$existing_item = db_select($check_query, [$user_id, $content_code, $content_options]);

$result  = false;
$message = "";

if (!empty($existing_item)) {
    // 이미 있으면 수량 증가
    $new_amount      = (int)$existing_item[0]['content_amount'] + (int)$content_amount;
    $existing_cart_id = $existing_item[0]['cart_id'];

    $update_query = "UPDATE cart SET content_amount = ? WHERE cart_id = ?";
    $result  = db_update_delete($update_query, [$new_amount, $existing_cart_id]);
    $message = "장바구니에 동일 상품이 있어 수량이 합산되었습니다.";

} else {
    // 없으면 새로 추가
    $insert_query = "
        INSERT INTO cart (cart_id, content_code, content_options, content_amount, user_id) 
        VALUES (?, ?, ?, ?, ?)
    ";
    
    $result  = db_insert($insert_query, [$cart_id, $content_code, $content_options, $content_amount, $user_id]);
    
    $message = "상품이 장바구니에 성공적으로 담겼습니다.";
}

// ----------------------------------------------------
// 4. 결과 처리
// ----------------------------------------------------
// db_insert는 lastInsertId()를 반환하는데, AUTO_INCREMENT가 없으면 0을 반환할 수 있음
// 0도 성공으로 처리해야 함 (false가 아닌 경우 모두 성공)
if ($result !== false) {
    // ✅ 성공 시: 알림 후 이전 페이지로 이동 (새로고침 효과)
    echo "<script>
            alert('{$message}');
            history.back();
          </script>";
    exit;
} else {
    // ❌ 실패 시: 에러 띄우고 뒤로가기
    echo "<script>
            alert('장바구니 담기에 실패했습니다. 데이터: user_id={$user_id}, code={$content_code}');
            history.back();
          </script>";
    exit;
}
?>
