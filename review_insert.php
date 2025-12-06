<?php
// DB 헬퍼 및 세션 로드
require_once("inc/db.php");
require_once("inc/session.php");

// 파일 업로드 관련 설정
$upload_dir = "img/reviews/"; // 이미지 저장 경로 (프로젝트 루트 기준)
$photo_path = null;
$message = ''; // 결과 메시지

// ----------------------------------------------------
// 1. 데이터 수집
// ----------------------------------------------------
$writer_id = $_SESSION['member_id'] ?? null;
$content_code = $_POST['content_code'] ?? null;
$order_id = $_POST['order_id'] ?? null; // 주문 상태 업데이트를 위한 order_id (선택 사항)
$review_contents = $_POST['review_contents'] ?? '';
$star = $_POST['star'] ?? null;

if (empty($writer_id) || empty($content_code) || empty($star) || mb_strlen($review_contents) < 10) {
    echo "<script>alert('필수 정보가 누락되었거나 내용이 너무 짧습니다. 다시 시도해주세요.'); history.back();</script>";
    exit;
}

// ----------------------------------------------------
// 2. 파일 업로드 처리
// ----------------------------------------------------
if (isset($_FILES['photo_upload']) && $_FILES['photo_upload']['error'] === 0) {
    $file = $_FILES['photo_upload'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_type = $file['type'];
    $file_error = $file['error'];
    
    // 파일명 인코딩 및 중복 방지 (현재 시간을 이용한 고유 ID + 확장자)
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid() . '_' . date("YmdHis") . '.' . $ext;
    $target_file = $upload_dir . $new_file_name;

    // 파일 이동 및 저장
    if (move_uploaded_file($file_tmp, $target_file)) {
        $photo_path = $target_file; // DB에 저장할 상대 경로
    } else {
        // 파일 업로드 실패는 경고만 하고 진행
        $message .= "파일 업로드에 실패했습니다. ";
    }
}


// ----------------------------------------------------
// 3. 리뷰 DB 삽입
// ----------------------------------------------------
// review_id 생성 (YYYYMMDD_랜덤ID)
$review_id = date("Ymd") . '_' . substr(md5(uniqid(true)), 0, 6); 

$insert_query = "INSERT INTO review (review_id, writer_id, content_code, review_contents, photo, star) 
                 VALUES (?, ?, ?, ?, ?, ?)";
                 
$params = [
    $review_id, 
    $writer_id, 
    $content_code, 
    $review_contents, 
    $photo_path, 
    $star
];

$result = db_insert($insert_query, $params);


// ----------------------------------------------------
// 4. 주문 테이블 업데이트 (리뷰 작성 완료 표시)
// ----------------------------------------------------
// my-page_order.php의 로직을 보면 pay 테이블에 'review' 필드가 있습니다.
// 이 필드를 'Y'로 업데이트하여 리뷰 작성 완료 상태를 표시합니다.
// *주의: 현재 DB 구조상 order_id로 pay 테이블을 업데이트하는 것이 필요하지만,
//        pay 테이블은 content_code가 아닌 order_contents(JSON)를 저장하므로, 
//        리뷰 완료 상태는 content_code 대신 order_id 단위로 처리합니다. 
//        (order_id는 URL에서 받았습니다.)

if (!empty($order_id)) {
    $update_pay_query = "UPDATE pay SET review = 'Y' WHERE order_id = ?";
    db_update_delete($update_pay_query, [$order_id]);
}

// [추가] 리뷰 작성 시 포인트 지급 (200P)
db_update_delete("UPDATE members SET point = point + 200 WHERE id = ?", [$writer_id]);


// ----------------------------------------------------
// 5. 결과 처리 및 리다이렉션
// ----------------------------------------------------
if ($result) {
    echo "<script>
            alert('리뷰가 성공적으로 등록되었습니다.' + '{$message}');
            location.href='my-page_order.php'; // 마이페이지 주문 내역으로 이동
          </script>";
} else {
    echo "<script>
            alert('리뷰 등록 중 데이터베이스 오류가 발생했습니다.');
            history.back();
          </script>";
}
?>