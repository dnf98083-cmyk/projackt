<?php
// DB 헬퍼 및 세션 로드
require_once("inc/db.php");
require_once("inc/session.php");

$content_code = $_GET["content_code"] ?? null; 

if (empty($content_code)) {
    echo "<script>alert('유효하지 않은 상품 코드입니다.'); history.back();</script>";
    exit;
}

// 1. 상품 상세 정보 조회
$result = db_select("select * from contents where content_code= ?", array($content_code));
if (empty($result)) {
    echo "<script>alert('상품 정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}
$product = $result[0]; 

// 2. 리뷰 통계 (평균 별점) 계산
$review_count = 0;
$avg_rating = 0;
$avg_result = db_select("SELECT AVG(star) AS avg_rating, COUNT(*) as review_count FROM review WHERE content_code= ?", array($content_code));
if (!empty($avg_result)) {
    $review_count = (int)($avg_result[0]['review_count'] ?? 0);
    $avg_rating = round($avg_result[0]['avg_rating'] ?? 0, 1);
}

// 3. 리뷰 목록 조회 (최신순) - 작성자 이름 포함
$reviews = db_select("SELECT r.*, m.name as writer_name FROM review r LEFT JOIN members m ON r.writer_id = m.id WHERE r.content_code = ? ORDER BY r.review_id DESC LIMIT 5", array($content_code));

// 4. 별점 HTML 함수 (부분 채워짐 지원)
function generate_star_html($rating) {
    // 5점 만점 기준 백분율 계산
    $percent = ($rating / 5) * 100;
    
    // 별점 HTML 구조 (회색 별 위에 색깔 별을 덮어씌우는 방식)
    // Font Awesome 아이콘 사용
    $html = '
    <div class="star-rating-system" style="position: relative; display: inline-block; color: #ddd; font-size: inherit;">
        <div class="back-stars" style="display: flex;">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
        </div>
        <div class="front-stars" style="position: absolute; top: 0; left: 0; white-space: nowrap; overflow: hidden; width: ' . $percent . '%; color: #f90; display: flex;">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
        </div>
    </div>
    ';
    return $html;
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>상세 - <?= htmlspecialchars($product['content_name']) ?></title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>

<body>
<?php require_once("inc/header.php"); ?>

<main class="main_wrapper contents_detail">
    <div class="product-detail-layout">

        <!-- 상품 이미지 -->
        <div class="image-gallery-area">
            <div class="main-image-wrap" style="position: relative; overflow: visible;">
                <img src="<?= htmlspecialchars($product["content_img"])?>" 
                     alt="<?= htmlspecialchars($product["content_name"])?> 메인" 
                     class="main-image"/>
                
                <!-- 찜하기 버튼 -->
                <button class="btn-wish-detail" data-code="<?= htmlspecialchars($product["content_code"]) ?>" 
                        style="position: absolute; right: 8px; bottom: 8px; z-index: 999; background: transparent; border: none; cursor: pointer; padding: 0; width: 36px; height: 36px;"
                        onclick="event.stopPropagation(); toggleWishDetail(this, '<?= htmlspecialchars($product["content_code"]) ?>')">
                    <div class="wish-circle" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.9); box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.2s;">
                        <img src="img/icons/heart1.png" alt="찜하기" style="width: 20px; height: 20px; pointer-events: none;" class="wish-icon">
                    </div>
                    <span class="wish-tooltip" style="position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 8px; padding: 6px 12px; background: rgba(0,0,0,0.8); color: white; border-radius: 4px; font-size: 12px; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.2s; z-index: 1000;">상품 찜하기!</span>
                </button>
            </div>
        </div>

        <!-- 상품 정보 -->
        <div class="info-options-area">

            <span class="product-brand">밀키친</span>
            <h1 class="product-main-title"><?= htmlspecialchars($product['content_name']) ?></h1>

            <div class="rating-review-wrap">
                <span class="rating-stars-display" style="font-size: 18px;">
                    <?= generate_star_html($avg_rating) ?>
                </span>
                <span class="avg-rating-text"><?= $avg_rating ?></span>
                <span class="review-count">(<?= $review_count ?>)</span>
                <span class="review-link">리뷰 보기 ></span>
            </div>

                        <div class="price-discount-wrap">
                <span class="discount-rate"><?= $product["discount_rate"] ?>%</span>
                <span class="final-price-display"><?= number_format($product["content_price"]) ?>원</span>
                <del class="original-cost"><?= number_format($product["content_cost"]) ?>원</del>
            </div>

            <hr class="divider-line">

            <!-- ✅ 택배배송 안내 박스 -->
            <div class="delivery-info-box" onclick="openDeliveryModal()">
                <div class="delivery-left">
                    <i class="fas fa-truck"></i>
                    <span class="delivery-title">택배배송 안내</span>
                </div>
                <div class="delivery-right">
                    <span class="delivery-link">배송안내</span>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>

            <!-- 여기부터 기존 코드 계속 -->
            <!-- 장바구니 + 구매 form -->
            <form action="cart_insert.php" name="contents_form" method="POST">

            <!-- ✅ 혜택/배송 영역 추가 (디자인용) -->
            <hr class="divider-line">

            <div class="benefit-section">
                <div class="benefit-item save-club">
                    <i class="fas fa-star"></i>
                    <span class="benefit-text">무조건 돈 되는 <strong>세이브클럽</strong></span>
                    <span class="benefit-rate">1% / 2% 추가적립</span>
                    <span class="benefit-detail-link">혜택확인 &gt;</span>
                </div>
                <div class="benefit-item save-club-discount">
                    <i class="fas fa-star-of-life"></i>
                    <span class="benefit-text">세이브클럽 할인</span>
                    <span class="benefit-amount">
                        <?= number_format($product["content_price"] * 0.02) ?>원
                    </span>
                    <span class="benefit-detail-link">추가적립 2%</span>
                </div>
            </div>


            <hr class="divider-line">

            <!-- 장바구니 + 구매 form -->
            <form action="cart_insert.php" name="contents_form" method="POST">

                <!-- 상품 코드 -->
                <input type="hidden" name="content_code" 
                       value="<?= htmlspecialchars($product["content_code"]) ?>">

                <!-- ⭐ JS가 채워주는 값 -->
                <input type="hidden" name="content_options" id="content_options">
                <input type="hidden" name="content_amount" id="content_amount">

                <!-- 옵션 선택 -->
                <div class="option-selection-wrap">
                    <select name="selected_options" class="option-select-box">
                        <option value="<?= htmlspecialchars($product["content_name"]) ?>" selected>
                            <?= htmlspecialchars($product["content_name"]) ?>
                        </option>
                    </select>

                    <!-- 수량 조절 -->
                    <div class="quantity-total-wrap">
                        <div class="quantity-control">
                            <button type="button" class="qty-btn minus">-</button>
                            <input type="number" class="qty-input" value="1" min="1" max="99">
                            <button type="button" class="qty-btn plus">+</button>
                        </div>

                        <span class="item-price-display"><?= number_format($product["content_price"]) ?>원</span>
                    </div>
                </div>

                <div style="margin-top: 10px;"></div>

                <!-- 구매 예정 금액 -->
                <div class="final-price-summary">
                    <span class="summary-label">구매예정금액</span>
                    <span class="summary-amount"><?= number_format($product["content_price"]) ?>원</span>
                </div>

                <!-- 버튼 -->
                <div class="action-buttons-wrap final">
                    <button type="button" class="btn-action gift" onclick="openGiftModal()">
                        <i class="fas fa-gift"></i> 선물하기
                    </button>

                    <button type="button" class="btn-action cart"
                            onclick="cart_insert()"
                            style="background-color: var(--mk-red); color:white;">
                        <i class="fas fa-shopping-cart"></i> 장바구니
                    </button>

                    <button type="submit" class="btn-action buy-now"
                            onclick="return validateOptions()"
                            formaction="payment.php"
                            style="background-color: var(--mk-red); color:white;">
                        바로구매
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- 👇 리뷰/문의/상세정보/배송안내 영역 -->
    <section class="bottom-tabs-section">
        <nav class="tabs-nav">
            <a href="#detail" class="tab-item">상세정보</a>
            <a href="#review" class="tab-item active">리뷰 (<span class="review-count-display"><?= $review_count ?></span>)</a>
            <a href="#qa" class="tab-item">상품문의</a>
            <a href="#delivery" class="tab-item">배송/교환/반품 안내</a>
        </nav>

        <div id="review" class="tab-content review-content-area">

            <div class="review-stats-header">
                <div class="overall-rating">
                    <span class="score"><?= $avg_rating ?></span>/5
                    <div class="stars-large" style="font-size: 24px; margin-top: 4px;"><?= generate_star_html($avg_rating) ?></div>
                </div>
                <div class="stat-summary">
                    <span class="total-reviews">총 <?= $review_count ?>건의 리뷰</span>
                    <span class="satisfaction-rate">96% 고객님이 5점을 주셨어요.</span>
                </div>

                <div class="rating-distribution">
                    <div class="rating-bar-wrap"><span class="rating-score">5점</span><div class="rating-bar"><div class="fill" style="width: 80%;"></div></div></div>
                    <div class="rating-bar-wrap"><span class="rating-score">4점</span><div class="rating-bar"><div class="fill" style="width: 10%;"></div></div></div>
                    <div class="rating-bar-wrap"><span class="rating-score">3점</span><div class="rating-bar"><div class="fill" style="width: 5%;"></div></div></div>
                    <div class="rating-bar-wrap"><span class="rating-score">2점</span><div class="rating-bar"><div class="fill" style="width: 3%;"></div></div></div>
                    <div class="rating-bar-wrap"><span class="rating-score">1점</span><div class="rating-bar"><div class="fill" style="width: 2%;"></div></div></div>
                </div>

                <button class="btn-review-write"
                        onclick="alert('리뷰는 마이페이지 > 주문배송 내역에서 구매확정 후 작성하실 수 있습니다.'); location.href='my-page_order.php';">
                        상품 리뷰 쓰기
                </button>

            </div>

            <hr class="section-divider">

            <div class="review-options-bar">
                <span class="review-list-count">상품리뷰 <?= $review_count ?>건</span>

                <select class="review-sort-select">
                    <option value="latest">최신 등록순</option>
                    <option value="ratings_high">평점 높은순</option>
                    <option value="ratings_low">평점 낮은순</option>
                </select>

                <label class="photo-filter">
                    <input type="checkbox" id="photoReviewFilter"> 포토 리뷰 모아보기
                </label>
            </div>

            <div class="review-list-container">
                <?php if (empty($reviews)): ?>
                    <p class="no-reviews" style="padding: 40px; text-align: center; color: #888;">아직 작성된 리뷰가 없습니다.</p>
                <?php else: ?>
                    <?php foreach($reviews as $r): ?>
                        <div class="review-item" style="padding: 20px 0; border-bottom: 1px solid #eee;">
                            <div class="review-meta" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <div>
                                    <span class="writer-id" style="font-weight: bold; margin-right: 10px;">
                                        <?= mask_id($r['writer_id']) ?> 
                                        <span style="font-weight:normal; color:#666; font-size:0.9em;">(<?= mask_name($r['writer_name']) ?>)</span>
                                    </span>
                                    <span class="review-date" style="color: #888; font-size: 0.9rem;"><?= date('Y.m.d', strtotime(substr($r['review_id'], 0, 8))) ?></span>
                                </div>
                                <div class="review-rating">
                                    <?= generate_star_html($r['star']) ?>
                                </div>
                            </div>

                            <div class="review-content" style="margin-bottom: 15px; line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($r['review_contents'])) ?>
                            </div>

                            <?php if ($r['photo']): ?>
                                <div class="review-photo-thumb" style="margin-bottom: 10px;">
                                    <img src="<?= htmlspecialchars($r['photo']) ?>" alt="리뷰 사진" style="max-width: 150px; border-radius: 4px; border: 1px solid #eee;">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="pagination">
                <a href="#">&lt;</a>
                <span class="active">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">&gt;</a>
            </div>
        </div>

        <div id="detail" class="tab-content" style="display: none;"><h2>상세 정보 내용</h2></div>
        <div id="qa" class="tab-content" style="display: none;"><h2>상품 문의 내용</h2></div>

    </section>

</main>

<?php require_once("inc/fast_move.php"); ?>
<?php require_once("inc/footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
<script src="js/option.js"></script>
<!-- ✅ 배송 안내 모달 -->
<div id="deliveryModal" class="delivery-modal">
    <div class="delivery-modal-content">
        <div class="delivery-modal-header">
            <span class="delivery-modal-title">배송안내</span>
            <button class="delivery-modal-close" onclick="closeDeliveryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- 탭 느낌 나는 파란 텍스트 -->
        <div class="delivery-modal-tab">택배배송</div>

        <div class="delivery-modal-body">
            <p class="delivery-highlight">밤 12시까지 결제 완료 시, 배송 희망일 당일 수령</p>
            <p>전국 / 제주도 및 섬지역은 배송 불가</p>
            <p>배송 불가 지역 주문 시 자동취소 될 수 있습니다.</p>
            <p>4만원 이상 무료배송 / 4만원 미만 배송비 3,000원</p>
        </div>
    </div>
</div>


<!-- ⭐ 선물하기 모달창 -->
<div id="giftModal" class="gift-modal">
    <div class="gift-modal-content">
        <h2>선물하기 방법 선택</h2>

        <!-- 선물하기 방법 선택 버튼 영역 -->
        <div id="giftMethodArea">
            <button class="gift-option kakao" onclick="gift_kakao()">
                🎁 카카오톡 친구에게 선물하기
            </button>

            <button class="gift-option phone" onclick="gift_phone()">
                📱 전화번호로 선물하기
            </button>

            <button class="gift-close" onclick="closeGiftModal()">
                ❌ 닫기
            </button>
        </div>

        <!-- 전화번호로 선물하기 폼 영역 (처음엔 숨김) -->
        <div id="giftPhoneForm" class="gift-phone-form" style="display:none;">
            <div class="gift-form-row">
                <label>받는 사람 이름</label>
                <input type="text" id="giftReceiverName" placeholder="예: 김수현">
            </div>

            <div class="gift-form-row">
                <label>받는 사람 전화번호</label>
                <input type="text" id="giftReceiverPhone" placeholder="예: 010-1234-5678">
            </div>

            <button class="gift-option phone" onclick="submitGiftByPhone()">
                ✅ 선물 요청하기
            </button>

            <button class="gift-close" onclick="backToGiftMethod()">
                ⬅ 방법 다시 선택하기
            </button>
        </div>
    </div>
</div>

<style>
    /* ===========================
   ✅ 찜하기 버튼 스타일
   =========================== */
.btn-wish-detail .wish-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
}

.btn-wish-detail.is-active .wish-circle {
    background: rgba(255, 107, 107, 0.1);
}

.btn-wish-detail:hover .wish-tooltip {
    opacity: 1;
}

    /* ===========================
   ✅ 택배배송 안내 박스
   =========================== */
.delivery-info-box{
    margin: 16px 0;
    padding: 14px 18px;
    border-radius: 10px;
    background: #f8f8f8;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.delivery-info-box:hover{
    background: #f0f0f0;
}
.delivery-left{
    display:flex;
    align-items:center;
    gap:8px;
}
.delivery-left i{
    font-size:20px;
}
.delivery-title{
    font-weight:600;
}
.delivery-right{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:14px;
    color:#666;
}
.delivery-link{
    text-decoration:underline;
}

/* ===========================
   ✅ 택배배송 안내 박스
   =========================== */
.delivery-info-box{
    margin: 16px 0;
    padding: 14px 18px;
    border-radius: 10px;
    background: #f8f8f8;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.delivery-info-box:hover{
    background: #f0f0f0;
}
.delivery-left{
    display:flex;
    align-items:center;
    gap:8px;
}
.delivery-left i{
    font-size:20px;
}
.delivery-title{
    font-weight:600;
}
.delivery-right{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:14px;
    color:#666;
}
.delivery-link{
    text-decoration:underline;
}

/* ===========================
   ✅ 배송 안내 모달
   =========================== */
.delivery-modal{
    display:none;
    position:fixed;
    z-index:9998;
    left:0; top:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.45);   /* 화면 전체 어둡게 */
}
.delivery-modal-content{
    max-width:480px;
    width:90%;
    background:#fff;
    margin:10% auto;
    padding:20px 22px;
    border-radius:12px;
    box-sizing:border-box;
    box-shadow:0 4px 18px rgba(0,0,0,0.15);
}
.delivery-modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
    border-bottom:1px solid #eee;
    padding-bottom:10px;
}
.delivery-modal-title{
    font-size:18px;
    font-weight:700;
}
.delivery-modal-close{
    border:none;
    background:transparent;
    cursor:pointer;
    font-size:18px;
}

