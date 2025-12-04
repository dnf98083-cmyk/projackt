<?php
// ------------------------------
// 캐시 버스팅 헬퍼
// ------------------------------
function bust($relPath) {
  $abs = __DIR__ . '/' . ltrim($relPath, '/');
  $ver = is_file($abs) ? filemtime($abs) : time();
  return $relPath . '?v=' . $ver;
}

// ------------------------------
// 데이터 로드
// ------------------------------
ob_start(); // 출력 버퍼링 시작
require_once __DIR__ . '/inc/db.php';

// 신상품 조회 (직접 등록한 상품만, 최신순 20개)
// registrant_id가 있는 상품만 조회
$new_products = db_select("SELECT * FROM contents WHERE registrant_id IS NOT NULL AND registrant_id != '' ORDER BY content_code DESC LIMIT 20");

// 카테고리별 신상품 (직접 등록한 상품만)
$categories = ['한식', '양식', '중식', '일식', '건강식', '기타'];
$category_new = [];

foreach ($categories as $cat) {
  $cat_products = db_select("SELECT * FROM contents WHERE category_large = ? AND registrant_id IS NOT NULL AND registrant_id != '' ORDER BY content_code DESC LIMIT 8", [$cat]);
  if (!empty($cat_products)) {
    $category_new[$cat] = $cat_products;
  }
}

$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NEW - Meal Kitchen</title>

  <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
  <link rel="icon" href="/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
  
  <style>
    .best-page {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px 16px;
    }
    
    .best-hero {
      text-align: center;
      padding: 40px 20px;
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      color: white;
      border-radius: 12px;
      margin-bottom: 40px;
    }
    
    .best-hero h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
      font-weight: bold;
    }
    
    .best-hero p {
      font-size: 1.1rem;
      opacity: 0.9;
    }
    
    .category-section {
      margin-bottom: 50px;
    }
    
    .category-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #333;
    }
    
    .category-header h2 {
      font-size: 1.8rem;
      margin: 0;
      color: #333;
    }
    
    .category-header .view-all {
      color: #4facfe;
      text-decoration: none;
      font-size: 0.95rem;
    }
    
    .category-header .view-all:hover {
      text-decoration: underline;
    }
    
    .category-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
      overflow-x: auto;
      padding-bottom: 10px;
    }
    
    .category-tab {
      padding: 10px 20px;
      background: white;
      border: 2px solid #e0e0e0;
      border-radius: 25px;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.3s;
      font-weight: 500;
    }
    
    .category-tab.active {
      background: #4facfe;
      color: white;
      border-color: #4facfe;
    }
    
    .category-tab:hover:not(.active) {
      border-color: #4facfe;
      color: #4facfe;
    }
    
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }
    
    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
      cursor: pointer;
      position: relative;
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    
    .product-card .new-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #4facfe;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 5;
    }

    .btn-wish-new {
        position: absolute;
        right: 10px;
        bottom: 10px; /* 이미지 영역 하단 */
        z-index: 10;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
    }
    .btn-wish-new .wish-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.9);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
    .btn-wish-new:hover .wish-circle {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }

    .product-card .info {
      padding: 15px;
    }
    
    .product-card .name {
      font-size: 1rem;
      font-weight: 500;
      margin-bottom: 8px;
      color: #333;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    
    .product-card .price {
      font-size: 1.1rem;
      font-weight: bold;
      color: #333;
    }
    
    @media (max-width: 768px) {
      .best-hero h1 {
        font-size: 1.8rem;
      }
      .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      }
    }
  </style>
