<?php
// DB 헬퍼 로드
require_once __DIR__ . "/inc/db.php";

$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';


// =========================================================
// 1. 카테고리 맵핑 정의 (화면 메뉴와 DB 실제 값을 연결)
// =========================================================
// ✅ DB에 존재하는 실제 카테고리 값을 화면에 표시할 메뉴 이름에 연결합니다.
$category_map = [
    '한식'    => ['한식'],     
    '양식'    => ['양식'], 
    '중식'    => ['중식'],
    '일식'    => ['일식'],
    '건강식'  => ['건강식'],
];
$display_menu_names = array_keys($category_map); 


// =========================================================
// 2. DB 상품 개수 조회 및 메뉴 개수 계산
// =========================================================
$count_query = "SELECT category_large, COUNT(*) as count FROM contents WHERE category_large IS NOT NULL AND category_large != '' GROUP BY category_large";
$db_results = db_select($count_query);

$category_counts = []; // DB의 실제 카테고리별 개수 (예: ['상의' => 4])
if (is_array($db_results)) {
    foreach ($db_results as $row) {
        $category_counts[trim($row['category_large'])] = (int)$row['count'];
    }
}

// 맵핑된 카테고리별 총합 계산
$total_all_count = array_sum($category_counts);
$menu_count_map = []; // 화면 메뉴별 총합 (예: ['한식' => 5])

foreach ($category_map as $menu_name => $db_keys) {
    $current_count = 0;
    foreach ($db_keys as $key) {
        $current_count += $category_counts[$key] ?? 0;
    }
    $menu_count_map[$menu_name] = $current_count;
}


// =========================================================
// 3. 정렬(Sort) 및 카테고리(Filter) 기준 처리
// =========================================================
$current_category = isset($_GET['cat']) ? $_GET['cat'] : 'all';

// "전체" 카테고리를 'all'로 처리
if ($current_category === '전체' || $current_category === 'all') {
    $current_category = 'all';
    $page_title = "전체 상품";
} else {
    $page_title = htmlspecialchars($current_category) . " 상품";
}

// 정렬 기준 가져오기
$current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';
$order_by_sql = '';

switch ($current_sort) {
    case 'sales': $order_by_sql = 'ORDER BY content_sales DESC'; break;
    case 'price_high': $order_by_sql = 'ORDER BY content_price DESC'; break;
    case 'price_low': $order_by_sql = 'ORDER BY content_price ASC'; break;
    case 'rating': 
        // 리뷰 개수순 정렬 (서브쿼리 사용)
        $order_by_sql = 'ORDER BY (SELECT COUNT(*) FROM review WHERE review.content_code = contents.content_code) DESC'; 
        break;
    case 'new': default: $order_by_sql = 'ORDER BY content_code DESC'; $current_sort = 'new'; break;
}

// 목록 개수 (LIMIT) 처리
// 페이지 및 목록 개수 (LIMIT, OFFSET)
$current_limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
if ($current_limit < 1) $current_limit = 20; 
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($current_page - 1) * $current_limit;


// =========================================================
// 4. 최종 상품 조회 쿼리 실행 (검색 및 필터링 통합)
// =========================================================
$search_query = $_GET['q'] ?? ''; // ✅ URL에서 검색어 (q)를 가져옴

$query = "SELECT contents.*, 
            (SELECT COUNT(*) FROM review WHERE review.content_code = contents.content_code) as review_count,
            (SELECT AVG(star) FROM review WHERE review.content_code = contents.content_code) as avg_star
            FROM contents";
$params = [];
$conditions = [];


// 4-1. 카테고리 필터링 조건 추가
if ($current_category !== 'all') {
    $db_keys_to_filter = $category_map[$current_category] ?? [];
    
    if (!empty($db_keys_to_filter)) {
        $placeholders = implode(', ', array_fill(0, count($db_keys_to_filter), '?'));
        $conditions[] = "category_large IN ({$placeholders})";
        $params = array_merge($params, $db_keys_to_filter);
    } else {
        $conditions[] = "1 = 0"; // 상품 없음
    }
}

