<?php
// =================================================================
// PHP 로직 블록 (HTML 출력 전 모든 변수 설정 및 require_once 처리)
// =================================================================

// 1. session.php 자동 로드 (PHP 구문 최종 안정화)
$__candidates = [__DIR__ . '/session.php', __DIR__ . '/../session.php'];
$__loaded = false;
foreach ($__candidates as $__p) {
    if (file_exists($__p)) {
        require_once $__p;
        $__loaded = true;
        break;
    }
}
if (!$__loaded) {
    die('session.php not found (looked in inc/ and project root)');
}

// 2. 프로젝트 베이스 URL 설정
$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';
?>
<div id="header_wrapper">
  <header>
    <ul class="util_nav">
      <?php if (empty($_SESSION['member_id'])) { ?>
        <li><a href="<?= $BASE ?>/sign_up.php">회원가입</a></li>
        <li><a href="<?= $BASE ?>/customer_center.php">고객센터</a></li>
        <li><a href="<?= $BASE ?>/login.php">로그인</a></li>
      <?php } else { ?>
        <li><a href="<?= $BASE ?>/help.php">도움말</a></li>
        <li><a href="<?= $BASE ?>/customer_center.php">고객센터</a></li>
        <li><a href="<?= $BASE ?>/logout.php">로그아웃</a></li>
      <?php } ?>
    </ul>

    <div class="main_nav">
      <a href="<?= $BASE ?>/index.php" class="mk-brand">
        <span>Meal Kitchen</span>
      </a>

      <form method="GET" action="<?= $BASE ?>/product.php" class="mk-search mk-search--header">
        <input
          type="text"
          placeholder="검색어를 입력하세요"
          name="q"
          class="mk-search-input"
          value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
        />
        <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </form>

      <div class="mk-util">
        <ul>
          <li><a href="<?= $BASE ?>/wishlist.php"><i class="fa-regular fa-heart"></i> <span>찜한 상품</span></a></li>
          <li><a href="<?= $BASE ?>/cart_list.php"><i class="fa-solid fa-bag-shopping"></i> <span>장바구니</span></a></li>
          <li><a href="<?= $BASE ?>/my-page_order.php"><i class="fa-regular fa-user"></i> <span>마이페이지</span></a></li>
        </ul>
      </div>
    </div>

    <?php require_once __DIR__ . '/main_menu.php'; ?>
  </header>
</div>

<style>
/* =========================================
   드로어(카테고리) 최종 스타일 - header 전용
   ========================================= */

/* 드로어 열렸을 때 본문 스크롤 막기 */
body.is-drawer-open {
  overflow: hidden;
}

/* 리스트/헤더 기본값 리셋 */
.drawer-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

/* 드로어 전체 레이아웃 (헤더 바로 아래, 왼쪽에서 슬라이드) */
.drawer {
  position: fixed;
  left: 0;
  top: var(--header-h);
  width: 280px;
  max-width: 86vw;
  height: calc(100dvh - var(--header-h));
  background: #fff;
  border-right: 1px solid #eee;
  box-shadow: 0 6px 24px rgba(0, 0, 0, .08);
  transform: translateX(-100%);
  transition: transform .28s ease;
  z-index: 1200;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  outline: none;
}
.drawer.is-open {
  transform: translateX(0);
}

/* 헤더 영역 (카테고리 / X 아이콘) */
.drawer__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 22px 12px; /* 위 14, 아래 12 */
  font-size: 20px;
  font-weight: 700;
  border-bottom: 1px solid #f3f3f3;
}
.drawer__head strong {
  font-weight: 700;
}
.drawer-close {
  border: none;
  background: transparent;
  font-size: 22px;
  cursor: pointer;
}

/* 리스트 부분 – 헤더 바로 아래에서 자연스럽게 시작 */
.drawer__body {
  flex: 1 1 auto;
  overflow: auto;
  padding: 4px 0 0; /* 위 여백 아주 약간만 */
}