</head>
<body class="best-page-body">
  <?php require_once __DIR__ . "/inc/header.php"; ?>

  <main class="best-page">
    <!-- 히어로 섹션 -->
    <section class="best-hero">
      <h1>이달의 신상품</h1>
      <p>매일매일 업데이트되는 새로운 맛을 만나보세요!</p>
    </section>

    <!-- 전체 신상품 (최신순) -->
    <section class="category-section">
      <div class="category-header">
        <h2>🔥 방금 들어온 신상</h2>
      </div>
      <div class="product-grid">
        <?php foreach ($new_products as $product): ?>
        <div class="product-card" 
             onclick="location.href='<?= $BASE ?>/contents_detail.php?content_code=<?= urlencode($product['content_code']) ?>'">
          <div class="new-badge">NEW</div>
          <div style="position: relative; height: 200px;">
            <img src="<?= $BASE ?>/<?= htmlspecialchars($product['content_img']) ?>" 
                 alt="<?= htmlspecialchars($product['content_name']) ?>"
                 onerror="this.src='<?= $BASE ?>/img/no-image.png'">
            
            <!-- 찜하기 버튼 -->
            <button class="btn-wish-new" data-code="<?= $product['content_code'] ?>" onclick="event.stopPropagation(); toggleWishNew(this, '<?= $product['content_code'] ?>')">
                <div class="wish-circle">
                    <img src="<?= $BASE ?>/img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">
                </div>
            </button>
          </div>
          <div class="info">
            <div class="name"><?= htmlspecialchars($product['content_name']) ?></div>
            <div class="price"><?= number_format($product['content_price']) ?>원</div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- 카테고리별 신상품 -->
    <section class="category-section">
      <div class="category-header">
        <h2>카테고리별 신상품</h2>
        <a href="<?= $BASE ?>/product.php?sort=new" class="view-all">전체 상품 보기 ></a>
      </div>

      <div class="category-tabs" id="categoryTabs">
        <?php $first = true; foreach ($category_new as $cat => $products): ?>
        <button class="category-tab <?= $first ? 'active' : '' ?>" 
                data-category="<?= htmlspecialchars($cat) ?>">
          <?= htmlspecialchars($cat) ?>
        </button>
        <?php $first = false; endforeach; ?>
      </div>

      <?php $first = true; foreach ($category_new as $cat => $products): ?>
      <div class="product-grid category-content" 
           data-category="<?= htmlspecialchars($cat) ?>" 
           style="<?= $first ? '' : 'display:none;' ?>">
        <?php foreach ($products as $product): ?>
        <div class="product-card" 
             onclick="location.href='<?= $BASE ?>/contents_detail.php?content_code=<?= urlencode($product['content_code']) ?>'">
          <div class="new-badge">NEW</div>
          <div style="position: relative; height: 200px;">
            <img src="<?= $BASE ?>/<?= htmlspecialchars($product['content_img']) ?>" 
                 alt="<?= htmlspecialchars($product['content_name']) ?>"
                 onerror="this.src='<?= $BASE ?>/img/no-image.png'">
            
            <!-- 찜하기 버튼 -->
            <button class="btn-wish-new" data-code="<?= $product['content_code'] ?>" onclick="event.stopPropagation(); toggleWishNew(this, '<?= $product['content_code'] ?>')">
                <div class="wish-circle">
                    <img src="<?= $BASE ?>/img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">
                </div>
            </button>
          </div>
          <div class="info">
            <div class="name"><?= htmlspecialchars($product['content_name']) ?></div>
            <div class="price"><?= number_format($product['content_price']) ?>원</div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php $first = false; endforeach; ?>
    </section>
  </main>

  <?php require_once __DIR__ . "/inc/footer.php"; ?>

  <script>
    // 카테고리 탭 전환
    document.querySelectorAll('.category-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        const category = tab.dataset.category;
        
        // 모든 탭 비활성화
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        // 클릭한 탭 활성화
        tab.classList.add('active');
        
        // 모든 컨텐츠 숨기기
        document.querySelectorAll('.category-content').forEach(c => c.style.display = 'none');
        // 해당 카테고리 컨텐츠만 표시
        document.querySelector(`.category-content[data-category="${category}"]`).style.display = 'grid';
      });
    });

    // 찜하기 토글 함수
    function toggleWishNew(btn, code) {
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
    <?php if (!empty($_SESSION['member_id'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        const wishButtons = document.querySelectorAll('.btn-wish-new');
        const codes = Array.from(wishButtons).map(btn => btn.dataset.code);
        
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
                        // 동일한 상품이 여러 탭에 있을 수 있으므로 querySelectorAll 사용
                        const btns = document.querySelectorAll(`.btn-wish-new[data-code="${code}"]`);
                        btns.forEach(btn => {
                            btn.querySelector('.wish-icon').src = '<?= $BASE ?>/img/icons/heart2.png';
                            btn.classList.add('is-active');
                        });
                    });
                }
            })
            .catch(err => console.error(err));
        }
    });
    <?php endif; ?>
  </script>
</body>
</html>
