// js/sort.js

// 버튼 요소
const SortSales = document.querySelector('.sort_nav01'); // 판매순
const SortHighPrice = document.querySelector('.sort_nav02'); // 높은가격순
const SortLowPrice = document.querySelector('.sort_nav03'); // 낮은가격순
const SortRating = document.querySelector('.sort_nav04'); // 평점순

// 정렬 타입 맵핑 (서버의 ?sort 파라미터 값과 일치시킴)
const sortMap = {
    'sort_nav01': 'sales',
    'sort_nav02': 'price_h',
    'sort_nav03': 'price_l',
    'sort_nav04': 'recommend'
};

// --------------------------------------------------------
// 1. 스타일 업데이트 함수
// --------------------------------------------------------
function updateSortStyle(activeClass) {
    // 모든 버튼/링크에서 활성 클래스 제거
    document.querySelectorAll('.sort_nav_wrapper a, .sort_nav_wrapper button, .sort_nav_wrapper div').forEach(el => {
        el.classList.remove('is-active');
    });

    // 선택된 요소에 활성 클래스 추가 (CSS에서 스타일을 처리)
    const activeButton = document.querySelector('.' + activeClass);
    if (activeButton) {
        activeButton.classList.add('is-active');
    }
}

// --------------------------------------------------------
// 2. 정렬 기능 함수 (페이지 이동)
// --------------------------------------------------------
function handleSortClick(event) {
    const clickedButton = event.currentTarget;
    const buttonClasses = Array.from(clickedButton.classList);
    let sortType = null;
    
    // 버튼 클래스를 순회하며 정렬 타입 획득
    for (const className of buttonClasses) {
        if (sortMap[className]) {
            sortType = sortMap[className];
            break;
        }
    }

    if (sortType) {
        updateSortStyle(buttonClasses.find(c => sortMap[c]));
        
        // 현재 URL의 경로를 가져옵니다. (index.php 또는 product.php)
        const currentPath = window.location.pathname.split('/').pop();
        
        // 쿼리 파라미터 업데이트 (기존 cat 파라미터는 유지)
        const url = new URL(window.location.href);
        
        // 기존의 'sort' 파라미터를 새 값으로 덮어씁니다.
        url.searchParams.set('sort', sortType);
        
        // 페이지 이동 (새로고침)
        window.location.href = url.href;
    }
}

// --------------------------------------------------------
// 3. 이벤트 리스너 바인딩 및 초기 활성 상태 설정
// --------------------------------------------------------
[SortSales, SortHighPrice, SortLowPrice, SortRating].forEach(button => {
    if (button) {
        // 기존의 배경색 변경 이벤트 대신, 페이지 이동 로직을 실행하도록 변경
        button.addEventListener('click', handleSortClick);
    }
});

// 페이지 로드 시 현재 정렬 상태를 반영하여 스타일 설정
document.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    const currentSort = url.searchParams.get('sort');
    
    if (currentSort) {
        // 현재 정렬 타입에 해당하는 버튼 클래스를 찾습니다.
        let activeClass = null;
        for (const [className, type] of Object.entries(sortMap)) {
            if (type === currentSort) {
                activeClass = className;
                break;
            }
        }
        if (activeClass) {
            updateSortStyle(activeClass);
        }
    } else {
        // 기본값은 'sales' -> 판매순
        updateSortStyle('sort_nav01');
    }
});