/* 탭처럼 보이는 파란 글씨 */
.delivery-modal-tab{
    color:#0080ff;
    font-weight:700;
    font-size:14px;
    margin:10px 0 4px;
}

.delivery-modal-body p{
    font-size:14px;
    margin:6px 0;
}
.delivery-highlight{
    color:var(--mk-red);
    font-weight:600;
    margin-top:6px;
}

.gift-phone-form { margin-top: 10px; }
.gift-form-row { text-align: left; margin: 8px 0; }
.gift-form-row label {
    display: block;
    margin-bottom: 4px;
    font-size: 14px;
    font-weight: 600;
}
.gift-form-row input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* 🌙 모달창 배경 */
.gift-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}

/* 🌙 모달창 본체 */
.gift-modal-content {
    width: 350px;
    background: #fff;
    margin: 15% auto;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}
.gift-modal-content h2 {
    margin-bottom: 20px;
    font-size: 20px;
    font-weight: bold;
}
.gift-option {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 8px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}
.gift-option.kakao { background: #FEE500; }
.gift-option.phone { background: #e0e0e0; }
.gift-close {
    margin-top: 10px;
    background: #ddd;
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
}
</style>

<script>
// ✅ 찜하기 토글 함수
function toggleWishDetail(btn, code) {
    <?php if (!empty($_SESSION['member_id'])): ?>
    const form = new FormData();
    form.append('content_code', code);
    
    fetch('wishlist_toggle.php', { method: 'POST', body: form })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                const icon = btn.querySelector('.wish-icon');
                if (data.toggled === 'added') {
                    icon.src = 'img/icons/heart2.png';
                    btn.classList.add('is-active');
                } else {
                    icon.src = 'img/icons/heart1.png';
                    btn.classList.remove('is-active');
                }
            } else if (data.error === 'NOT_AUTH') {
                alert('로그인이 필요합니다.');
                location.href = 'login.php';
            }
        })
        .catch(err => console.error(err));
    <?php else: ?>
    alert('로그인이 필요합니다.');
    location.href = 'login.php';
    <?php endif; ?>
}

