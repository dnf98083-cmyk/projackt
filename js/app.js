// js/app.js

// ----------------------------------------------------
// 1. 장바구니 체크박스 관리 로직
// ----------------------------------------------------

/**
 * 전체 체크박스 상태 변경 시 모든 개별 체크박스 상태를 변경합니다.
 */
function checkAll() {
    const checkAllBox = document.querySelector('.check_all');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:not(.check_all)');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = checkAllBox.checked;
    });
}

/**
 * 개별 체크박스 상태 변경 시 전체 체크박스 상태를 업데이트합니다.
 */
function check_all_check() {
    const checkAllBox = document.querySelector('.check_all');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:not(.check_all)');
    
    let allChecked = true;
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            allChecked = false;
        }
    });

    checkAllBox.checked = allChecked;
}


// ----------------------------------------------------
// 2. 장바구니 삭제 로직 (cart_delete.php 연동)
// ----------------------------------------------------

/**
 * 장바구니에서 상품을 삭제하고 서버로 전송합니다.
 * @param {string} type - 'selected' (선택 삭제) 또는 'all' (전체 삭제)
 */
function deleteCartItems(type) {
    let cartIds = [];

    if (type === 'all') {
        if (!confirm('장바구니의 모든 상품을 삭제하시겠습니까?')) {
            return;
        }
        // 전체 삭제의 경우 화면에 있는 모든 체크박스의 값을 가져오거나, 
        // 별도의 'all' 플래그를 서버에 보낼 수도 있지만, 
        // 여기서는 화면에 있는 모든 ID를 수집해서 보내는 방식으로 처리합니다.
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        checkboxes.forEach(cb => cartIds.push(cb.value));
    } else {
        // 선택 삭제
        const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
        checkboxes.forEach(cb => cartIds.push(cb.value));

        if (cartIds.length === 0) {
            alert('삭제할 상품을 하나 이상 선택해주세요.');
            return;
        }
        
        if (!confirm('선택한 상품을 삭제하시겠습니까?')) {
            return;
        }
    }

    // 서버로 전송 (AJAX)
    const formData = new FormData();
    formData.append('cart_ids', cartIds.join(','));

    fetch('cart_delete.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('삭제 성공했습니다.');
            location.reload(); // 페이지 새로고침하여 목록 최신화
        } else {
            alert('삭제 실패: ' + (data.error || '알 수 없는 오류'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('삭제 중 오류가 발생했습니다.');
    });
}


// ----------------------------------------------------
// 3. 장바구니 주문 로직 (pay.submit.php 연동)
// ----------------------------------------------------

/**
 * 선택된 상품 또는 전체 상품을 주문하기 위해 폼을 전송합니다.
 * @param {string} type - 'all' (전체 주문) 또는 'selected' (선택 주문)
 */
function submitOrder(type) {
    const cartForm = document.querySelector('form[name="cart_form"]');
    
    if (type === 'selected') {
        const checkboxes = document.querySelectorAll('form[name="cart_form"] input[type="checkbox"]:not(.check_all)');
        let selectedCount = 0;
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
            }
        });

        if (selectedCount === 0) {
            alert('주문할 상품을 하나 이상 선택해주세요.');
            return;
        }

        // 서버에서 선택된 항목만 처리할 수 있도록 hidden 필드를 추가 (임시 플래그)
        const selectionFlag = document.createElement('input');
        selectionFlag.type = 'hidden';
        selectionFlag.name = 'order_type';
        selectionFlag.value = 'selected';
        cartForm.appendChild(selectionFlag);

    } else if (type === 'all') {
        const selectionFlag = document.createElement('input');
        selectionFlag.type = 'hidden';
        selectionFlag.name = 'order_type';
        selectionFlag.value = 'all';
        cartForm.appendChild(selectionFlag);
    }
    
    // 폼 전송
    cartForm.submit();
}


// ----------------------------------------------------
// ✅ 4. 상품 정렬 및 목록 개수 변경 로직 (product.php 연동)
// ----------------------------------------------------

/**
 * 정렬 기준(Sort)이 변경될 때 페이지를 이동합니다.
 * product.php의 <select class="sort_select">와 연결됩니다.
 * @param {string} url - 정렬 기준이 포함된 완전한 URL
 */
function updateSort(url) {
    // product.php의 <select> 태그는 이미 URL 전체를 value로 전달합니다.
    if (url) {
        window.location.href = url;
    }
}

/**
 * 목록 표시 개수(Limit)가 변경될 때 페이지를 이동합니다.
 * product.php의 <select class="limit_select">와 연결됩니다.
 * @param {string} url - 목록 개수(limit)가 포함된 완전한 URL
 */
function updateLimit(url) {
    // product.php의 <select> 태그는 이미 URL 전체를 value로 전달합니다.
    if (url) {
        window.location.href = url;
    }
}

// ----------------------------------------------------
// (기존의 hot_issue.js, member.js 관련 함수가 이 파일에 있다면 여기에 위치합니다)
// ----------------------------------------------------