<?php
// DB 헬퍼 및 세션 로드
require_once("inc/db.php");
require_once("inc/session.php");

$user_id = $_SESSION['member_id'] ?? null;
$order_id = $_GET['order_id'] ?? null;
$content_code = $_GET['content_code'] ?? null;

if (empty($user_id)) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='login.php';</script>";
    exit;
}
if (empty($order_id) || empty($content_code)) {
    echo "<script>alert('리뷰를 작성할 주문 및 상품 정보가 필요합니다.'); history.back();</script>";
    exit;
}

// 1. 상품 정보 조회
$product_query = "SELECT content_name, content_img FROM contents WHERE content_code = ?";
$product_info = db_select($product_query, [$content_code]);

if (empty($product_info)) {
    echo "<script>alert('해당 상품 정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}
$product = $product_info[0];

// 2. 이미 리뷰를 작성했는지 확인 (review 테이블에 order_id 필드가 없으므로, content_code와 user_id로만 확인합니다.)
// *주의: review 테이블에 order_id를 추가하는 것이 이상적이지만, 현재 DB 구조를 따릅니다.
$check_query = "SELECT review_id FROM review WHERE content_code = ? AND writer_id = ?";
$is_reviewed = db_select($check_query, [$content_code, $user_id]);

if (!empty($is_reviewed)) {
    echo "<script>alert('이미 해당 상품에 대한 리뷰를 작성하셨습니다.'); location.href='my-page_order.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>리뷰 작성 - <?= htmlspecialchars($product['content_name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/review.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>

<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper review_write">
        <h1 class="page_title">리뷰 작성</h1>
        
        <div class="product_summary">
            <img src="<?= htmlspecialchars($product['content_img']) ?>" alt="<?= htmlspecialchars($product['content_name']) ?>" class="review_thumb">
            <span class="product_name"><?= htmlspecialchars($product['content_name']) ?></span>
            <span class="product_code">상품코드: <?= htmlspecialchars($content_code) ?></span>
        </div>

        <form action="review_insert.php" method="POST" name="review_form" enctype="multipart/form-data" onsubmit="return validateReview()">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
            <input type="hidden" name="content_code" value="<?= htmlspecialchars($content_code) ?>">

            <section class="review_rating">
                <h2>⭐ 상품 평점</h2>
                <div class="star_selection">
                    <input type="radio" id="star5" name="star" value="5" required><label for="star5">★</label>
                    <input type="radio" id="star4" name="star" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="star" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="star" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="star" value="1"><label for="star1">★</label>
                    <span class="rating_guide">평점을 선택해주세요.</span>
                </div>
            </section>

            <section class="review_content">
                <h2>📝 리뷰 내용</h2>
                <textarea name="review_contents" rows="8" placeholder="최소 10자 이상 입력해주세요." required minlength="10"></textarea>
            </section>

            <section class="review_photo">
                <h2>📷 사진 첨부 (선택 사항)</h2>
                <input type="file" name="photo_upload" id="photo_upload" accept="image/*">
                <label for="photo_upload" class="file_label">파일 선택</label>
                <span id="file_name_display">선택된 파일 없음</span>
            </section>
            
            <div class="action_buttons">
                <button type="submit" class="btn btn--primary btn--lg">리뷰 등록하기</button>
                <button type="button" class="btn btn--secondary btn--lg" onclick="history.back()">취소</button>
            </div>
        </form>
    </main>

    <?php require_once("inc/footer.php"); ?>

    <script>
        document.getElementById('photo_upload').addEventListener('change', function() {
            const fileName = this.files.length > 0 ? this.files[0].name : '선택된 파일 없음';
            document.getElementById('file_name_display').textContent = fileName;
        });

        function validateReview() {
            const content = document.forms['review_form']['review_contents'].value;
            if (content.length < 10) {
                alert('리뷰 내용은 최소 10자 이상 입력해야 합니다.');
                return false;
            }
            if (!document.forms['review_form']['star'].value) {
                alert('별점을 선택해주세요.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>