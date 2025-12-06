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

// ------------------------------
// 데이터 로드
// ------------------------------
// 정렬 파라미터 처리 (server-side sorting)
$sort = $_GET['sort'] ?? 'sales'; // sales | price_h | price_l | recommend

require_once __DIR__ . '/inc/db.php';

// 안전하게 ORDER BY 결정 — DB에 컬럼이 있는지 확인 후 선택
  function column_exists($col) {
    $res = db_select("SELECT COUNT(*) as cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'contents' AND COLUMN_NAME = ?", [$col]);
    return !empty($res) && isset($res[0]['cnt']) && intval($res[0]['cnt']) > 0;
  }

  // 우선 가능한 정렬 컬럼을 결정
  $use_sales   = column_exists('content_sales');
  $use_price   = column_exists('content_price');
  $use_rating  = column_exists('content_rating');

  switch ($sort) {
    case 'price_h':
      if ($use_price) $order = 'content_price DESC';
      else $order = ($use_sales ? 'content_sales DESC' : 'content_code DESC');
      break;
    case 'price_l':
      if ($use_price) $order = 'content_price ASC';
      else $order = ($use_sales ? 'content_sales DESC' : 'content_code DESC');
      break;
    case 'recommend':
      // 추천순 -> 리뷰 많은 순
      $order = '(SELECT COUNT(*) FROM review WHERE review.content_code = contents.content_code) DESC';
      break;
    case 'sales':
    default:
      if ($use_sales) $order = 'content_sales DESC';
      elseif ($use_price) $order = 'content_price DESC';
      else $order = 'content_code DESC';
      $sort = 'sales';
      break;
  }

  $query = "SELECT contents.*, 
            (SELECT COUNT(*) FROM review WHERE review.content_code = contents.content_code) as review_count,
            (SELECT AVG(star) FROM review WHERE review.content_code = contents.content_code) as avg_star
            FROM contents ORDER BY " . $order . " LIMIT 64";
  $products = db_select($query);
  
  // BEST 3 상품 조회 (판매량 기준)
  $best_order = $use_sales ? 'content_sales DESC' : 'content_code DESC';
  $best_products = db_select("
      SELECT c.*, 
      (SELECT COUNT(*) FROM review r WHERE r.content_code = c.content_code) as review_count,
      (SELECT AVG(star) FROM review r WHERE r.content_code = c.content_code) as avg_star
      FROM contents c 
      ORDER BY {$best_order} 
      LIMIT 3
  ");

  // 최신 리뷰 조회 (커뮤니티)
  $latest_reviews = db_select("
      SELECT r.*, c.content_name, c.content_img,
      (SELECT COUNT(*) FROM review_comments rc WHERE rc.review_id = r.review_id) as comment_count
      FROM review r 
      JOIN contents c ON r.content_code = c.content_code 
      ORDER BY r.review_regdate DESC 
      LIMIT 4
  ");

  // 최신 공지사항 조회
  $latest_notices = db_select("SELECT * FROM notice ORDER BY reg_date DESC LIMIT 5");

if (!isset($products) || !is_array($products)) $products = [];
if (!isset($best_products) || !is_array($best_products)) $best_products = [];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Meal Kitchen</title>

  <!-- 스타일 (자동 캐시버스팅) -->
  <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
  <link rel="icon" href="/favicon.ico" type="image/x-icon" />

  <!-- Font Awesome 아이콘 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body class="home">
  <?php require_once __DIR__ . "/inc/header.php"; ?>

  <main class="main_wrapper">
    <!-- =========================
         배너 + 사이드 광고 (2열)
         ========================= -->
    <?php
      // 메인 배너 이미지 스캔 (img/banner 폴더)
      $bannerDir = __DIR__ . '/img/banner';
      $banners = [];
      if (is_dir($bannerDir)) {
          // jpg, png, gif, avif, webp 등 지원
          $files = glob($bannerDir . '/*.{jpg,jpeg,png,gif,avif,webp}', GLOB_BRACE);
          if ($files) {
              foreach ($files as $f) {
                  $banners[] = 'img/banner/' . basename($f);
              }
          }
      }
      // 만약 배너가 하나도 없으면 기본값 (혹은 빈 배열)
      if (empty($banners)) {
          // Fallback
          $banners = ['img/banner/banner_01.jpg']; 
      }

      // 사이드 광고 이미지 스캔 (img/ad 폴더)
      $sideAdDir = __DIR__ . '/img/ad';
      $sideAds = [];
      if (is_dir($sideAdDir)) {
          $files = glob($sideAdDir . '/*.{jpg,jpeg,png,gif,avif,webp}', GLOB_BRACE);
          if ($files) {
              foreach ($files as $f) {
                  $sideAds[] = 'img/ad/' . basename($f);
              }
          }
      }
      // 만약 광고가 없으면 기본 이미지 하나라도 (없으면 빈 배열)
      if (empty($sideAds) && is_file(__DIR__ . '/img/ad_paladin.jpg')) {
          $sideAds[] = 'img/ad_paladin.jpg';
      }
    ?>
    <section class="mk-hero-row">
      <!-- 메인 배너 -->
      <div class="banner_slide" id="hero" aria-roledescription="carousel">
        <div class="banner_track">
          <?php foreach ($banners as $i => $p): ?>
            <img src="<?= bust($p) ?>" alt="메인 배너 <?= $i+1 ?>" />
          <?php endforeach; ?>
        </div>
        <?php if (count($banners) > 1): ?>
          <div class="banner_dots" aria-hidden="true">
            <?php for ($i=0; $i<count($banners); $i++): ?>
              <button aria-label="배너 <?= $i+1 ?>" <?= $i===0 ? 'class="is-active"' : '' ?>></button>
            <?php endfor; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- 사이드 광고 (자동 슬라이드) -->
      <aside class="side_ad_wrapper" id="side_ad_slider">
        <?php if (!empty($sideAds)): ?>
          <div class="side_ad_track">
            <?php foreach ($sideAds as $ad): ?>
              <a href="#" class="side_ad_item">
                <img src="<?= bust($ad) ?>" alt="광고" loading="lazy" />
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <!-- 광고 이미지가 없을 경우 빈 공간 유지 or 숨김 -->
          <div style="width:100%; height:100%; background:#f5f5f5; display:flex; align-items:center; justify-content:center; color:#ccc;">
            광고 준비중
          </div>
        <?php endif; ?>
      </aside>
    </section>

    <!-- =========================
         상품 추천 (슬라이드)
         ========================= -->
    <div class="recommend_main_wrapper" style="position: relative; max-width: 1200px; margin: 0 auto; padding: 20px;">
      <div class="recommend_main_header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <span class="recomend_main_title">상품추천</span>
        <div class="sort_nav_wrapper" role="tablist" aria-label="정렬">
          <a href="index.php?sort=sales" class="sort_nav01 <?= $sort==='sales' ? 'is-active' : '' ?>"><span>판매순</span></a>
          <a href="index.php?sort=price_h" class="sort_nav02 <?= $sort==='price_h' ? 'is-active' : '' ?>"><span>높은가격순</span></a>
          <a href="index.php?sort=price_l" class="sort_nav03 <?= $sort==='price_l' ? 'is-active' : '' ?>"><span>낮은가격순</span></a>
          <a href="index.php?sort=recommend" class="sort_nav04 <?= $sort==='recommend' ? 'is-active' : '' ?>"><span>리뷰순</span></a>
        </div>
      </div>

      <!-- 슬라이드 컨테이너 -->
      <div class="product-slide-container" style="position: relative; overflow: hidden; padding: 0 50px;">
        <button class="slide-btn slide-prev" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); z-index: 100; background: white; border: 2px solid #e60000; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.3s;" aria-label="이전">
          <i class="fas fa-chevron-left" style="color: #e60000; font-size: 16px;"></i>
        </button>
        <button class="slide-btn slide-next" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); z-index: 100; background: white; border: 2px solid #e60000; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.3s;" aria-label="다음">
          <i class="fas fa-chevron-right" style="color: #e60000; font-size: 16px;"></i>
        </button>

        <div class="product-slide-wrapper" style="overflow: hidden;">
          <div class="product-slide-track" style="display: flex; transition: transform 0.4s ease;">
            <?php 
            // 8개씩 페이지로 나누기
            $pages = array_chunk($products, 8);
            foreach ($pages as $pageIdx => $pageProducts): 
            ?>
            <div class="product-slide-page" style="min-width: 100%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; padding: 0 10px;">
              <?php foreach ($pageProducts as $r):
                $img   = isset($r['content_img']) ? trim($r['content_img']) : '';
                $imgSrc= $img ? bustIfLocal($img) : bust('img/no_image.png');
                $name  = htmlspecialchars($r['content_name'] ?? '');
                $price = number_format((int)($r['content_price'] ?? 0));
                $rate  = (int)($r['discount_rate'] ?? 0);
                $code  = htmlspecialchars($r['content_code'] ?? '');
                $review_count = (int)($r['review_count'] ?? 0);
                $avg_star = (float)($r['avg_star'] ?? 0);
                $sales = (int)($r['content_sales'] ?? 0);
              ?>
              <div class="product-card-slide" style="cursor: pointer; display: flex; flex-direction: column; background: white; border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;">
                <div style="width: 100%; aspect-ratio: 1; overflow: hidden; position: relative;" onclick="location.href='contents_detail.php?content_code=<?= $code ?>'">
                  <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= $name ?: '상품 이미지' ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" />
                  
                  <!-- 찜하기 버튼 -->
                  <button class="btn-wish-slide" data-code="<?= $code ?>" style="position: absolute; right: 8px; bottom: 8px; z-index: 10; background: transparent; border: none; cursor: pointer; padding: 0; width: 36px; height: 36px;" onclick="event.stopPropagation(); toggleWishSlide(this, '<?= $code ?>')">
                    <div class="wish-circle" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s;">
                      <img src="<?= $BASE ?? '' ?>/img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">
                    </div>
                  </button>
                </div>
                <div style="padding: 12px; display: flex; flex-direction: column; flex: 1;" onclick="location.href='contents_detail.php?content_code=<?= $code ?>'">
                  <div style="margin-bottom: 12px;">
                    <span style="color: #333; font-size: 15px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= $name ?></span>
                  </div>
                  
                  <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: flex-end;">
                    <div style="display: flex; flex-direction: column;">
                        <?php if ($rate > 0): ?>
                        <span style="color: #e60000; font-size: 14px; font-weight: 700; margin-bottom: 2px;"><?= $rate ?>%</span>
                        <?php endif; ?>
                        <span style="font-size: 18px; font-weight: 700; color: #333; letter-spacing: -0.5px;"><?= $price ?>원</span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #999; padding-bottom: 3px;">
                        <i class="fas fa-star" style="color: #e60000; font-size: 11px;"></i>
                        <span style="color: #333; font-weight: 600;"><?= number_format($avg_star, 1) ?></span>
                        <span style="color: #999;">(<?= number_format($review_count) ?>)</span>
                        <?php if ($sales > 0): ?>
                        <span style="color: #ddd;">|</span>
                        <span style="color: #666;">구매 <?= number_format($sales) ?></span>
                        <?php endif; ?>
                    </div>
                  </div>

                  <?php if (!empty($r['deliv_today']) && $r['deliv_today'] === 'Y'): ?>
                  <div style="font-size: 12px; color: #ff6b6b; margin-top: 6px;">
                    <i class="fas fa-bolt" aria-hidden="true"></i> 오늘 출발
                  </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- 페이지 인디케이터 -->
      <div class="slide-indicators" style="display: flex; justify-content: center; gap: 8px; margin-top: 20px;">
        <?php for ($i = 0; $i < count($pages); $i++): ?>
        <button class="slide-indicator <?= $i === 0 ? 'active' : '' ?>" data-page="<?= $i ?>" style="width: 10px; height: 10px; border-radius: 50%; border: none; background: <?= $i === 0 ? '#e60000' : '#ddd' ?>; cursor: pointer; transition: all 0.3s;"></button>
        <?php endfor; ?>
      </div>
      
      <style>
        .slide-btn:hover {
          background: #ff6b6b;
          transform: translateY(-50%) scale(1.1);
        }
        .slide-btn:hover i {
          color: white;
        }
        .slide-indicator.active {
          background: #ff6b6b !important;
          width: 24px !important;
          border-radius: 5px !important;
        }
        .product-card-slide:hover {
          transform: translateY(-4px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-wish-slide .wish-circle:hover {
          transform: scale(1.1);
          box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }
        @media (max-width: 768px) {
          .product-slide-page {
            grid-template-columns: repeat(2, 1fr) !important;
          }
        }
        @media (max-width: 1024px) and (min-width: 769px) {
          .product-slide-page {
            grid-template-columns: repeat(3, 1fr) !important;
          }
        }
      </style>
      
      <script>
      (function() {
        const track = document.querySelector('.product-slide-track');
        const prevBtn = document.querySelector('.slide-prev');
        const nextBtn = document.querySelector('.slide-next');
        const indicators = document.querySelectorAll('.slide-indicator');
        const totalPages = <?= count($pages) ?>;
        let currentPage = 0;
        
        function updateSlide() {
          track.style.transform = `translateX(-${currentPage * 100}%)`;
          indicators.forEach((ind, idx) => {
            ind.classList.toggle('active', idx === currentPage);
            ind.style.background = idx === currentPage ? '#e60000' : '#ddd';
            ind.style.width = idx === currentPage ? '24px' : '10px';
            ind.style.borderRadius = idx === currentPage ? '5px' : '50%';
          });
          prevBtn.style.opacity = currentPage === 0 ? '0.5' : '1';
          nextBtn.style.opacity = currentPage === totalPages - 1 ? '0.5' : '1';
        }
        
        prevBtn.addEventListener('click', () => {
          if (currentPage > 0) {
            currentPage--;
            updateSlide();
          }
        });
        
        nextBtn.addEventListener('click', () => {
          if (currentPage < totalPages - 1) {
            currentPage++;
            updateSlide();
          }
        });
        
        indicators.forEach((ind, idx) => {
          ind.addEventListener('click', () => {
            currentPage = idx;
            updateSlide();
          });
        });
        
        updateSlide();
      })();
      
      // 찜하기 토글 함수
      function toggleWishSlide(btn, code) {
        <?php if (!empty($_SESSION['member_id'])): ?>
        const form = new FormData();
        form.append('content_code', code);
        
        fetch('<?= $BASE ?? '' ?>/wishlist_toggle.php', { method: 'POST', body: form })
          .then(r => r.json())
          .then(data => {
            if (data.ok) {
              const icon = btn.querySelector('.wish-icon');
              if (data.toggled === 'added') {
                icon.src = '<?= $BASE ?? '' ?>/img/icons/heart2.png';
                btn.classList.add('is-active');
              } else {
                icon.src = '<?= $BASE ?? '' ?>/img/icons/heart1.png';
                btn.classList.remove('is-active');
              }
            } else if (data.error === 'NOT_AUTH') {
              alert('로그인이 필요합니다.');
              location.href = '<?= $BASE ?? '' ?>/login.php';
            }
          })
          .catch(err => console.error(err));
        <?php else: ?>
        alert('로그인이 필요합니다.');
        location.href = '<?= $BASE ?? '' ?>/login.php';
        <?php endif; ?>
      }
      
      // 페이지 로드 시 찜한 상품 확인
      <?php if (!empty($_SESSION['member_id'])): ?>
      document.addEventListener('DOMContentLoaded', () => {
        const wishButtons = document.querySelectorAll('.btn-wish-slide');
        const codes = Array.from(wishButtons).map(btn => btn.dataset.code);
        
        if (codes.length > 0) {
          // 찜한 상품 목록 가져오기
          fetch('<?= $BASE ?? '' ?>/get_wishlist_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codes: codes })
          })
          .then(r => r.json())
          .then(data => {
            if (data.ok && data.wishlisted) {
              data.wishlisted.forEach(code => {
                const btn = document.querySelector(`.btn-wish-slide[data-code="${code}"]`);
                if (btn) {
                  btn.querySelector('.wish-icon').src = '<?= $BASE ?? '' ?>/img/icons/heart2.png';
                  btn.classList.add('is-active');
                }
              });
            }
          })
          .catch(err => console.error(err));
        }
      });
      <?php endif; ?>
      </script>
    </div>

    <!-- =========================
         BEST 3
         ========================= -->
    <?php if (!empty($best_products)): ?>
    <section class="best-section" style="max-width: 1200px; margin: 60px auto; padding: 20px;">
      <div class="best-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 3px solid #ff6b6b;">
        <h2 style="font-size: 2rem; margin: 0; color: #333; display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 2.5rem;">🏆</span>
          <span>BEST 상품 TOP 3</span>
        </h2>
        <a href="<?= $BASE ?? '' ?>/best.php" style="color: #ff6b6b; text-decoration: none; font-size: 1rem; font-weight: 500;">전체보기 ></a>
      </div>
      
      <div class="best-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px;">
        <?php foreach ($best_products as $idx => $item): 
          $rank = $idx + 1;
          $img = isset($item['content_img']) ? trim($item['content_img']) : '';
          $imgSrc = $img ? bustIfLocal($img) : bust('img/no_image.png');
          $name = htmlspecialchars($item['content_name'] ?? '');
          $price = number_format((int)($item['content_price'] ?? 0));
          $code = htmlspecialchars($item['content_code'] ?? '');
          $category = htmlspecialchars($item['category_large'] ?? '기타');
          $review_count = (int)($item['review_count'] ?? 0);
          $avg_star = (float)($item['avg_star'] ?? 0);
          $sales = (int)($item['content_sales'] ?? 0);
          
          $rankColors = [
            1 => 'linear-gradient(135deg, #FFD700, #FFA500)',
            2 => 'linear-gradient(135deg, #C0C0C0, #A8A8A8)',
            3 => 'linear-gradient(135deg, #CD7F32, #B8860B)'
          ];
          $rankColor = $rankColors[$rank] ?? '#f0f0f0';
        ?>
        <div class="best-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s; cursor: pointer; position: relative;"
             onclick="location.href='contents_detail.php?content_code=<?= $code ?>'">
          <div class="rank-badge" style="position: absolute; top: 15px; left: 15px; width: 50px; height: 50px; background: <?= $rankColor ?>; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
            <?= $rank ?>
          </div>
          <div style="position: relative; overflow: hidden; height: 280px;">
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= $name ?>" 
                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                 onerror="this.src='<?= bust('img/no_image.png') ?>'"
                 onmouseover="this.style.transform='scale(1.05)'"
                 onmouseout="this.style.transform='scale(1)'">
          </div>
          <div style="padding: 20px;">
            <div style="display: inline-block; padding: 4px 10px; background: #fff5f5; color: #ff6b6b; border-radius: 20px; font-size: 0.85rem; margin-bottom: 10px;">
              <?= $category ?>
            </div>
            <h3 style="font-size: 1.1rem; margin: 0 0 12px 0; color: #333; font-weight: 600; line-height: 1.4; min-height: 2.8em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
              <?= $name ?>
            </h3>
            
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 0.9rem; color: #666;">
                <div style="display: flex; align-items: center; gap: 2px;">
                    <i class="fas fa-star" style="color: #ffc107; font-size: 0.9rem;"></i>
                    <span style="font-weight: bold; color: #333;"><?= number_format($avg_star, 1) ?></span>
                    <span style="color: #999;">(<?= number_format($review_count) ?>)</span>
                </div>
                <?php if ($sales > 0): ?>
                <span style="color: #ddd;">|</span>
                <span style="color: #ff6b6b; font-weight: 500;">구매 <?= number_format($sales) ?>건</span>
                <?php endif; ?>
            </div>

            <div style="font-size: 1.3rem; font-weight: bold; color: #ff6b6b;">
              <?= $price ?>원
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <style>
        .best-card:hover {
          transform: translateY(-8px);
          box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
          .best-grid {
            grid-template-columns: 1fr !important;
          }
          .best-header h2 {
            font-size: 1.5rem !important;
          }
        }
        
        @media (max-width: 1024px) and (min-width: 769px) {
          .best-grid {
            grid-template-columns: repeat(2, 1fr) !important;
          }
        }
      </style>
    </section>
    <?php endif; ?>

    <!-- =========================
         COMMUNITY (Latest Reviews)
         ========================= -->
    <?php if (!empty($latest_reviews)): ?>
    <section class="community-section" style="max-width: 1200px; margin: 0 auto 60px; padding: 20px;">
      <div class="community-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #333;">
        <h2 style="font-size: 1.8rem; margin: 0; color: #333;">🗣️ 생생한 후기</h2>
        <a href="<?= $BASE ?? '' ?>/community.php" style="color: #666; text-decoration: none; font-size: 0.95rem;">더보기 ></a>
      </div>

      <div class="community-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <?php foreach ($latest_reviews as $review): 
            $r_img = !empty($review['photo']) ? $review['photo'] : $review['content_img'];
            $r_imgSrc = $r_img ? bustIfLocal($r_img) : bust('img/no_image.png');
            $r_content = mb_substr(strip_tags($review['review_contents']), 0, 50, 'utf-8') . '...';
            $r_date = date('Y.m.d', strtotime($review['review_regdate']));
            $r_star = (int)$review['star'];
        ?>
        <div class="community-card" style="background: #f9f9f9; border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.2s;" 
             onclick="location.href='community.php'">
            <div style="height: 180px; overflow: hidden;">
                <img src="<?= htmlspecialchars($r_imgSrc) ?>" alt="리뷰 이미지" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <div style="padding: 15px;">
                <div style="color: #ff6b6b; margin-bottom: 8px; font-size: 0.9rem;">
                    <?php for($k=0; $k<5; $k++) echo $k < $r_star ? '★' : '☆'; ?>
                </div>
                <p style="font-size: 0.95rem; color: #333; line-height: 1.5; margin: 0 0 10px 0; height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    <?= htmlspecialchars($review['review_contents']) ?>
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #888;">
                    <span><?= htmlspecialchars($review['writer_id']) ?>님</span>
                    <div style="display: flex; gap: 8px;">
                        <span title="조회수"><i class="far fa-eye"></i> <?= number_format($review['views'] ?? 0) ?></span>
                        <span title="댓글"><i class="far fa-comment-dots"></i> <?= number_format($review['comment_count'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
      </div>
      <style>
        .community-card:hover { transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); background: white; }
        @media (max-width: 768px) {
            .community-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 480px) {
            .community-grid { grid-template-columns: 1fr !important; }
        }
      </style>
    </section>
    <?php endif; ?>

    <!-- =========================
         NOTICE
         ========================= -->
    <?php if (!empty($latest_notices)): ?>
    <section class="notice-section" style="max-width: 1200px; margin: 0 auto 60px; padding: 20px;">
      <div class="notice-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 10px;">
        <h2 style="font-size: 1.5rem; margin: 0; color: #333;">📢 공지사항</h2>
      </div>
      <ul class="notice-list" style="list-style: none; padding: 0; margin: 0;">
        <?php foreach ($latest_notices as $notice): ?>
        <li style="border-bottom: 1px solid #eee; padding: 12px 0; display: flex; justify-content: space-between; align-items: center;">
            <a href="notice_view.php?id=<?= $notice['id'] ?>" style="font-size: 1rem; color: #333; flex: 1; text-decoration: none; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?= htmlspecialchars($notice['title']) ?>
            </a>
            <span style="font-size: 0.9rem; color: #888; flex-shrink: 0; margin-left: 10px;"><?= substr($notice['reg_date'], 0, 10) ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endif; ?>
  </main>

  <?php if (file_exists(__DIR__ . "/inc/fast_move.php")) require_once __DIR__ . "/inc/fast_move.php"; ?>
  <?php if (file_exists(__DIR__ . "/inc/footer.php"))    require_once __DIR__ . "/inc/footer.php"; ?>

  <!-- 스크립트 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <!-- <script src="js/hot_issue.js"></script>  파일 없어서 404 떠서 잠시 비활성화 -->
  <script src="js/member.js"></script>
  <script src="js/sort.js"></script>

  <script>
  // 정렬 탭 전환 (간단 로컬 토글)
  (function(){
    function showList(id){
      ['list_sales','list_price_H','list_price_L','list_rating'].forEach(function(i){
        var el = document.getElementById(i);
        if(!el) return;
        el.style.display = (i === id) ? 'block' : 'none';
      });
    }

    var btnSales = document.getElementById('btn_sales');
    var btnPh = document.getElementById('btn_price_h');
    var btnPl = document.getElementById('btn_price_l');
    var btnRt = document.getElementById('btn_rating');

    function setActive(btn){
      [btnSales, btnPh, btnPl, btnRt].forEach(function(b){ if(b) b.classList.remove('is-active'); });
      if(btn) btn.classList.add('is-active');
    }

    if(btnSales){ btnSales.addEventListener('click', function(){ setActive(btnSales); showList('list_sales'); }); }
    if(btnPh){    btnPh.addEventListener('click', function(){ setActive(btnPh); showList('list_price_H'); }); }
    if(btnPl){    btnPl.addEventListener('click', function(){ setActive(btnPl); showList('list_price_L'); }); }
    if(btnRt){    btnRt.addEventListener('click', function(){ setActive(btnRt); showList('list_rating'); }); }
  })();
  </script>

  <!-- 배너 자동 슬라이더 -->
  <script>
  (function(){
    const root  = document.getElementById('hero');
    if(!root) return;
    const track = root.querySelector('.banner_track');
    const dotsW = root.querySelector('.banner_dots');
    const slides= Array.from(track.children);
    const dots  = dotsW ? Array.from(dotsW.children) : [];
    if(slides.length <= 1) return;

    let idx = 0, timer = null;
    const DURATION = 4000;

    function go(n){
      idx = (n + slides.length) % slides.length;
      track.style.transform = `translateX(${-idx * 100}%)`;
      dots.forEach((d,i)=>d.classList.toggle('is-active', i===idx));
    }
    function start(){ stop(); timer = setInterval(()=>go(idx+1), DURATION); }
    function stop(){ if(timer){ clearInterval(timer); timer=null; } }

    dots.forEach((d,i)=> d.addEventListener('click', ()=>{ go(i); start(); }));
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);

    // 터치 스와이프
    let sx=0, dx=0;
    root.addEventListener('touchstart', e=>{ sx=e.touches[0].clientX; dx=0; stop(); }, {passive:true});
    root.addEventListener('touchmove',  e=>{ dx=e.touches[0].clientX - sx; }, {passive:true});
    root.addEventListener('touchend',   ()=>{ if(Math.abs(dx)>40){ go(idx + (dx<0?1:-1)); } start(); });

    go(0);
    start();
  })();

  // 사이드 광고 자동 슬라이더
  (function(){
    const root = document.getElementById('side_ad_slider');
    if(!root) return;
    const track = root.querySelector('.side_ad_track');
    if(!track) return;
    const slides = Array.from(track.children);
    if(slides.length <= 1) return;

    let idx = 0, timer = null;
    const DURATION = 3000; // 3초마다 전환

    function go(n){
      idx = (n + slides.length) % slides.length;
      track.style.transform = `translateX(${-idx * 100}%)`;
    }
    function start(){ stop(); timer = setInterval(()=>go(idx+1), DURATION); }
    function stop(){ if(timer){ clearInterval(timer); timer=null; } }

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);

    go(0);
    start();
  })();
  </script>
</body>
</html>
