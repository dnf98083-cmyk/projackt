<?php
// 세션 불러오기 (다른 페이지들과 동일하게 사용)
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 회원 정보 가져오기
$member_id = $_SESSION['member_id']  ?? null;          // 로그인 아이디

// 이름은 두 키 중 하나에서 가져오도록 (member_name 없으면 name 사용)
$member_name = $_SESSION['member_name'] 
               ?? $_SESSION['name'] 
               ?? null;

// 로그인 안 돼 있으면 로그인 페이지로 이동
if (empty($member_id)) {
    echo "<script>
            alert('로그인 후 이용 가능합니다.');
            location.href='login.php';
          </script>";
    exit;
}

// 마이페이지에서 사용할 이름 (이름 없으면 아이디라도 표시)
$username = $member_name ?: $member_id;


// 아래부터는 친구가 짜둔 나머지 변수들 그대로 사용
$user_grade = "화이트";
$user_points = 0;
$coupon_count = 0;
$order_shipping_count = 0;

$status_payment_complete = 0;
$status_preparing = 0;
$status_shipping = 0;
$status_delivered = 0;
$status_confirmed = 0;

$cancel_count = 0;
$exchange_count = 0;
$return_count = 0;

// ----------------------------------------------------
// 날짜 검색 및 페이지네이션 로직
// ----------------------------------------------------
$view_mode = $_GET['view'] ?? 'date'; // 'date' or 'all'
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if ($view_mode === 'all') {
    // 전체보기 모드: 날짜 필터 무시
    $start_date = '';
    $end_date = '';
    
    // 전체 개수 조회
    $count_result = db_select("SELECT COUNT(*) as cnt FROM pay WHERE member_id = ?", [$member_id]);
    $total_count = $count_result[0]['cnt'] ?? 0;
    
    // 데이터 조회 (페이징)
    // LIMIT, OFFSET은 PDO execute로 넘기면 문자열로 처리되어 오류가 날 수 있으므로 직접 쿼리에 넣음 (정수형 변환 필수)
    $orders = db_select("SELECT * FROM pay WHERE member_id = ? ORDER BY order_date DESC LIMIT $limit OFFSET $offset", [$member_id]);
    
} else {
    // 날짜 검색 모드 (기본)
    $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-1 month'));
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    
    // 쿼리용 날짜 포맷
    $query_start = $start_date . " 00:00:00";
    $query_end = $end_date . " 23:59:59";
    
    // 전체 개수 조회 (날짜 필터 적용)
    $count_result = db_select("SELECT COUNT(*) as cnt FROM pay WHERE member_id = ? AND order_date BETWEEN ? AND ?", [$member_id, $query_start, $query_end]);
    $total_count = $count_result[0]['cnt'] ?? 0;
    
    // 데이터 조회 (날짜 필터 + 페이징)
    $orders = db_select("SELECT * FROM pay WHERE member_id = ? AND order_date BETWEEN ? AND ? ORDER BY order_date DESC LIMIT $limit OFFSET $offset", [$member_id, $query_start, $query_end]);
}

// 총 페이지 수 계산
$total_pages = ceil($total_count / $limit);

// 상태별 카운트 계산 (이건 항상 전체 기준인지, 조회된 기준인지? -> 보통 상단 현황판은 '최근 1개월' 또는 '전체' 기준이 일반적이나, 여기서는 전체 기준으로 유지하거나 필터링된 기준으로 할 수 있음. 
// 기존 코드에서는 필터링된 $orders를 루프 돌렸으나, 페이징 때문에 $orders에 전체 데이터가 없음.
// 따라서 상단 상태판용 카운트는 별도로 구해야 함. 
// 사용자 요청: "주문 내역 조회하는데 등급이 변경되는 현상이 있어 해결해줘" -> 등급은 전체 기준.
// 상단 '진행중인 주문' 카운트는 보통 최근 내역 기준임. 여기서는 일단 $orders 루프 대신 별도 쿼리로 구하거나, 
// 기존 로직($orders 루프)을 유지하면 페이징된 10개에 대해서만 카운트가 됨 -> 이건 이상함.
// 따라서 상단 상태 카운트는 '최근 1개월' 고정으로 하거나, '전체'로 하는게 맞음. 
// 여기서는 '최근 1개월' 기준으로 다시 조회해서 카운트 하겠음 (일반적인 쇼핑몰 UX).

