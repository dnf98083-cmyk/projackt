<?php
// ------------------------------
// 캐시 버스팅 헬퍼
// ------------------------------
function bust($relPath) {
  $abs = __DIR__ . '/' . ltrim($relPath, '/');
  $ver = is_file($abs) ? filemtime($abs) : time();
  return $relPath . '?v=' . $ver;
}

function bustIfLocal($path) {
  if (preg_match('~^https?://~i', $path)) return $path;
  return bust($path);
}

require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/session.php';

$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';

// 정렬 기준 처리
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'discount';
$order_sql = 'ORDER BY discount_rate DESC'; // 기본값: 할인율 높은 순

switch ($sort) {
    case 'price_asc': // 낮은 가격순
        $order_sql = 'ORDER BY content_price ASC';
        break;
    case 'price_desc': // 높은 가격순
        $order_sql = 'ORDER BY content_price DESC';
        break;
    case 'review': // 리뷰 많은 순
        $order_sql = 'ORDER BY review_count DESC, avg_star DESC';
        break;
    case 'discount': // 할인율 높은 순 (기본)
    default:
        $order_sql = 'ORDER BY discount_rate DESC';
        $sort = 'discount';
        break;
}

// 할인 상품 조회 (할인율이 0보다 큰 상품)
$query = "SELECT *, 
            (SELECT COUNT(*) FROM review WHERE review.content_code = contents.content_code) as review_count,
            (SELECT AVG(star) FROM review WHERE review.content_code = contents.content_code) as avg_star
          FROM contents 
          WHERE discount_rate > 0 
          {$order_sql}";