/* 각 항목 한 줄 스타일 */
.drawer-item a {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding: 10px 22px;   /* 줄 간 간격 */
  font-size: 18px;
  text-decoration: none;
  color: #111;
  box-sizing: border-box;
}
.drawer-item a:hover {
  background: #fafafa;
  transform: translateX(2px);
}

/* 왼쪽 텍스트: 한 줄로 고정 */
.drawer-item a .txt {
  flex: 1;
  min-width: 0;
  white-space: nowrap;
}

/* 오른쪽 숫자 배지: 연한 회색 작은 숫자 */
.count-badge {
  flex-shrink: 0;
  margin-left: 8px;
  font-size: 14px;
  color: #b7b7b7;
  font-style: normal;
}

/* 선택된 항목 강조 (있다면) */
.drawer-item.is-active a {
  background: #ffe9e9;
  border-left: 4px solid #e60000;
  padding-left: 18px; /* 왼쪽 패딩만 줄여서 맞춤 */
  font-weight: 700;
}

/* SNS 푸터 영역 */
.drawer__footer {
  margin-top: auto;
  padding: 10px 18px 14px;
  border-top: 1px solid #f3f3f3;
}
.drawer__footer ul.drawer-sns {
  display: flex;
  gap: 18px;
  list-style: none;
  margin: 0;
  padding: 0;
  font-size: 24px;
}
.drawer__footer ul.drawer-sns li {
  display: inline-block;
}
.drawer__footer ul.drawer-sns a {
  color: #333;
}

/* 드로어 배경 딤 */
.drawer-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 1100;
}
.drawer-backdrop[hidden] {
  display: none;
}
</style>

<aside id="categoryDrawer" class="drawer" aria-hidden="true" tabindex="-1">
  <div class="drawer__head">
    <strong>카테고리</strong>
    <button type="button" class="drawer-close" aria-label="닫기">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>
  <nav class="drawer__body">
    <ul class="drawer-list" id="drawerList"></ul>
  </nav>
    <div class="drawer__footer">
      <ul class="drawer-sns">
        <li>
          <a href="https://www.youtube.com/" title="YouTube" target="_blank" rel="noopener noreferrer">
            <img src="<?= $BASE ?>/img/icons/youtube.png" alt="YouTube" width="24" height="24" />
          </a>
        </li>
        <li>
          <a href="https://www.facebook.com/" title="Facebook" target="_blank" rel="noopener noreferrer">
            <img src="<?= $BASE ?>/img/icons/facebook.png" alt="Facebook" width="24" height="24" />
          </a>
        </li>
        <li>
          <a href="https://www.instagram.com/" title="Instagram" target="_blank" rel="noopener noreferrer">
            <img src="<?= $BASE ?>/img/icons/instagram.png" alt="Instagram" width="24" height="24" />
          </a>
        </li>
        <li>
          <a href="https://twitter.com/" title="Twitter" target="_blank" rel="noopener noreferrer" class="sns-twitter">
            <img src="<?= $BASE ?>/img/icons/X.png" alt="Twitter" width="24" height="24" />
          </a>
        </li>
      </ul>
    </div>
</aside>
<div class="drawer-backdrop" hidden></div>

<script>
/* ========= 카테고리 드로어(헤더 아래 좌측 슬라이드) =========
 * - .open-drawer 클릭 시 열기
 * - data-cats 있으면 해당 데이터로 목록 구성, 없으면 메인 메뉴 복사
 * - 현재 페이지/활성 탭은 .is-active 강조
 * - 헤더 높이를 CSS 변수(--header-h)에 반영해 항상 헤더 바로 아래에 붙음
 */