$status_payment_complete = 0;
$status_preparing = 0;
$status_shipping = 0;
$status_delivered = 0;
$status_confirmed = 0;

// 상단 상태판용: 최근 1개월 데이터 조회
$month_ago = date('Y-m-d H:i:s', strtotime('-1 month'));
$status_orders = db_select("SELECT status FROM pay WHERE member_id = ? AND order_date >= ?", [$member_id, $month_ago]);

foreach ($status_orders as $ord) {
    $st = $ord['status'] ?? '';
    switch ($st) {
        case '결제완료': $status_payment_complete++; break;
        case '상품준비중': $status_preparing++; break;
        case '배송중': $status_shipping++; break;
        case '배송완료': $status_delivered++; break;
        case '구매확정': $status_confirmed++; break;
    }
}
$order_shipping_count = count($status_orders); // 최근 1개월 주문 수로 표시 (또는 전체로 표시할 수도 있음)

// ----------------------------------------------------
// 포인트 및 등급 계산 로직 (사용자 요청)
// ----------------------------------------------------

// 1. 등급 산정을 위한 전체 주문 건수 조회 (날짜 필터 무시하고 전체 조회)
$all_time_orders = db_select("SELECT status FROM pay WHERE member_id = ?", [$member_id]);
$total_valid_order_count = 0;

foreach ($all_time_orders as $ord) {
    $st = $ord['status'] ?? '';
    // 유효 주문 상태 체크
    if (in_array($st, ['결제완료', '상품준비중', '배송중', '배송완료', '구매확정'])) {
        $total_valid_order_count++;
    }
}

// 2. 작성한 리뷰 건수 계산
// review 테이블이 있다고 가정 (review_insert.php 참조)
$review_count_result = db_select("SELECT COUNT(*) as cnt FROM review WHERE writer_id = ?", [$member_id]);
$review_count = $review_count_result[0]['cnt'] ?? 0;

// 3. 포인트 계산 (전체 누적 기준 - 등급 산정용)
// - 결제완료(유효주문) 1건당 100포인트
// - 리뷰 1건당 200포인트
$calculated_score = ($total_valid_order_count * 100) + ($review_count * 200);

// [수정] 실제 사용 가능한 포인트는 DB에서 가져옴
$member_info = db_select("SELECT point FROM members WHERE id = ?", [$member_id]);
$db_point = $member_info[0]['point'] ?? 0;

// [마이그레이션 로직]
// DB 포인트가 0이고, 계산된 점수가 0보다 크며, 사용 내역이 없는 경우 초기화 (기존 활동에 대한 보상)
$used_point_check = db_select("SELECT SUM(used_point) as total_used FROM pay WHERE member_id = ?", [$member_id]);
$total_used = $used_point_check[0]['total_used'] ?? 0;

if ($db_point == 0 && $calculated_score > 0 && $total_used == 0) {
    // 포인트 초기화 (마이그레이션)
    db_update_delete("UPDATE members SET point = ? WHERE id = ?", [$calculated_score, $member_id]);
    $db_point = $calculated_score;
}

$user_points = $db_point; // 화면 표시용 변수 (보유 포인트)

// 4. 등급 산정 (누적 점수 기준)
// 0~400: 화이트
// 500~900: 브론즈
// 1000~1090: 실버
// 1100~1190: 골드
// 1200~1290: 플래티넘
// 1300~: 다이아
$user_grade = "화이트";
$grade_initial = "W";
$grade_class = "grade-white";