// ✅ 페이지 로드 시 찜 상태 확인
<?php if (!empty($_SESSION['member_id'])): ?>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector('.btn-wish-detail');
    if (btn) {
        const code = btn.dataset.code;
        
        fetch('get_wishlist_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codes: [code] })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok && data.wishlisted && data.wishlisted.includes(code)) {
                btn.querySelector('.wish-icon').src = 'img/icons/heart2.png';
                btn.classList.add('is-active');
            }
        })
        .catch(err => console.error(err));
    }
});
<?php endif; ?>

// ✅ 배송안내 모달 열기/닫기
function openDeliveryModal() {
    document.getElementById("deliveryModal").style.display = "block";
}

function closeDeliveryModal() {
    document.getElementById("deliveryModal").style.display = "none";
}

// ✅ 장바구니 담기 함수
function cart_insert() {
    const form = document.forms['contents_form'];
    const optionSelect = form.querySelector('select[name="selected_options"]');
    const qtyInput = form.querySelector('.qty-input');
    
    // 수량 확인
    const qty = parseInt(qtyInput.value) || 1;
    if (qty < 1) {
        alert('수량은 1개 이상이어야 합니다.');
        qtyInput.focus();
        return false;
    }
    
    // hidden 필드에 값 설정
    const optionsInput = document.getElementById('content_options');
    const amountInput = document.getElementById('content_amount');
    
    // 옵션 값 설정 (선택된 값 또는 첫 번째 옵션)
    if (optionSelect) {
        optionsInput.value = optionSelect.value || optionSelect.options[0]?.value || '기본';
    } else {
        optionsInput.value = '기본';
    }
    amountInput.value = qty;
    
    // 폼 제출
    form.action = 'cart_insert.php';
    form.method = 'POST';
    form.submit();
}

