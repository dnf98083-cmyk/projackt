<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];

// 내 리뷰 목록 조회
$query = "SELECT r.*, c.content_name, c.content_img 
          FROM review r 
          LEFT JOIN contents c ON r.content_code = c.content_code 
          WHERE r.writer_id = ? 
          ORDER BY r.review_id DESC";
$my_reviews = db_select($query, [$member_id]);

function bust($relPath) {
    $abs = __DIR__ . '/' . ltrim($relPath, '/');
    $ver = is_file($abs) ? filemtime($abs) : time();
    return $relPath . '?v=' . $ver;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>나의 리뷰 관리 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .review-list {
            width: 100%;
            margin-top: 20px;
        }
        .review-item {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .review-item:last-child {
            border-bottom: none;
        }
        .review-product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
            margin-right: 20px;
        }
        .review-content {
            flex: 1;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .product-name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            text-decoration: none;
        }
        .product-name:hover {
            text-decoration: underline;
        }
        .review-date {
            color: #888;
            font-size: 13px;
        }
        .star-rating {
            color: #f1c40f;
            margin-bottom: 10px;
        }
        .review-text {
            color: #555;
            line-height: 1.5;
            white-space: pre-wrap;
        }
        .review-photo {
            margin-top: 10px;
            max-width: 150px;
            max-height: 150px;
            border-radius: 4px;
            cursor: pointer;
        }
        .no-reviews {
            text-align: center;
            padding: 80px 0;
            color: #666;
            border-bottom: 1px solid #eee;
        }
        .no-reviews i {
            font-size: 40px;
            color: #ddd;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper">
        <div class="mypage-wrapper">
            <?php require_once("inc/mypage_sidebar.php"); ?>

            <section class="mypage-content">
                <div class="page-header" style="border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px;">
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0;">나의 리뷰 관리</h2>
                </div>

                <?php if (empty($my_reviews)): ?>
                    <div class="no-reviews">
                        <i class="fa-regular fa-comment-dots"></i>
                        <p>쓴 리뷰글이 없습니다.</p>
                    </div>
                <?php else: ?>
                    <div class="review-list">
                        <?php foreach ($my_reviews as $review): ?>
                            <div class="review-item">
                                <a href="contents_detail.php?code=<?= $review['content_code'] ?>">
                                    <?php if ($review['content_img']): ?>
                                        <img src="<?= htmlspecialchars($review['content_img']) ?>" alt="상품 이미지" class="review-product-img">
                                    <?php else: ?>
                                        <div class="review-product-img" style="background:#f5f5f5; display:flex; align-items:center; justify-content:center; color:#ccc;">No Image</div>
                                    <?php endif; ?>
                                </a>
                                <div class="review-content">
                                    <div class="review-header">
                                        <a href="contents_detail.php?code=<?= $review['content_code'] ?>" class="product-name">
                                            <?= htmlspecialchars($review['content_name']) ?>
                                        </a>
                                        <!-- 날짜 정보가 review_id에 포함되어 있다면 파싱, 아니면 DB에 날짜 컬럼이 있는지 확인 필요. 
                                             review_insert.php를 보면 review_id = date("Ymd") . ... 형식이므로 앞 8자리를 날짜로 사용 가능 -->
                                        <?php 
                                            $date_str = substr($review['review_id'], 0, 4) . '.' . substr($review['review_id'], 4, 2) . '.' . substr($review['review_id'], 6, 2);
                                        ?>
                                        <span class="review-date"><?= $date_str ?></span>
                                    </div>
                                    
                                    <div class="star-rating">
                                        <?php
                                        $star_score = (int)$review['star'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $star_score) {
                                                echo '<i class="fa-solid fa-star"></i>';
                                            } else {
                                                echo '<i class="fa-regular fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>

                                    <div class="review-text">
                                        <?= nl2br(htmlspecialchars($review['review_contents'])) ?>
                                    </div>

                                    <?php if (!empty($review['photo'])): ?>
                                        <img src="<?= htmlspecialchars($review['photo']) ?>" alt="리뷰 사진" class="review-photo" onclick="window.open(this.src)">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>
</body>
</html>
