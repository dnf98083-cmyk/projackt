<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>상품 등록 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .product-add-wrapper {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .page-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }
        .form-textarea {
            height: 150px;
            resize: vertical;
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: #e3405a;
        }
        .file-preview {
            margin-top: 10px;
            max-width: 200px;
            display: none;
            border-radius: 8px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper">
        <div class="mypage-wrapper">
            <?php require_once("inc/mypage_sidebar.php"); ?>

            <section class="mypage-content">
                <div class="product-add-wrapper" style="margin: 0; box-shadow: none; border: none; padding: 0;">
                    <h1 class="page-title" style="text-align: left;">나만의 상품 등록하기</h1>
                    
                    <form action="my-page_product_insert.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">상품명</label>
                            <input type="text" name="content_name" class="form-input" required placeholder="예: 맛있는 김치볶음밥 밀키트">
                        </div>

                        <div class="form-group">
                            <label class="form-label">카테고리</label>
                            <select name="category_large" class="form-select" required>
                                <option value="">카테고리 선택</option>
                                <option value="한식">한식</option>
                                <option value="양식">양식</option>
                                <option value="중식">중식</option>
                        <option value="일식">일식</option>
                        <option value="건강식">건강식</option>
                        <option value="기타">기타</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">판매 가격 (원)</label>
                    <input type="number" name="content_price" class="form-input" required placeholder="예: 12900">
                </div>

                <div class="form-group">
                    <label class="form-label">상품 이미지</label>
                    <input type="file" name="content_img" class="form-input" accept="image/*" required onchange="previewImage(this)">
                    <img id="imgPreview" class="file-preview" alt="미리보기">
                </div>

                <div class="form-group">
                    <label class="form-label">상품 설명</label>
                    <textarea name="content_content" class="form-textarea" placeholder="상품에 대한 자세한 설명을 적어주세요."></textarea>
                </div>

                <button type="submit" class="btn-submit">상품 등록하기</button>
            </form>
                </div>
            </section>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imgPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
