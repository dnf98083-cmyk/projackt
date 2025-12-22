<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// POST 데이터 수신
$name = $_POST['content_name'] ?? '';
$category = $_POST['category_large'] ?? '';
$price = (int)($_POST['content_price'] ?? 0);
$content = $_POST['content_content'] ?? '';

if (empty($name) || empty($category) || $price <= 0) {
    echo "<script>alert('필수 정보를 모두 입력해주세요.'); history.back();</script>";
    exit;
}

// 원가 계산 (임의로 60% 설정)
$cost = floor($price * 0.6);

// 상품 코드 생성 (MK-YYYYMMDD-XXXX)
$today = date("Ymd");
$prefix = "MK-" . $today . "-";

// 오늘 등록된 마지막 번호 조회
$last_code_query = "SELECT content_code FROM contents WHERE content_code LIKE '$prefix%' ORDER BY content_code DESC LIMIT 1";
$last_code_result = db_select($last_code_query);

if (!empty($last_code_result)) {
    $last_num = (int)substr($last_code_result[0]['content_code'], -4);
    $new_num = $last_num + 1;
} else {
    $new_num = 1;
}

$code = $prefix . sprintf("%04d", $new_num);

// 이미지 업로드 처리
$upload_dir = "img/organized/" . $category . "/";
// 폴더가 없으면 생성
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$img_path = "";
if (isset($_FILES['content_img']) && $_FILES['content_img']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['content_img']['tmp_name'];
    $origin_name = $_FILES['content_img']['name'];
    $ext = pathinfo($origin_name, PATHINFO_EXTENSION);
    
    // 파일명: 상품코드.확장자
    $new_filename = $code . "." . $ext;
    $dest_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($tmp_name, $dest_path)) {
        $img_path = $dest_path;
    } else {
        echo "<script>alert('이미지 업로드에 실패했습니다.'); history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('상품 이미지는 필수입니다.'); history.back();</script>";
    exit;
}

// DB Insert
try {
    $pdo = db_get_pdo();
    $sql = "INSERT INTO contents (
        content_code, 
        content_name, 
        category_large, 
        content_price, 
        content_cost, 
        content_img, 
        content_content, 
        deliv_today, 
        discount_rate, 
        content_sales,
        registrant_id,
        regist_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'N', 0, 0, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $code,
        $name,
        $category,
        $price,
        $cost,
        $img_path,
        $content,
        $_SESSION['member_id']
    ]);
    
    echo "<script>
        alert('상품이 성공적으로 등록되었습니다!');
        location.href = 'new.php';
    </script>";
    
} catch (Exception $e) {
    echo "<script>alert('DB 오류: " . addslashes($e->getMessage()) . "'); history.back();</script>";
}
?>
