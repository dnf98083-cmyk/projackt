<?php 
// DB 헬퍼 로드
$db_path = __DIR__ . '/db.php';
if (!file_exists($db_path)) {
    $db_path = __DIR__ . '/../inc/db.php';
}
$db_helper_loaded = file_exists($db_path);
if ($db_helper_loaded) {
    require_once $db_path;
} else {
    // DB 헬퍼 로드 실패 시, 오류 로깅은 유지하고 프로그램은 계속 진행
    error_log("DB helper not found for main_menu.php");
    $db_helper_loaded = false;
}

// BASE URL 설정 (이전과 동일)
if (!isset($BASE)) {
    $BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    if ($BASE === '') $BASE = '/';
}

// =========================================================
// 1. 카테고리 맵핑 정의 (화면 메뉴와 DB 실제 값을 연결)
// =========================================================
$category_map = [
    '전체 카테고리' => ['all'],
    '한식'      => ['한식'], 
    '양식'      => ['양식'], 
    '중식'      => ['중식'],
    '일식'      => ['일식'],
    '건강식'    => ['건강식'],
];
$display_menu_names = array_keys($category_map); 


// =========================================================
// 2. DB 상품 개수 조회 및 동적 데이터 생성 (드로어용)
// =========================================================
$category_counts = [];
if ($db_helper_loaded) {
    $count_query = "SELECT category_large, COUNT(*) as count FROM contents WHERE category_large IS NOT NULL AND category_large != '' GROUP BY category_large";
    $db_results = db_select($count_query);
    if (is_array($db_results)) {
        foreach ($db_results as $row) {
            $category_counts[trim($row['category_large'])] = (int)$row['count'];
        }
    }
}

$dynamic_cats = [];
$total_all_count = array_sum($category_counts);

// 동적 데이터 (드로어 메뉴용) 생성
foreach ($display_menu_names as $menu_name) {
    $current_count = 0;
    $db_keys = $category_map[$menu_name];
    
    if ($menu_name === '전체 카테고리') {
        $current_count = $total_all_count;
        $menu_url = "product.php?cat=all";
    } else {
        foreach ($db_keys as $key) {
            $current_count += $category_counts[$key] ?? 0;
        }
        $menu_url = "product.php?cat=" . urlencode($menu_name);
    }
    
    // DB의 카테고리 값이 전혀 없는 항목은 (0)으로 표시되도록 유지
    // 100개 이상이면 "99+"로 표시
    $display_count = $current_count > 99 ? "99+" : $current_count;
    
    $dynamic_cats[] = [
        "name" => $menu_name, 
        "href" => "{$BASE}/{$menu_url}",
        "count" => $display_count
    ];
}

// 드로어용 JSON 데이터 생성
$json_cats = json_encode($dynamic_cats);

// 현재 URL에서 활성화된 카테고리 파라미터 가져오기
$current_cat_param = $_GET['cat'] ?? 'none';
?>
<div class="main_menu_wrapper">
  <ul class="main_menu_bar">
    <li class="mm-item mm-cat">
      <a href="#" class="open-drawer" role="button"
        aria-controls="categoryDrawer" aria-expanded="false"
        data-cats='<?= $json_cats ?>'
        >
        <span class="ico-hamburger" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z" />
          </svg>
        </span>
        <span>카테고리</span>
      </a>
    </li>


    <li><a href="<?= $BASE ?>/new.php">NEW</a></li>
    <li><a href="<?= $BASE ?>/best.php">BEST</a></li>
    <li><a href="<?= $BASE ?>/sale.php">할인</a></li>
    <li><a href="<?= $BASE ?>/community.php">커뮤니티</a></li>
  </ul>
</div>