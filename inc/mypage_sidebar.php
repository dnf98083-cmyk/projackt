<?php
// 현재 페이지의 파일명을 가져와서 active 클래스를 지정하기 위함
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="mypage-sidebar">
    <div class="sidebar-title" style="font-size: 20px; font-weight: bold; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #333;">마이페이지</div>
    
    <h3>나의 쇼핑 정보</h3>
    <ul>
        <li><a href="my-page_order.php" class="<?= $current_page == 'my-page_order.php' ? 'active' : '' ?>">주문배송 조회</a></li>
        <li><a href="#" onclick="alert('준비중입니다.'); return false;">취소/교환/반품 내역</a></li>
    </ul>

    <h3>나의 혜택 관리</h3>
    <ul>
        <li><a href="#" onclick="alert('준비중입니다.'); return false;">쿠폰함</a></li>
        <li><a href="#" onclick="alert('준비중입니다.'); return false;">포인트</a></li>
    </ul>

    <h3>나의 활동 관리</h3>
    <ul>
        <li><a href="my-page_reviews.php" class="<?= $current_page == 'my-page_reviews.php' ? 'active' : '' ?>">리뷰 관리</a></li>
        <li><a href="customer_center.php" class="<?= $current_page == 'customer_center.php' ? 'active' : '' ?>">1:1 문의</a></li>
    </ul>

    <h3>나의 상품 판매</h3>
    <ul>
        <li><a href="my-page_product_add.php" class="<?= $current_page == 'my-page_product_add.php' ? 'active' : '' ?>">상품 등록</a></li>
        <li><a href="my-page_product_list.php" class="<?= $current_page == 'my-page_product_list.php' ? 'active' : '' ?>">등록 상품 관리</a></li>
    </ul>

    <h3>나의 정보 관리</h3>
    <ul>
        <li><a href="my-page_mem_info.php" class="<?= $current_page == 'my-page_mem_info.php' ? 'active' : '' ?>">회원정보 수정</a></li>
        <li><a href="#" onclick="alert('준비중입니다.'); return false;">배송지 관리</a></li>
    </ul>
</aside>

<style>
    .mypage-sidebar h3 {
        margin-top: 20px;
        margin-bottom: 10px;
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }
    .mypage-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .mypage-sidebar li {
        margin-bottom: 8px;
    }
    .mypage-sidebar li a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        display: block; /* 클릭 영역 확대 */
        padding: 2px 0;
    }
    .mypage-sidebar li a:hover, .mypage-sidebar li a.active {
        color: #e60000;
        font-weight: bold;
    }
</style>
