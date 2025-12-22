// js/option.js — 옵션 1개 전용 완성본

const OPTION_STATE = {
    option: null,
    amount: 1,
    basePrice: 0
};

// 페이지 로드 후 실행
document.addEventListener("DOMContentLoaded", () => {

    // 기본 가격 가져오기
    const priceEl = document.querySelector('.item-price-display');
    if (priceEl) {
        OPTION_STATE.basePrice = parseInt(priceEl.textContent.replace(/[^0-9]/g,'')) || 0;
    }

    // 옵션 선택 이벤트
    const optionSelect = document.querySelector('.option-select-box');
    optionSelect.addEventListener("change", () => {
        OPTION_STATE.option = optionSelect.value;
        updateTotalPrice();
    });

    // 수량 조절 버튼
    const qtyInput = document.querySelector('.qty-input');
    const minus = document.querySelector('.qty-btn.minus');
    const plus = document.querySelector('.qty-btn.plus');

    minus.addEventListener("click", () => {
        OPTION_STATE.amount = Math.max(1, OPTION_STATE.amount - 1);
        qtyInput.value = OPTION_STATE.amount;
        updateTotalPrice();
    });

    plus.addEventListener("click", () => {
        OPTION_STATE.amount++;
        qtyInput.value = OPTION_STATE.amount;
        updateTotalPrice();
    });

    qtyInput.addEventListener("change", () => {
        OPTION_STATE.amount = Math.max(1, parseInt(qtyInput.value) || 1);
        updateTotalPrice();
    });

    updateTotalPrice();
});


// 총 가격 업데이트
function updateTotalPrice() {
    const total = OPTION_STATE.basePrice * OPTION_STATE.amount;

    const summary = document.querySelector('.summary-amount');
    const itemPrice = document.querySelector('.item-price-display');

    if(summary) summary.textContent = total.toLocaleString() + "원";
    if(itemPrice) itemPrice.textContent = total.toLocaleString() + "원";

    // ✅ 선택된 옵션 요약 박스 업데이트
    const box = document.getElementById('selectedOptionBox');
    const nameEl = document.getElementById('selectedOptionName');
    const qtyEl = document.getElementById('selectedOptionQty');
    const priceEl = document.getElementById('selectedOptionPrice');

    const hasValidOption = OPTION_STATE.option 
        && OPTION_STATE.option !== "" 
        && OPTION_STATE.option !== "옵션 선택";

    if (box) {
        if (hasValidOption) {
            box.style.display = 'block';
            if (nameEl)  nameEl.textContent  = OPTION_STATE.option;
            if (qtyEl)   qtyEl.textContent   = OPTION_STATE.amount + "개";
            if (priceEl) priceEl.textContent = total.toLocaleString() + "원";
        } else {
            box.style.display = 'none';
        }
    }
}


// 장바구니 담기 버튼
function cart_insert() {
    const optionValue = OPTION_STATE.option;
    const qty = OPTION_STATE.amount;

    if (!optionValue || optionValue === "" || optionValue === "옵션 선택") {
        alert("옵션을 선택해주세요.");
        return;
    }

    if (qty < 1) {
        alert("수량은 1개 이상이어야 합니다.");
        return;
    }

    // hidden 필드에 값 넣기
    document.getElementById('content_options').value = optionValue;
    document.getElementById('content_amount').value = qty;

    document.forms["contents_form"].submit();
}


// 바로구매 검증
function validateOptions() {
    const optionValue = OPTION_STATE.option;
    const qty = OPTION_STATE.amount;

    if (!optionValue || optionValue === "" || optionValue === "옵션 선택") {
        alert("옵션을 선택해주세요.");
        return false;
    }
    if (qty < 1) {
        alert("수량은 1개 이상이어야 합니다.");
        return false;
    }

    document.getElementById('content_options').value = optionValue;
    document.getElementById('content_amount').value = qty;

    return true;
}
