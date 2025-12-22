<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];
$member_name = $_SESSION['member_name'] ?? $_SESSION['name'] ?? $member_id;

// 내 상품 목록 조회
$my_products = db_select("SELECT * FROM contents WHERE registrant_id = ? ORDER BY regist_date DESC", [$member_id]);

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
    <title>나의 상품 관리 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .product-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .product-list-table th, .product-list-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }
        .product-list-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            border-top: 2px solid #333;
        }
        .product-img-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        .btn-review {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            background-color: #fff !important;
            color: #4a90e2 !important;
            border: 1px solid #4a90e2;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            line-height: 1.5;
            text-decoration: none;
            margin-right: 5px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-review:hover {
            background-color: #4a90e2 !important;
            color: #fff !important;
        }
        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            background-color: #fff !important;
            color: #e60000 !important;
            border: 1px solid #e60000;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            line-height: 1.5;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-delete:hover {
            background-color: #e60000 !important;
            color: #fff !important;
        }
        .btn-add-product {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn-add-product:hover {
            background-color: #555;
        }
        .no-products {
            text-align: center;
            padding: 50px 0;
            color: #666;
            border-bottom: 1px solid #eee;
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
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0;">나의 상품 관리</h2>
                    <a href="my-page_product_add.php" class="btn-add-product"><i class="fa-solid fa-plus"></i> 상품 등록하기</a>
                </div>

                <?php if (empty($my_products)): ?>
                    <div class="no-products">
                        <p>등록한 상품이 없습니다.</p>
                    </div>
                <?php else: ?>
                    <table class="product-list-table">
                        <colgroup>
                            <col style="width: 100px;">
                            <col style="width: auto;">
                            <col style="width: 120px;">
                            <col style="width: 120px;">
                            <col style="width: 120px;">
                            <col style="width: 180px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>이미지</th>
                                <th>상품명</th>
                                <th>카테고리</th>
                                <th>가격</th>
                                <th>등록일</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_products as $product): ?>
                                <tr>
                                    <td>
                                        <?php if ($product['content_img']): ?>
                                            <img src="<?= htmlspecialchars($product['content_img']) ?>" alt="상품 이미지" class="product-img-thumb">
                                        <?php else: ?>
                                            <span style="color:#ccc; font-size: 12px;">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: left; padding-left: 20px;">
                                        <div style="font-weight: bold;"><?= htmlspecialchars($product['content_name']) ?></div>
                                        <div style="font-size: 12px; color: #888; margin-top: 4px;"><?= htmlspecialchars($product['content_code']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($product['category_large']) ?></td>
                                    <td><?= number_format($product['content_price']) ?>원</td>
                                    <td><?= date("Y.m.d", strtotime($product['regist_date'])) ?></td>
                                    <td>
                                        <a href="my-page_product_reviews.php?code=<?= $product['content_code'] ?>" class="btn-review">리뷰보기</a>
                                        <button type="button" class="btn-delete" onclick="deleteProduct('<?= $product['content_code'] ?>')">삭제</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>

    <script>
        function deleteProduct(code) {
            if (confirm('정말로 이 상품을 삭제하시겠습니까? 삭제된 상품은 복구할 수 없습니다.')) {
                location.href = 'my-page_product_delete.php?code=' + code;
            }
        }
    </script>
</body>
</html>