// ✅ 바로구매 유효성 검사
function validateOptions() {
    const form = document.forms['contents_form'];
    const optionSelect = form.querySelector('select[name="selected_options"]');
    const qtyInput = form.querySelector('.qty-input');
    
    // 옵션 선택 확인
    if (optionSelect && optionSelect.value === '') {
        alert('옵션을 선택해주세요.');
        optionSelect.focus();
        return false;
    }
    
    // 수량 확인
    const qty = parseInt(qtyInput.value) || 1;
    if (qty < 1) {
        alert('수량은 1개 이상이어야 합니다.');
        qtyInput.focus();
        return false;
    }
    
    // hidden 필드에 값 설정
    const optionsInput = document.getElementById('content_options');
    const amountInput = document.getElementById('content_amount');
    
    if (optionSelect) {
        optionsInput.value = optionSelect.value || optionSelect.options[0]?.text || '기본';
    } else {
        optionsInput.value = '기본';
    }
    amountInput.value = qty;
    
    return true;
}

// 바깥(어두운 영역) 클릭하면 닫기
window.addEventListener("click", function(e){
    const modal = document.getElementById("deliveryModal");
    if (e.target === modal) {
        closeDeliveryModal();
    }
});

function openGiftModal() {
    document.getElementById("giftModal").style.display = "block";
    document.getElementById("giftMethodArea").style.display = "block";
    document.getElementById("giftPhoneForm").style.display = "none";
}
function closeGiftModal() {
    document.getElementById("giftModal").style.display = "none";
}
function gift_kakao() {
    alert("카카오톡 선물하기 기능은 준비 중입니다!");
}
function gift_phone() {
    document.getElementById("giftMethodArea").style.display = "none";
    document.getElementById("giftPhoneForm").style.display = "block";
}
function submitGiftByPhone() {
    const name = document.getElementById("giftReceiverName").value.trim();
    const phone = document.getElementById("giftReceiverPhone").value.trim();

    if (!name) { alert("받는 사람 이름을 입력해주세요."); return; }
    if (!phone) { alert("받는 사람 전화번호를 입력해주세요."); return; }

    const phoneRegex = /^[0-9\-]+$/;
    if (!phoneRegex.test(phone)) {
        alert("전화번호는 숫자와 '-'만 입력할 수 있습니다.");
        return;
    }

    alert(name + " (" + phone + ") 님께 선물하기를 진행합니다! (나중에 결제 페이지로 연결)");
    // location.href = "gift_pay.php?name=" + encodeURIComponent(name) + "&phone=" + encodeURIComponent(phone);
}
function backToGiftMethod() {
    document.getElementById("giftPhoneForm").style.display = "none";
    document.getElementById("giftMethodArea").style.display = "block";
}
</script>

</body>
</html>