// 4-2. 검색어 필터링 조건 추가
if (!empty($search_query)) {
    // 상품명(content_name)에 검색어(q)가 포함된 상품을 찾습니다.
    $conditions[] = "content_name LIKE ?";
    $params[] = "%" . $search_query . "%"; // %검색어% 형태로 LIKE 검색
}

// 4-3. 모든 조건 통합
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " " . $order_by_sql;
$query .= " LIMIT " . intval($current_limit) . " OFFSET " . intval($offset);

$products = db_select($query, $params);
if (!is_array($products)) $products = [];

$total_products_on_page = count($products); 

// 전체 개수 조회(더보기 버튼 노출 여부 판단용)
$count_query = "SELECT COUNT(*) AS cnt FROM contents";
$count_params = $params;
if (!empty($conditions)) {
    $count_query .= " WHERE " . implode(' AND ', $conditions);
}
$count_res = db_select($count_query, $count_params);
$total_matching = isset($count_res[0]['cnt']) ? (int)$count_res[0]['cnt'] : 0;
$has_more = ($offset + $current_limit) < $total_matching;

// AJAX 요청인 경우: JSON { html: string, count: int } 를 반환
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $items_html = '';
    $returned_count = 0;

    if (!empty($products)) {
        foreach ($products as $r) {
            $img   = isset($r['content_img']) ? trim($r['content_img']) : '';
            $imgSrc= $img ? $BASE . '/' . ltrim($img, '/') : $BASE . '/img/no_image.png';
            $name  = htmlspecialchars($r['content_name'] ?? '상품명 없음');
            $price = number_format((int)($r['content_price'] ?? 0));
            $rate  = (int)($r['discount_rate'] ?? 0);
            $code  = htmlspecialchars($r['content_code'] ?? '');
            $category = htmlspecialchars($r['category_large'] ?? '기타');
            $review_count = (int)($r['review_count'] ?? 0);
            $avg_star = (float)($r['avg_star'] ?? 0);
            $sales = (int)($r['content_sales'] ?? 0);

            $li = '<li class="product-card" style="background: white; border-radius: 12px; overflow: hidden; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; display: flex; flex-direction: column;" onmouseover="this.style.transform=\'translateY(-4px)\'; this.style.boxShadow=\'0 4px 12px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 8px rgba(0,0,0,0.1)\'">';
            
            // Image Area
            $li .= '<div style="position: relative; width: 100%; aspect-ratio: 1; overflow: hidden;" onclick="location.href=\'contents_detail.php?content_code=' . $code . '\'">';
            $li .= '<img src="' . htmlspecialchars($imgSrc) . '" alt="' . $name . '" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform=\'scale(1.05)\'" onmouseout="this.style.transform=\'scale(1)\'" />';
            
            // Wishlist Button
            $li .= '<button type="button" class="btn-wish-product" data-code="' . $code . '" style="position: absolute; right: 8px; bottom: 8px; z-index: 10; background: transparent; border: none; cursor: pointer; padding: 0; width: 36px; height: 36px;" onclick="event.stopPropagation(); toggleWishProduct(this, \'' . $code . '\');">';
            $li .= '<div class="wish-circle" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s;">';
            $li .= '<img src="' . $BASE . '/img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">';
            $li .= '</div></button></div>';
            
            // Text Area
            $li .= '<div style="padding: 12px; display: flex; flex-direction: column; flex: 1;" onclick="location.href=\'contents_detail.php?content_code=' . $code . '\'">';
            $li .= '<div style="margin-bottom: 12px;">';
            $li .= '<span style="color: #333; font-size: 15px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">' . $name . '</span></div>';
            
            $li .= '<div style="margin-top: auto; display: flex; justify-content: space-between; align-items: flex-end;">';
            $li .= '<div style="display: flex; flex-direction: column;">';
            if ($rate > 0) {
                $li .= '<span style="color: #e3405a; font-size: 14px; font-weight: 700; margin-bottom: 2px;">' . $rate . '%</span>';
            }
            $li .= '<span style="font-size: 18px; font-weight: 700; color: #333; letter-spacing: -0.5px;">' . $price . '원</span>';
            $li .= '</div>';

            $li .= '<div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #999; padding-bottom: 3px;">';
            $li .= '<i class="fas fa-star" style="color: #ffc107; font-size: 11px;"></i>';
            $li .= '<span style="color: #333; font-weight: 600;">' . number_format($avg_star, 1) . '</span>';
            $li .= '<span style="color: #999;">(' . number_format($review_count) . ')</span>';
            if ($sales > 0) {
                $li .= '<span style="color: #ddd;">|</span>';
                $li .= '<span style="color: #666;">구매 ' . number_format($sales) . '</span>';
            }
            $li .= '</div></div></div></li>';

            $items_html .= $li;
            $returned_count++;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'html' => $items_html,
        'count' => $returned_count
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
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
<body class="product-page">
    <?php require_once __DIR__ . "/inc/header.php"; ?>

    <main class="main_wrapper">
        <div class="product_page_layout full-width-list"> 
            
            <section class="product_list_view">
                
                <div class="page-title-section">
                    <span class="breadcrumb">홈 > <?= htmlspecialchars($page_title) ?></span> 
                    <h1 class="page-main-title"><?= htmlspecialchars($page_title) ?></h1>
                    
                    <div class="list_header">
                        <span class="total_count_display">총 <?= $total_all_count ?>개 상품이 있습니다</span>
                        
                        <div class="sort_options_wrapper">
                            
                            <select class="sort_select" onchange="updateSort(this.value);">
                                <?php
                                $sort_options = [
                                    'rating'     => '리뷰순', 
                                    'new'        => '신상품순',
                                    'sales'      => '판매순',
                                    'price_high' => '높은가격순',
                                    'price_low'  => '낮은가격순',
                                ];
                                
                                foreach ($sort_options as $sort_key => $sort_name) {
                                    // 정렬 시 검색어($search_query)도 유지
                                    $url_params = "cat=" . urlencode($current_category) . "&limit=" . $current_limit . "&q=" . urlencode($search_query);
                                    $option_url = "product.php?" . $url_params . "&sort=" . $sort_key;
                                ?>
                                    <option value="<?= $BASE ?>/<?= $option_url ?>" 
                                        <?= $current_sort === $sort_key ? 'selected' : '' ?>>
                                        <?= $sort_name ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <select class="limit_select" onchange="updateLimit(this.value);">
                                <?php 
                                $limits = [20, 60, 100]; 
                                foreach ($limits as $limit_val):
                                    // 목록 개수 변경 시 검색어($search_query)와 정렬 기준도 유지
                                    $url_params = "cat=" . urlencode($current_category) . "&sort=" . $current_sort . "&q=" . urlencode($search_query);
                                    $limit_url = "product.php?" . $url_params . "&limit=" . $limit_val;
                                ?>
                                    <option value="<?= $BASE ?>/<?= $limit_url ?>"
                                            <?= $current_limit === $limit_val ? 'selected' : '' ?>>
                                        <?= $limit_val ?>개 보기
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <div class="view_mode_buttons">
                                <i class="fas fa-th-large active"></i> <i class="fas fa-list"></i> 
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; list-style: none; padding: 0; margin: 20px 0;">
                    <?php if (empty($products)): ?>
                        <p class="no-results" style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #666; font-size: 1.1rem;">선택된 카테고리 또는 검색어에 해당하는 상품이 없습니다.</p>
                    <?php else: ?>
                        <?php foreach ($products as $r):
                            $img   = isset($r['content_img']) ? trim($r['content_img']) : '';
                            $imgSrc= $img ? $BASE . '/' . ltrim($img, '/') : $BASE . '/img/no_image.png'; 
                            
                            $name  = htmlspecialchars($r['content_name'] ?? '상품명 없음');
                            $price = number_format((int)($r['content_price'] ?? 0));
                            $rate  = (int)($r['discount_rate'] ?? 0);
                            $code  = htmlspecialchars($r['content_code'] ?? '');
                            $category = htmlspecialchars($r['category_large'] ?? '기타');
                            $review_count = (int)($r['review_count'] ?? 0);
                            $avg_star = (float)($r['avg_star'] ?? 0);
                            $sales = (int)($r['content_sales'] ?? 0);
                        ?>
                        <li class="product-card" style="background: white; border-radius: 12px; overflow: hidden; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; display: flex; flex-direction: column;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
                            <div style="position: relative; width: 100%; aspect-ratio: 1; overflow: hidden;" onclick="location.href='contents_detail.php?content_code=<?= $code ?>'">
                                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= $name ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" />
                                
                                <!-- 찜하기 버튼 -->
                                <button type="button" class="btn-wish-product" data-code="<?= $code ?>" style="position: absolute; right: 8px; bottom: 8px; z-index: 10; background: transparent; border: none; cursor: pointer; padding: 0; width: 36px; height: 36px;" onclick="event.stopPropagation(); toggleWishProduct(this, '<?= $code ?>');">
                                    <div class="wish-circle" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s;">
                                        <img src="<?= $BASE ?>/img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px;" class="wish-icon">
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
                                        <span style="color: #e3405a; font-size: 14px; font-weight: 700; margin-bottom: 2px;"><?= $rate ?>%</span>
                                        <?php endif; ?>
                                        <span style="font-size: 18px; font-weight: 700; color: #333; letter-spacing: -0.5px;"><?= $price ?>원</span>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #999; padding-bottom: 3px;">
                                        <i class="fas fa-star" style="color: #ffc107; font-size: 11px;"></i>
                                        <span style="color: #333; font-weight: 600;"><?= number_format($avg_star, 1) ?></span>
                                        <span style="color: #999;">(<?= number_format($review_count) ?>)</span>
                                        <?php if ($sales > 0): ?>
                                            <span style="color: #ddd;">|</span>
                                            <span style="color: #666;">구매 <?= number_format($sales) ?></span>
                                        <?php endif; ?>
                                    </div>
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
                    .btn-wish-product .wish-circle:hover {
                        transform: scale(1.1);
                        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                    }
                </style>
                
                <?php if ($has_more): ?>
                    <div class="load-more-wrap">
                        <?php
                        // 다음 페이지에 요청할 URL (AJAX 사용)
                        $next_page = $current_page + 1;
                        $base_params = [ 'cat' => $current_category, 'sort' => $current_sort, 'q' => $search_query, 'limit' => $current_limit ];
                        $query_string = http_build_query(array_merge($base_params, ['page' => $next_page, 'ajax' => 1]));
                        $ajax_url = $BASE . '/product.php?' . $query_string;
                        ?>
                        <button id="btnLoadMore" data-next-page="<?= $next_page ?>" data-ajax-url="<?= htmlspecialchars($ajax_url) ?>">더보기</button>
                    </div>
                <?php endif; ?>
            </section>

            <?php
            // 검색어가 있을 경우 커뮤니티 리뷰 검색 결과 표시
            if (!empty($search_query)) {
                $review_query = "
                    SELECT 
                        r.*,
                        c.content_name,
                        c.content_img,
                        m.name as writer_name
                    FROM review r
                    LEFT JOIN contents c ON r.content_code = c.content_code
                    LEFT JOIN members m ON r.writer_id = m.id
                    WHERE r.content LIKE ? OR c.content_name LIKE ?
                    ORDER BY r.review_regdate DESC
                    LIMIT 4
                ";
                $review_params = ["%$search_query%", "%$search_query%"];
                $search_reviews = db_select($review_query, $review_params);

                if (!empty($search_reviews)) {
            ?>
            <section class="search_review_view" style="margin-top: 60px; padding-top: 40px; border-top: 1px solid #eee;">
                <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">'<?= htmlspecialchars($search_query) ?>' 관련 커뮤니티 리뷰</h2>
                <div class="review-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <?php foreach ($search_reviews as $review): 
                        // 리뷰 이미지가 있으면 사용, 없으면 상품 이미지 사용
                        $r_img = !empty($review['photo']) ? $BASE . '/img/review/' . $review['photo'] : (!empty($review['content_img']) ? $BASE . '/' . ltrim($review['content_img'], '/') : $BASE . '/img/no_image.png');
                    ?>
                    <div class="review-card" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; cursor: pointer; background: white;" onclick="location.href='community.php?tab=all'">
                        <div style="width: 100%; height: 200px; overflow: hidden;">
                            <img src="<?= htmlspecialchars($r_img) ?>" alt="리뷰 이미지" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                        <div style="padding: 15px;">
                            <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($review['content_name']) ?></div>
                            <div style="font-size: 13px; color: #666; margin-bottom: 10px; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"><?= htmlspecialchars($review['content']) ?></div>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #999;">
                                <span><?= htmlspecialchars($review['writer_name']) ?></span>
                                <span><i class="fas fa-star" style="color: #fadb14;"></i> <?= $review['star'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <style>
                    @media (max-width: 768px) {
                        .review-grid {
                            grid-template-columns: repeat(2, 1fr) !important;
                        }
                    }
                </style>
            </section>
            <?php 
                }
            } 
            ?>

        </div>
    </main>

    <?php require_once __DIR__ . "/inc/fast_move.php"; ?>
    <?php require_once __DIR__ . "/inc/footer.php"; ?>

    <script>
        // 더보기(AJAX) 기능
        document.addEventListener('DOMContentLoaded', function(){
            var btn = document.getElementById('btnLoadMore');
            if (!btn) return;
            btn.addEventListener('click', function(){
                var url = btn.getAttribute('data-ajax-url');
                btn.disabled = true;
                btn.textContent = '로딩 중...';
                fetch(url)
                    .then(function(r){ return r.json(); })
                    .then(function(resp){
                        var html = resp.html || '';
                        var count = resp.count || 0;
                        var container = document.querySelector('.product-grid');
                        if (container && html) {
                            var temp = document.createElement('div');
                            temp.innerHTML = html;
                            while (temp.firstChild) {
                                container.appendChild(temp.firstChild);
                            }
                            // 새로 추가된 상품들의 찜 상태 확인
                            if (typeof checkWishlistStatus === 'function') {
                                checkWishlistStatus();
                            }
                        }

                        // prepare next page: increment data-next-page and ajax-url
                        var next = parseInt(btn.getAttribute('data-next-page')) + 1;
                        btn.setAttribute('data-next-page', next);
                        var urlObj = new URL(btn.getAttribute('data-ajax-url'), window.location.origin);
                        urlObj.searchParams.set('page', next);
                        btn.setAttribute('data-ajax-url', urlObj.pathname + urlObj.search);

                        // 버튼 숨김 조건: 서버가 반환한 항목 수가 요청한 limit보다 적으면 더 이상 항목이 없음
                        var requestedLimit = <?= (int)$current_limit ?>;
                        if (count === 0 || count < requestedLimit) {
                            btn.style.display = 'none';
                        } else {
                            btn.disabled = false;
                            btn.textContent = '더보기';
                        }
                    })
                    .catch(function(){
                        btn.disabled = false;
                        btn.textContent = '더보기';
                        alert('데이터 로드에 실패했습니다. 새로고침 후 재시도하세요.');
                    });
            });
        });

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
        
        // 찜하기 버튼에 이벤트 추가
        document.addEventListener('click', function(e){
            var t = e.target;
            if (t.closest && t.closest('.btn-wish-product')) {
                e.preventDefault();
                e.stopPropagation();
                var btn = t.closest('.btn-wish-product');
                var code = btn.getAttribute('data-code');
                toggleWishProduct(btn, code);
            }
        });
        
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

        function updateSort(url) {
            window.location.href = url;
        }
        function updateLimit(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>