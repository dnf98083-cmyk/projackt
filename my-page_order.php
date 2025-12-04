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

// 주문 목록 조회
$orders = db_select("SELECT * FROM pay WHERE member_id = ? ORDER BY order_date DESC", [$member_id]);

// 상태별 카운트 계산
foreach ($orders as $ord) {
    $st = $ord['status'] ?? '';
    switch ($st) {
        case '결제완료': $status_payment_complete++; break;
        case '상품준비중': $status_preparing++; break;
        case '배송중': $status_shipping++; break;
        case '배송완료': $status_delivered++; break;
        case '구매확정': $status_confirmed++; break;
    }
}
$order_shipping_count = count($orders);

// ----------------------------------------------------
// 포인트 및 등급 계산 로직 (사용자 요청)
// ----------------------------------------------------

// 1. 유효 주문 건수 계산 (취소/반품/교환 제외하고, 결제완료 이상인 건수)
// 위 foreach에서 이미 카운트된 변수들을 활용
$valid_order_count = $status_payment_complete + $status_preparing + $status_shipping + $status_delivered + $status_confirmed;

// 2. 작성한 리뷰 건수 계산
// review 테이블이 있다고 가정 (review_insert.php 참조)
$review_count_result = db_select("SELECT COUNT(*) as cnt FROM review WHERE writer_id = ?", [$member_id]);
$review_count = $review_count_result[0]['cnt'] ?? 0;

// 3. 포인트 계산
// - 결제완료(유효주문) 1건당 100포인트
// - 리뷰 1건당 200포인트
$user_points = ($valid_order_count * 100) + ($review_count * 200);

// 4. 등급 산정
// 0~400: 화이트
// 500~900: 브론즈
// 1000~1090: 실버
// 1100~1190: 골드
// 1200~1290: 플래티넘
// 1300~: 다이아
$user_grade = "화이트";
$grade_initial = "W";
$grade_class = "grade-white";

if ($user_points >= 1300) {
    $user_grade = "다이아";
    $grade_initial = "D";
    $grade_class = "grade-diamond";
} elseif ($user_points >= 1200) {
    $user_grade = "플래티넘";
    $grade_initial = "P";
    $grade_class = "grade-platinum";
} elseif ($user_points >= 1100) {
    $user_grade = "골드";
    $grade_initial = "G";
    $grade_class = "grade-gold";
} elseif ($user_points >= 1000) {
    $user_grade = "실버";
    $grade_initial = "S";
    $grade_class = "grade-silver";
} elseif ($user_points >= 500) {
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
                        <a href="#" style="font-size: 0.9rem; color: #666;">전체보기</a>
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

                <div class="order-list-box">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                        <h4>주문배송 내역</h4>
                        <a href="#" style="font-size: 0.9rem; color: #666;">전체보기</a>
                    </div>
                    <?php if (empty($orders)): ?>
                        <div class="no-data">
                            최근 1개월 내에 주문내역이 없습니다.
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
                                        <?php if ($status == '배송중' || $status == '결제완료'): ?>
                                            <form action="order_confirm.php" method="POST" onsubmit="return confirm('상품을 수령하셨습니까? 구매를 확정합니다.');">
                                                <input type="hidden" name="order_id" value="<?= $ord['order_id'] ?>">
                                                <button type="submit" class="btn-confirm">상품 수령 확인</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($status == '구매확정'): ?>
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
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <?php require_once __DIR__ . "/inc/footer.php"; ?>
</body>
</html>