$sale_products = db_select($query);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SALE - Meal Kitchen</title>

  <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
  
  <style>
    .sale-page {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px 16px;
    }
    
    .sale-hero {
      text-align: center;
      padding: 40px 20px;
      background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
      color: #d6336c;
      border-radius: 12px;
      margin-bottom: 40px;
    }
    
    .sale-hero h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
      font-weight: bold;
      color: #c2255c;
    }
    
    .sale-hero p {
      font-size: 1.1rem;
      color: #a61e4d;
    }

    .sort-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }

    .sort-select {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 0.95rem;
      color: #333;
      background-color: white;
      cursor: pointer;
      outline: none;
    }

    .sort-select:focus {
      border-color: #e60000;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.2s, box-shadow 0.2s;
      border: 1px solid #eee;
      position: relative;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .img-wrapper {
      width: 100%;
      aspect-ratio: 1;
      overflow: hidden;
      position: relative;
    }

    .img-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s;
    }

    .product-card:hover .img-wrapper img {
      transform: scale(1.05);
    }

    .badge-sale {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #e60000;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: bold;
      font-size: 0.9rem;
      z-index: 10;
    }

    .info-wrapper {
      padding: 15px;
    }

    .p-name {
      font-size: 1rem;
      font-weight: 500;
      color: #333;
      margin-bottom: 8px;
      line-height: 1.4;
      height: 2.8em;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .p-price-box {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .p-original {
      font-size: 0.9rem;
      color: #999;
      text-decoration: line-through;
    }

    .p-final {
      font-size: 1.1rem;
      font-weight: bold;
      color: #333;
    }
    
    .p-rate {
      color: #e60000;
      font-weight: bold;
      margin-right: 5px;
    }

    @media (max-width: 1024px) {
      .product-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
      .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
      .product-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <?php require_once __DIR__ . "/inc/header.php"; ?>

  <main class="sale-page">
    <section class="sale-hero">
      <h1>🔥 TIME SALE</h1>
      <p>지금 놓치면 후회하는 특가 상품을 만나보세요!</p>
    </section>

    <div class="sort-bar">
      <select class="sort-select" onchange="location.href='?sort=' + this.value">
        <option value="discount" <?= $sort === 'discount' ? 'selected' : '' ?>>할인율 높은순</option>
        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>낮은 가격순</option>
        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>높은 가격순</option>
        <option value="review" <?= $sort === 'review' ? 'selected' : '' ?>>리뷰 많은순</option>
      </select>
    </div>

    <?php if (empty($sale_products)): ?>
      <div style="text-align: center; padding: 100px 0; color: #999; font-size: 1.2rem;">
        <i class="fas fa-exclamation-circle" style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
        현재 진행 중인 할인 상품이 없습니다.
      </div>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($sale_products as $p): 
          $img = $p['content_img'] ? bustIfLocal($p['content_img']) : bust('img/no_image.png');
          $name = htmlspecialchars($p['content_name']);
          $price = (int)$p['content_price']; // 판매가
          $cost = (int)$p['content_cost'];   // 정가
          $rate = (int)$p['discount_rate'];
          $code = $p['content_code'];
        ?>
        <div class="product-card" onclick="location.href='<?= $BASE ?>/contents_detail.php?content_code=<?= $code ?>'" style="cursor: pointer;">
          <div class="img-wrapper">
            <span class="badge-sale"><?= $rate ?>% OFF</span>
            <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy">
            
            <!-- Wishlist Button -->
            <button type="button" class="btn-wish-product" data-code="<?= $code ?>" style="position: absolute; right: 8px; bottom: 8px; z-index: 10; background: transparent; border: none; cursor: pointer; padding: 0; width: 36px; height: 36px;" onclick="event.stopPropagation(); toggleWishProduct(this, '<?= $code ?>');">
                <div class="wish-circle" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s;">
                    <img src="<?= $BASE ?>/img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">
                </div>
            </button>
          </div>
          <div class="info-wrapper">
            <div class="p-name"><?= $name ?></div>
            <div class="p-price-box">
              <?php if ($cost > $price): ?>
              <span class="p-original"><?= number_format($cost) ?>원</span>
              <?php endif; ?>
              <div style="display: flex; align-items: center;">
                <span class="p-rate"><?= $rate ?>%</span>
                <span class="p-final"><?= number_format($price) ?>원</span>
              </div>
            </div>
            
            <!-- Review Info -->
            <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #999; margin-top: 8px;">
                <i class="fas fa-star" style="color: #ffc107; font-size: 11px;"></i>
                <span style="color: #333; font-weight: 600;"><?= number_format((float)$p['avg_star'], 1) ?></span>
                <span style="color: #999;">(<?= number_format((int)$p['review_count']) ?>)</span>
                <?php if (isset($p['content_sales']) && $p['content_sales'] > 0): ?>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #666;">구매 <?= number_format($p['content_sales']) ?></span>
                <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php require_once __DIR__ . "/inc/fast_move.php"; ?>
  <?php require_once __DIR__ . "/inc/footer.php"; ?>

  <script>
    // 찜 토글
    function toggleWishProduct(btn, code) {
        <?php if (!empty($_SESSION['member_id'])): ?>
        const form = new FormData();
        form.append('content_code', code);
        
        fetch('<?= $BASE ?>/wishlist_toggle.php', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    const icon = btn.querySelector('.wish-icon');
                    if (data.toggled === 'added') {
                        icon.src = '<?= $BASE ?>/img/icons/heart2.png';
                        btn.classList.add('is-active');
                    } else {
                        icon.src = '<?= $BASE ?>/img/icons/heart1.png';
                        btn.classList.remove('is-active');
                    }
                } else if (data.error === 'NOT_AUTH') {
                    alert('로그인이 필요합니다.');
                    location.href = '<?= $BASE ?>/login.php';
                }
            })
            .catch(err => console.error(err));
        <?php else: ?>
        alert('로그인이 필요합니다.');
        location.href = '<?= $BASE ?>/login.php';
        <?php endif; ?>
    }
    
    // 페이지 로드 시 찜한 상품 확인
    function checkWishlistStatus() {
        <?php if (!empty($_SESSION['member_id'])): ?>
        // 아직 체크하지 않은 버튼만 선택
        const wishButtons = document.querySelectorAll('.btn-wish-product:not(.checked-wish)');
        if (wishButtons.length === 0) return;

        const codes = Array.from(wishButtons).map(btn => {
            btn.classList.add('checked-wish'); // 중복 체크 방지 마킹
            return btn.dataset.code;
        });
        
        if (codes.length > 0) {
            fetch('<?= $BASE ?>/get_wishlist_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ codes: codes })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok && data.wishlisted) {
                    data.wishlisted.forEach(code => {
                        const btns = document.querySelectorAll(`.btn-wish-product[data-code="${code}"]`);
                        btns.forEach(btn => {
                            btn.querySelector('.wish-icon').src = '<?= $BASE ?>/img/icons/heart2.png';
                            btn.classList.add('is-active');
                        });
                    });
                }
            })
            .catch(err => console.error(err));
        }
        <?php endif; ?>
    }

    document.addEventListener('DOMContentLoaded', checkWishlistStatus);
  </script>
</body>
</html>