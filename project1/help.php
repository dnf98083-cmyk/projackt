<?php
// 도움말(튜토리얼) 페이지
require_once __DIR__ . '/inc/db.php';

// 캐시 버스팅 헬퍼 (스타일 갱신 즉시 반영)
function bust($relPath) {
  $abs = __DIR__ . '/' . ltrim($relPath, '/');
  $ver = is_file($abs) ? filemtime($abs) : time();
  return $relPath . '?v=' . $ver;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Meal Kitchen - 도움말</title>
  <!-- 스타일 경로 수정 (상대 경로 사용) -->
  <link rel="stylesheet" href="<?= bust('css/style.css') ?>" />
  <link rel="stylesheet" href="<?= bust('css/help.css') ?>" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body>
<?php include_once __DIR__ . '/inc/header.php'; ?>

<main class="main_wrapper help-page-wrapper">
  <!-- 상단 타이틀 영역 -->
  <div class="help-header">
    <h1 class="help-title">도움말 & 사용 가이드</h1>
    <p class="help-subtitle">쇼핑몰 이용에 필요한 주요 기능과 사용법을 안내해 드립니다.</p>
  </div>

  <!-- 컨텐츠 섹션 -->
  <div class="help-content-grid">
    
    <!-- 카드 1: 회원가입 및 혜택 -->
    <a href="<?= $BASE ?>/sign_up.php" class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-user-plus"></i>
      </div>
      <div class="help-card-body">
        <h2>회원가입 및 혜택</h2>
        <ul>
          <li><b>회원가입</b>: 누구나 무료로 가입할 수 있으며, 가입 즉시 <b>일반 회원(Level 9)</b> 등급이 부여됩니다.</li>
          <li><b>포인트</b>: 상품 구매 시 일정 비율의 포인트가 적립되며, 현금처럼 사용 가능합니다.</li>
          <li><b>정보관리</b>: 마이페이지에서 개인정보를 안전하게 관리하세요.</li>
        </ul>
      </div>
    </a>

    <!-- 카드 2: 주문 가이드 -->
    <article class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-mouse-pointer"></i>
      </div>
      <div class="help-card-body">
        <h2>주문 방법</h2>
        <ul>
          <li><b>상품선택</b>: 상세페이지에서 옵션과 수량을 선택하세요.</li>
          <li><b>장바구니</b>: 여러 상품을 담아 한 번에 결제할 수 있습니다.</li>
          <li><b>결제수단</b>: 신용카드, 무통장입금 등 다양한 결제 수단을 지원합니다.</li>
        </ul>
      </div>
    </article>

    <!-- 카드 3: 배송 안내 -->
    <article class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-truck"></i>
      </div>
      <div class="help-card-body">
        <h2>배송 안내</h2>
        <ul>
          <li><b>배송비</b>: 기본 배송비는 3,000원입니다.</li>
          <li><b>무료배송</b>: <b>40,000원 이상</b> 구매 시 무료로 배송해 드립니다.</li>
          <li><b>배송기간</b>: 결제 완료 후 통상 2~3일(영업일 기준) 이내에 배송됩니다.</li>
          <li><b>반품방법</b>: 마이페이지 > 주문내역에서 반품 신청이 가능합니다.</li>
        </ul>
      </div>
    </article>

    <!-- 카드 4: 마이페이지 -->
    <a href="<?= $BASE ?>/my-page_order.php" class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-user-circle"></i>
      </div>
      <div class="help-card-body">
        <h2>마이페이지 활용</h2>
        <ul>
          <li><b>주문조회</b>: 실시간 배송 상태와 과거 주문 내역을 확인하세요.</li>
          <li><b>위시리스트</b>: 마음에 드는 상품을 찜해두고 나중에 구매하세요.</li>
          <li><b>리뷰관리</b>: 작성한 리뷰를 관리하고 적립금을 확인하세요.</li>
        </ul>
      </div>
    </a>

    <!-- 카드 5: 교환 및 반품 -->
    <a href="<?= $BASE ?>/customer_center.php" class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-box-open"></i>
      </div>
      <div class="help-card-body">
        <h2>교환 및 반품</h2>
        <ul>
          <li><b>신청기간</b>: 상품 수령 후 7일 이내에 신청 가능합니다.</li>
          <li><b>신청방법</b>: 고객센터 또는 1:1 문의를 통해 접수해 주세요.</li>
          <li><b>주의사항</b>: 상품 가치가 훼손된 경우 교환/반품이 제한될 수 있습니다.</li>
        </ul>
      </div>
    </a>

    <!-- 카드 6: 등급 및 포인트 -->
    <article class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-crown"></i>
      </div>
      <div class="help-card-body">
        <h2>등급 및 포인트</h2>
        <ul>
          <li><b>회원등급</b>: 구매 실적에 따라 일반, VIP, VVIP 등급이 부여됩니다.</li>
          <li><b>포인트적립</b>: 구매 금액의 1%가 포인트로 적립됩니다.</li>
          <li><b>포인트사용</b>: 1,000P 이상부터 현금처럼 사용 가능합니다.</li>
        </ul>
      </div>
    </article>

    <!-- 카드 7: 고객센터 -->
    <a href="<?= $BASE ?>/customer_center.php" class="help-card">
      <div class="help-card-icon">
        <i class="fas fa-headset"></i>
      </div>
      <div class="help-card-body">
        <h2>고객센터</h2>
        <ul>
          <li><b>운영시간</b>: 평일 10:00 ~ 18:00 (주말/공휴일 휴무)</li>
          <li><b>FAQ</b>: 자주 묻는 질문에서 빠른 해결책을 찾아보세요.</li>
          <li><b>1:1 문의</b>: 해결되지 않는 문제는 언제든 문의 남겨주세요.</li>
        </ul>
      </div>
    </a>

  </div>

  <div class="help-footer-link">
    <p>더 궁금한 점이 있으신가요?</p>
    <a href="<?= $BASE ?>/customer_center.php" class="btn-customer-center">고객센터 바로가기 <i class="fas fa-chevron-right"></i></a>
  </div>

</main>
<?php
include_once __DIR__ . '/inc/footer.php';
?>
</body>
</html>
