<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/session.php';

$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); if ($BASE === '') $BASE = '/';

if (empty($_SESSION['member_id'])) {
    header('Location: ' . $BASE . '/login.php');
    exit;
}
$member_id = $_SESSION['member_id'];

// 목록 조회: wishlist join contents
$query = "SELECT c.* FROM wishlist w JOIN contents c ON c.content_code = w.content_code WHERE w.member_id = ? ORDER BY w.created_at DESC";
$items = db_select($query, [$member_id]);
if (!is_array($items)) $items = [];

$page_title = '찜한 상품';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($page_title) ?> - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= $BASE ?>/css/style.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body>
<?php require_once __DIR__ . '/inc/header.php'; ?>
<main class="main_wrapper">
    <div class="product_page_layout full-width-list">
        <section class="product_list_view">
            <div class="page-title-section">
                <span class="breadcrumb">홈 > <?= htmlspecialchars($page_title) ?></span>
                <h1 class="page-main-title"><?= htmlspecialchars($page_title) ?></h1>
                <div class="list_header">
                    <span class="total_count_display">총 <?= count($items) ?>개 상품</span>
                </div>
            </div>

            <ul class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; list-style: none; padding: 0; margin: 20px 0;">
                <?php if (empty($items)): ?>
                    <p class="no-results" style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #666; font-size: 1.1rem;">찜한 상품이 없습니다.</p>
                <?php else: ?>
                    <?php foreach ($items as $r):
                        $img   = isset($r['content_img']) ? trim($r['content_img']) : '';
                        $imgSrc= $img ? $BASE . '/' . ltrim($img, '/') : $BASE . '/img/no_image.png'; 
                        $name  = htmlspecialchars($r['content_name'] ?? '상품명 없음');
                        $price = number_format((int)($r['content_price'] ?? 0));
                        $rate  = (int)($r['discount_rate'] ?? 0);
                        $code  = htmlspecialchars($r['content_code'] ?? '');
                        $category = htmlspecialchars($r['category_large'] ?? '기타');
                    ?>
                    <li class="product-card" style="background: white; border-radius: 12px; overflow: hidden; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
                        <div style="position: relative; width: 100%; aspect-ratio: 1; overflow: hidden;" onclick="location.href='contents_detail.php?content_code=<?= $code ?>'">
                            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= $name ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" />
                            
                            <!-- 찜하기 버튼 -->
                            <button type="button" class="btn-wish is-active" data-code="<?= $code ?>" style="position: absolute; right: 8px; bottom: 8px; z-index: 10; background: transparent; border: none; cursor: pointer; padding: 0; width: 36px; height: 36px;">
                                <div class="wish-circle" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s;">
                                    <img src="<?= $BASE ?>/img/icons/heart2.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">
                                </div>
                            </button>
                        </div>
                        
                        <div style="padding: 12px;" onclick="location.href='contents_detail.php?content_code=<?= $code ?>'">
                            <div style="display: inline-block; padding: 4px 10px; background: #fff5f5; color: #ff6b6b; border-radius: 20px; font-size: 0.85rem; margin-bottom: 8px;">
                                <?= $category ?>
                            </div>
                            <div style="min-height: 2.5em; margin-bottom: 8px;">
                                <span style="color: #333; font-size: 14px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= $name ?></span>
                            </div>
                            <div style="display: flex; align-items: baseline; gap: 8px;">
                                <?php if ($rate > 0): ?>
                                <span style="color: #e3405a; font-size: 16px; font-weight: 700;"><?= $rate ?>%</span>
                                <?php endif; ?>
                                <span style="font-size: 15px; font-weight: 600; color: #333;"><?= $price ?>원</span>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            
            <style>
                @media (max-width: 768px) {
                    .product-grid {
                        grid-template-columns: repeat(2, 1fr) !important;
                    }
                }
                @media (max-width: 1024px) and (min-width: 769px) {
                    .product-grid {
                        grid-template-columns: repeat(3, 1fr) !important;
                    }
                }
                .btn-wish .wish-circle:hover {
                    transform: scale(1.1);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                }
            </style>
        </section>
    </div>
</main>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
<script>
// 찜 토글
document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('.btn-wish');
    buttons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // 부모 요소(상품 상세 이동)로 이벤트 전파 중단

            var code = btn.getAttribute('data-code');
            var form = new FormData();
            form.append('content_code', code);

            fetch('<?= $BASE ?>/wishlist_toggle.php', { method:'POST', body: form })
                .then(function(r){ return r.json(); })
                .then(function(resp){
                    if (resp && resp.ok) {
                        if (resp.toggled === 'removed') {
                            // 찜 해제 시 카드 제거 애니메이션
                            var li = btn.closest('.product-card');
                            if (li) {
                                li.style.transition = 'all 0.3s';
                                li.style.opacity = '0';
                                li.style.transform = 'scale(0.8)';
                                setTimeout(function() {
                                    li.remove();
                                    // 남은 상품 개수 업데이트
                                    var count = document.querySelectorAll('.product-card').length;
                                    var countDisplay = document.querySelector('.total_count_display');
                                    if (countDisplay) {
                                        countDisplay.textContent = '총 ' + count + '개 상품';
                                    }
                                    // 상품이 없으면 메시지 표시
                                    if (count === 0) {
                                        var grid = document.querySelector('.product-grid');
                                        if (grid) {
                                            grid.innerHTML = '<p class="no-results" style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #666; font-size: 1.1rem;">찜한 상품이 없습니다.</p>';
                                        }
                                    }
                                }, 300);
                            }
                        }
                    } else if (resp && resp.error === 'NOT_AUTH') {
                        alert('로그인이 필요합니다.');
                        window.location.href = '<?= $BASE ?>/login.php';
                    } else {
                        alert('요청 처리 실패');
                    }
                })
                .catch(function(err){ 
                    console.error(err);
                    alert('네트워크 오류'); 
                });
        });
    });
});
</script>
</body>
</html>