if ($calculated_score >= 1300) {
    $user_grade = "다이아";
    $grade_initial = "D";
    $grade_class = "grade-diamond";
} elseif ($calculated_score >= 1200) {
    $user_grade = "플래티넘";
    $grade_initial = "P";
    $grade_class = "grade-platinum";
} elseif ($calculated_score >= 1100) {
    $user_grade = "골드";
    $grade_initial = "G";
    $grade_class = "grade-gold";
} elseif ($calculated_score >= 1000) {
    $user_grade = "실버";
    $grade_initial = "S";
    $grade_class = "grade-silver";
} elseif ($calculated_score >= 500) {
    $user_grade = "브론즈";
    $grade_initial = "B";
    $grade_class = "grade-bronze";
}

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
    <title>마이페이지 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

    <?php require_once __DIR__ . "/inc/header.php"; ?>

    <main class="main_wrapper">
        <div class="mypage-wrapper">
            
            <?php require_once("inc/mypage_sidebar.php"); ?>

            <section class="mypage-content">
                <div class="profile-box">
                    <div class="grade-icon <?= $grade_class ?>"><?= $grade_initial ?></div>
                    <div class="user-info">
                        <h4><strong><?= htmlspecialchars($username) ?>님!</strong> 반갑습니다.</h4>
                        <p>
                            <strong><?= $user_grade ?></strong> |
                            적립 <strong>0%</strong> | 
                            100,000원 더 구매 하시면 오천원지급
                        </p>
                    </div>
                    <div class="user-stats">
                        <div>
                            <span>주문/배송</span>
                            <strong><?= $order_shipping_count ?><small>건</small></strong>
                        </div>
                        <div>
                            <span>쿠폰</span>
                            <strong><?= $coupon_count ?><small>개</small></strong>
                        </div>
                        <div>
                            <span>포인트</span>
                            <strong><?= $user_points ?><small>P</small></strong>
                        </div>
                    </div>
                </div>

                <div class="order-status-box">
                    <div class="box-title">
                        <h4>진행중인 주문 <strong>30일</strong> 이내</h4>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="status-pipeline" style="flex-grow: 1;">
                            <div class="step">
                                <strong><?= $status_payment_complete ?></strong>
                                결제완료
                            </div>
                            <span class="pipeline-arrow">→</span>
                            <div class="step">
                                <strong><?= $status_preparing ?></strong>
                                상품준비중
                            </div>
                            <span class="pipeline-arrow">→</span>
                            <div class="step">
                                <strong><?= $status_shipping ?></strong>
                                배송중
                            </div>
                            <span class="pipeline-arrow">→</span>
                            <div class="step">
                                <strong><?= $status_delivered ?></strong>
                                배송완료
                            </div>
                            <span class="pipeline-arrow">→</span>
                            <div class="step">
                                <strong><?= $status_confirmed ?></strong>
                                구매확정
                            </div>
                        </div>
                    </div>
                </div>

                <div class="date-filter-wrap" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eee;">
                    <form method="GET" onsubmit="return validateDateRange()" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="date" name="start_date" id="start_date" value="<?= $start_date ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                            <span>~</span>
                            <input type="date" name="end_date" id="end_date" value="<?= $end_date ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                        </div>
                        <button type="submit" style="padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">조회</button>
                    </form>
                </div>

                <script>
                function validateDateRange() {
                    const startStr = document.getElementById('start_date').value;
                    const endStr = document.getElementById('end_date').value;
                    
                    if (!startStr || !endStr) return true;
                    
                    const start = new Date(startStr);
                    const end = new Date(endStr);
                    
                    // 날짜 차이 계산 (밀리초 단위)
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    
                    if (diffDays > 30) {
                        alert('30일 이내에 선택하세요');
                        return false;
                    }
                    return true;
                }
                </script>

                <div class="order-list-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #333;">
                        <h4 style="margin: 0; font-size: 1.2rem; font-weight: bold;">주문배송 내역</h4>
                        <a href="?view=all" style="font-size: 0.9rem; color: #666; font-weight: bold; text-decoration: none;">전체보기 ></a>
                    </div>
                    <?php if (empty($orders)): ?>
                        <div class="no-data" style="padding: 50px 0; text-align: center; color: #888;">
                            <i class="fas fa-exclamation-circle" style="font-size: 40px; color: #ddd; margin-bottom: 15px;"></i>
                            <p>조회된 기간 내 주문내역이 없습니다.</p>
                        </div>
                    <?php else: ?>
                        <div class="order-list">
                            <?php foreach ($orders as $ord): 
                                $items = json_decode($ord['order_contents'], true);
                                $first_item = $items[0] ?? null;
                                
                                if ($first_item) {
                                    $title = $first_item['content_name'];
                                    if (count($items) > 1) {
                                        $title .= " 외 " . (count($items) - 1) . "건";
                                    }
                                    $img = $first_item['content_img'];
                                    $content_code = $first_item['content_code'];
                                } else {
                                    $title = "상품 정보 없음 (주문번호: " . $ord['order_id'] . ")";
                                    $img = "img/icons/no-image.png"; // 대체 이미지
                                    $content_code = "";
                                }

                                $status = $ord['status'] ?? '결제완료';
                                $review_yn = $ord['review'] ?? 'N';
                            ?>
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-date"><?= date('Y.m.d', strtotime($ord['order_date'] ?? 'now')) ?></span>
                                    <span class="order-id">주문번호 <?= $ord['order_id'] ?></span>
                                    <a href="#" class="detail-link">상세보기 ></a>
                                </div>
                                <div class="order-body">
                                    <div class="product-info">
                                        <a href="contents_detail.php?content_code=<?= $content_code ?>">
                                            <img src="<?= $img ?>" alt="상품이미지" class="thumb" onerror="this.src='https://placehold.co/80x80?text=No+Image'">
                                        </a>
                                        <div class="info-text">
                                            <div class="status-badge"><?= $status ?></div>
                                            <a href="contents_detail.php?content_code=<?= $content_code ?>" style="text-decoration: none; color: inherit;">
                                                <div class="product-name"><?= htmlspecialchars($title) ?></div>
                                            </a>
                                            <div class="price"><?= number_format($ord['total_price']) ?>원</div>
                                        </div>
                                    </div>
                                    <div class="action-buttons">
                                        <?php if ($status == '결제완료'): ?>
                                            <form action="order_claim.php" method="POST" onsubmit="return confirm('주문을 취소하시겠습니까?');" style="display:inline;">
                                                <input type="hidden" name="order_id" value="<?= $ord['order_id'] ?>">
                                                <input type="hidden" name="claim_type" value="cancel">
                                                <button type="submit" class="btn-cancel" style="padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer; margin-right: 5px;">주문취소</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($status == '배송중' || $status == '결제완료'): ?>
                                            <form action="order_confirm.php" method="POST" onsubmit="return confirm('상품을 수령하셨습니까? 구매를 확정합니다.');" style="display:inline;">
                                                <input type="hidden" name="order_id" value="<?= $ord['order_id'] ?>">
                                                <button type="submit" class="btn-confirm">상품 수령 확인</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($status == '구매확정'): ?>
                                            <form action="order_claim.php" method="POST" onsubmit="return confirm('반품을 신청하시겠습니까?');" style="display:inline;">
                                                <input type="hidden" name="order_id" value="<?= $ord['order_id'] ?>">
                                                <input type="hidden" name="claim_type" value="return">
                                                <button type="submit" class="btn-return" style="padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer; margin-right: 5px;">반품신청</button>
                                            </form>

                                            <?php if ($review_yn == 'N' && $content_code): ?>
                                                <a href="review_write.php?order_id=<?= $ord['order_id'] ?>&content_code=<?= $content_code ?>" class="btn-review">리뷰 쓰기</a>
                                            <?php elseif ($review_yn == 'Y'): ?>
                                                <a href="contents_detail.php?content_code=<?= $content_code ?>" class="btn-review-done">리뷰등록완료</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <a href="#" class="btn-inquiry">배송조회</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- 페이지네이션 -->
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination" style="display: flex; justify-content: center; gap: 5px; margin-top: 30px;">
                            <?php 
                            $qs = $_GET;
                            unset($qs['page']);
                            $base_url = '?' . http_build_query($qs);
                            ?>
                            
                            <?php if ($page > 1): ?>
                                <a href="<?= $base_url ?>&page=1" style="padding: 8px 12px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 4px;">&lt;&lt;</a>
                                <a href="<?= $base_url ?>&page=<?= $page - 1 ?>" style="padding: 8px 12px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 4px;">&lt;</a>
                            <?php endif; ?>

                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                                $active = ($i == $page) ? 'background: #333; color: white; border-color: #333;' : 'background: white; color: #666; border: 1px solid #ddd;';
                            ?>
                                <a href="<?= $base_url ?>&page=<?= $i ?>" style="padding: 8px 12px; text-decoration: none; border-radius: 4px; <?= $active ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="<?= $base_url ?>&page=<?= $page + 1 ?>" style="padding: 8px 12px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 4px;">&gt;</a>
                                <a href="<?= $base_url ?>&page=<?= $total_pages ?>" style="padding: 8px 12px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 4px;">&gt;&gt;</a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <?php require_once __DIR__ . "/inc/footer.php"; ?>
</body>
</html>