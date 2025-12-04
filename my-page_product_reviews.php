<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$content_code = $_GET['code'] ?? '';

if (!$content_code) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 상품 정보 조회
$product = db_select("SELECT * FROM contents WHERE content_code = ?", [$content_code]);

if (empty($product)) {
    echo "<script>alert('상품 정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}

$product = $product[0];

// 리뷰 조회
$reviews = db_select("SELECT * FROM review WHERE content_code = ? ORDER BY review_regdate DESC", [$content_code]);

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
    <title>상품 리뷰 관리 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .review-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .review-list-table th, .review-list-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }
        .review-list-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            border-top: 2px solid #333;
        }
        .review-img-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
            cursor: pointer;
        }
        .star-rating {
            color: #f4c150;
            font-size: 14px;
        }
        .no-reviews {
            text-align: center;
            padding: 50px 0;
            color: #666;
            border-bottom: 1px solid #eee;
        }
        .product-info-summary {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .product-info-summary img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .product-info-summary .info {
            flex: 1;
        }
        .product-info-summary .info h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .product-info-summary .info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .btn-back {
            display: inline-block;
            padding: 8px 16px;
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-back:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper">
        <div class="mypage-wrapper">
            <?php require_once("inc/mypage_sidebar.php"); ?>

            <section class="mypage-content">
                <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0;">상품 리뷰 관리</h2>
                    <a href="my-page_product_list.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> 목록으로</a>
                </div>

                <div class="product-info-summary">
                    <?php if ($product['content_img']): ?>
                        <img src="<?= htmlspecialchars($product['content_img']) ?>" alt="상품 이미지">
                    <?php else: ?>
                        <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px;">No Image</div>
                    <?php endif; ?>
                    <div class="info">
                        <h3><?= htmlspecialchars($product['content_name']) ?></h3>
                        <p>상품코드: <?= htmlspecialchars($product['content_code']) ?> | 가격: <?= number_format($product['content_price']) ?>원</p>
                    </div>
                </div>

                <?php if (empty($reviews)): ?>
                    <div class="no-reviews">
                        <p>등록된 리뷰가 없습니다.</p>
                    </div>
                <?php else: ?>
                    <table class="review-list-table">
                        <colgroup>
                            <col style="width: 80px;">
                            <col style="width: auto;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 120px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>이미지</th>
                                <th>내용</th>
                                <th>별점</th>
                                <th>작성자</th>
                                <th>작성일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td>
                                        <?php if ($review['photo']): ?>
                                            <img src="<?= htmlspecialchars($review['photo']) ?>" alt="리뷰 이미지" class="review-img-thumb" onclick="window.open(this.src)">
                                        <?php else: ?>
                                            <span style="color:#ccc; font-size: 12px;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: left; padding: 15px;">
                                        <?= nl2br(htmlspecialchars($review['review_contents'])) ?>
                                    </td>
                                    <td>
                                        <div class="star-rating">
                                            <?php
                                            $star = intval($review['star']);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $star) echo '<i class="fa-solid fa-star"></i>';
                                                else echo '<i class="fa-regular fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($review['writer_id']) ?></td>
                                    <td><?= date("Y.m.d", strtotime($review['review_regdate'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>
</body>
</html>