(function () {
  const drawer     = document.getElementById('categoryDrawer');
  const closeBtn   = drawer.querySelector('.drawer-close');
  const backdrop   = document.querySelector('.drawer-backdrop');
  const listTarget = document.getElementById('drawerList');

  // 헤더 높이 동기화 → 드로어 top/백드롭 top 계산에 사용
  function syncHeaderHeight() {
    const header = document.getElementById('header_wrapper');
    if (!header) return;
    const h = header.getBoundingClientRect().height;
    document.documentElement.style.setProperty('--header-h', h + 'px');
  }
  syncHeaderHeight();
  window.addEventListener('resize', syncHeaderHeight);

  // 숫자 배지 HTML
  function badgeHTML(n) {
    return (n !== undefined && n !== null && n !== '')
      ? `<em class="count-badge">(${n})</em>`
      : '';
  }

  // 현재 URL(경로 + 쿼리)와 항목의 URL(경로 + 쿼리) 비교
  function isActive(href) {
    try {
      const currentURL = location.pathname + location.search;
      const itemURLObj = new URL(href, location.origin);
      const itemURL = itemURLObj.pathname + itemURLObj.search;
      return currentURL === itemURL;
    } catch (_) {
      return false;
    }
  }

  // 목록 구성: data-cats 기반
  function buildFromData(cats) {
    listTarget.innerHTML = '';
    cats.forEach((item) => {
      const li = document.createElement('li');
      li.className = 'drawer-item';

      let is_active = false;

      // 1) URL 완전 일치
      if (isActive(item.href)) {
        is_active = true;
      }

      // 2) product.php 에서 '전체 카테고리' 처리
      const isCatAllLink   = item.href.includes('cat=all');
      const isProductPage  = location.pathname.includes('product.php');
      const hasNoCatQuery  = !location.search.includes('cat=');

      if (isCatAllLink && isProductPage && (location.search.includes('cat=all') || hasNoCatQuery)) {
        is_active = true;
      }

      if (is_active) {
        li.classList.add('is-active');
      }

      li.innerHTML = `
        <a href="${item.href}">
          <span class="txt">${item.name}</span>
          ${badgeHTML(item.count)}
        </a>
      `;
      listTarget.appendChild(li);
    });
  }

  // 목록 구성: 메인 메뉴 복사(백업)
  function buildFromMainMenu() {
    listTarget.innerHTML = '';
    document.querySelectorAll('.main_menu_bar a').forEach(a => {
      if (a.classList.contains('open-drawer')) return; // 트리거 제외
      const li = document.createElement('li');
      li.className = 'drawer-item';

      if (a.parentElement.classList.contains('is-active') || isActive(a.href)) {
        li.classList.add('is-active');
      }

      li.innerHTML = `
        <a href="${a.getAttribute('href') || '#'}">
          <span class="txt">${a.textContent.trim()}</span>
        </a>
      `;
      listTarget.appendChild(li);
    });
  }

  // 열기
  function openDrawer(triggerEl) {
    let data = null;
    try {
      data = triggerEl?.dataset?.cats ? JSON.parse(triggerEl.dataset.cats) : null;
    } catch (e) {
      console.warn('Invalid data-cats JSON:', e);
    }

    if (Array.isArray(data) && data.length) {
      buildFromData(data);
    } else {
      buildFromMainMenu();
    }

    drawer.classList.add('is-open');
    backdrop.hidden = false;
    document.body.classList.add('is-drawer-open');
    drawer.setAttribute('aria-hidden', 'false');
    if (triggerEl) triggerEl.setAttribute('aria-expanded', 'true');

    // 드로어 내부 첫 요소로 포커스 이동
    drawer.querySelector('button, a, input')?.focus();
  }

  // 닫기
  function closeDrawer() {
    drawer.classList.remove('is-open');
    backdrop.hidden = true;
    document.body.classList.remove('is-drawer-open');
    drawer.setAttribute('aria-hidden', 'true');
    const trigger = document.querySelector('.open-drawer[aria-expanded="true"]');
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
      trigger.focus();
    }
  }

  // 이벤트 바인딩
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.open-drawer');
    if (!btn) return;
    e.preventDefault();
    openDrawer(btn);
  });

  closeBtn.addEventListener('click', closeDrawer);
  backdrop.addEventListener('click', closeDrawer);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });
})();
</script>
