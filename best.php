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

// 베스트 상품 조회 (판매량 기준 상위 5개)
function column_exists($col) {
  $res = db_select("SELECT COUNT(*) as cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'contents' AND COLUMN_NAME = ?", [$col]);
  return !empty($res) && isset($res[0]['cnt']) && intval($res[0]['cnt']) > 0;
}

$use_sales = column_exists('content_sales');
$order = $use_sales ? 'content_sales DESC' : 'content_code DESC';

$best_products = db_select("SELECT * FROM contents ORDER BY {$order} LIMIT 5");

// 카테고리별 베스트 상품
$categories = ['한식', '양식', '중식', '일식', '건강식', '기타'];
$category_best = [];

foreach ($categories as $cat) {
  $cat_products = db_select("SELECT * FROM contents WHERE category_large = ? ORDER BY {$order} LIMIT 6", [$cat]);
  if (!empty($cat_products)) {
    $category_best[$cat] = $cat_products;
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
  <title>BEST - Meal Kitchen</title>

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
      background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
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
    
    .best-ranking {
      background: white;
      border-radius: 12px;
      padding: 30px;
      margin-bottom: 40px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .best-ranking h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .best-ranking h2::before {
      content: '🏆';
      font-size: 2rem;
    }
    
    .ranking-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .ranking-item {
      display: flex;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #eee;
      transition: background 0.2s;
      cursor: pointer;
    }
    
    .ranking-item:hover {
      background: #f8f9fa;
    }
    
    .ranking-item:last-child {
      border-bottom: none;
    }
    
    .rank-number {
      font-size: 1.5rem;
      font-weight: bold;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin-right: 15px;
      flex-shrink: 0;
    }
    
    .rank-number.top-1 {
      background: linear-gradient(135deg, #FFD700, #FFA500);
      color: white;
    }
    
    .rank-number.top-2 {
      background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
      color: white;
    }
    
    .rank-number.top-3 {
      background: linear-gradient(135deg, #CD7F32, #B8860B);
      color: white;
    }
    
    .rank-number.normal {
      background: #f0f0f0;
      color: #666;
    }
    
    .ranking-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
      flex-shrink: 0;
    }
    
    .ranking-info {
      flex: 1;
    }
    
    .ranking-info h3 {
      font-size: 1.1rem;
      margin: 0 0 5px 0;
      color: #333;
    }
    
    .ranking-info .category {
      display: inline-block;
      padding: 2px 8px;
      background: #e3f2fd;
      color: #1976d2;
      border-radius: 4px;
      font-size: 0.85rem;
      margin-bottom: 5px;
    }
    
    .ranking-price {
      text-align: right;
      flex-shrink: 0;
    }
    
    .ranking-price .price {
      font-size: 1.2rem;
      font-weight: bold;
      color: #e91e63;
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
      color: #ff6b6b;
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
      background: #ff6b6b;
      color: white;
      border-color: #ff6b6b;
    }
    
    .category-tab:hover:not(.active) {
      border-color: #ff6b6b;
      color: #ff6b6b;
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
      color: #e91e63;
    }
    
    @media (max-width: 768px) {
      .best-hero h1 {
        font-size: 1.8rem;
      }
      
      .ranking-item {
        flex-wrap: wrap;
      }
      
      .ranking-price {
        width: 100%;
        text-align: left;
        margin-top: 10px;
        padding-left: 55px;
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
      <h1>지금 최고의 인기상품!</h1>
      <p>고객님들이 가장 많이 찾는 베스트 상품을 만나보세요</p>
    </section>

    <!-- 베스트 랭킹 TOP 5 -->
    <section class="best-ranking">
      <h2>실시간 베스트 TOP 5</h2>
      <ol class="ranking-list">
        <?php foreach ($best_products as $idx => $product): 
          $rank = $idx + 1;
          $rankClass = $rank <= 3 ? "top-{$rank}" : 'normal';
        ?>
        <li class="ranking-item" onclick="location.href='<?= $BASE ?>/contents_detail.php?content_code=<?= urlencode($product['content_code']) ?>'">
          <div class="rank-number <?= $rankClass ?>"><?= $rank ?></div>
          <img src="<?= $BASE ?>/<?= htmlspecialchars($product['content_img']) ?>" 
               alt="<?= htmlspecialchars($product['content_name']) ?>" 
               class="ranking-image"
               onerror="this.src='<?= $BASE ?>/img/no-image.png'">
          <div class="ranking-info">
            <span class="category"><?= htmlspecialchars($product['category_large'] ?? '기타') ?></span>
            <h3><?= htmlspecialchars($product['content_name']) ?></h3>
            <div class="sales-count" style="font-size: 0.9rem; color: #666; margin-top: 4px;">
                <i class="fas fa-fire" style="color: #ff6b6b; margin-right: 4px;"></i>
                <?= number_format($product['content_sales'] ?? 0) ?>개 판매
            </div>
          </div>
          <div class="ranking-price">
            <div class="price"><?= number_format($product['content_price']) ?>원</div>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
    </section>

    <!-- 카테고리별 베스트 -->
    <section class="category-section">
      <div class="category-header">
        <h2>FOOD 카테고리 베스트 상품</h2>
        <a href="<?= $BASE ?>/product.php" class="view-all">전체 상품 보기 ></a>
      </div>

      <div class="category-tabs" id="categoryTabs">
        <?php $first = true; foreach ($category_best as $cat => $products): ?>
        <button class="category-tab <?= $first ? 'active' : '' ?>" 
                data-category="<?= htmlspecialchars($cat) ?>">
          <?= htmlspecialchars($cat) ?>
        </button>
        <?php $first = false; endforeach; ?>
      </div>

      <?php $first = true; foreach ($category_best as $cat => $products): ?>
      <div class="product-grid category-content" 
           data-category="<?= htmlspecialchars($cat) ?>" 
           style="<?= $first ? '' : 'display:none;' ?>">
        <?php foreach ($products as $product): ?>
        <div class="product-card" 
             onclick="location.href='<?= $BASE ?>/contents_detail.php?content_code=<?= urlencode($product['content_code']) ?>'">
          <img src="<?= $BASE ?>/<?= htmlspecialchars($product['content_img']) ?>" 
               alt="<?= htmlspecialchars($product['content_name']) ?>"
               onerror="this.src='<?= $BASE ?>/img/no-image.png'">
          <div class="info">
            <div class="name"><?= htmlspecialchars($product['content_name']) ?></div>
            <div class="price"><?= number_format($product['content_price']) ?>원</div>
            <div class="sales-count" style="font-size: 0.85rem; color: #888; margin-top: 5px;">
                <?= number_format($product['content_sales'] ?? 0) ?>개 판매
            </div>
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
  </script>
</body>
</html>
