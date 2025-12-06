<?php
// cart_list.php
require_once("inc/db.php");
require_once("inc/session.php");

// 1) 로그인 체크
$user_id = $_SESSION['member_id'] ?? null;
if (empty($user_id)) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='login.php';</script>";
    exit;
}

// 2) 현재 로그인한 사용자의 장바구니 목록 조회
$sql = "
    SELECT 
        c.cart_id,
        c.content_code,
        c.content_options,
        c.content_amount,
        p.content_name,
        p.content_img,
        p.content_price
    FROM cart c
    JOIN contents p ON c.content_code = p.content_code
    WHERE c.user_id = ?
    ORDER BY c.cart_id DESC
";
$cart_items = db_select($sql, [$user_id]);

// 3) 합계 계산
$sub_total = 0;
foreach ($cart_items as $item) {
    $sub_total += $item['content_price'] * $item['content_amount'];
}

// 배송비 규칙: 4만원 이상 무료, 미만 3,000원
$free_limit   = 40000;
$shipping_fee = ($sub_total >= $free_limit || $sub_total == 0) ? 0 : 3000;
$total_price  = $sub_total + $shipping_fee;
$left_for_free = max(0, $free_limit - $sub_total);

// css 캐시 버스터
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
    <title>장바구니 - Meal Kitchen</title>
    <link rel="stylesheet" href="<?= bust('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body>
<?php require_once("inc/header.php"); ?>

<main class="main_wrapper">
    <div class="cart-wrapper">

        <h2 class="cart-title">장바구니</h2>

        <?php if (empty($cart_items)): ?>
            <p class="cart-empty-text">장바구니에 담긴 상품이 없습니다.</p>
        <?php else: ?>

            <!-- 상단 전체선택 / 삭제 -->
            <div class="cart-top-bar">
                <div class="cart-tabs">
                    <label class="cart-tab active">
                        <input type="checkbox" id="cartCheckAll" checked>
                        <span class="tab-text">전체체크</span>
                    </label>
                    <button type="button" class="cart-tab" id="btnDeleteSelected">
                        <span class="tab-text">선택삭제</span>
                    </button>
                </div>
            </div>

            <!-- 장바구니 상품 리스트 -->
            <ul class="cart-item-list">
                <?php foreach ($cart_items as $row): 
                    $unit  = (int)$row['content_price'];
                    $qty   = (int)$row['content_amount'];
                    $line  = $unit * $qty;
                ?>
                <li class="cart-item" data-cart-id="<?= htmlspecialchars($row['cart_id']) ?>">
                    <div class="cart-item-check">
                        <input type="checkbox" class="cart-check" checked>
                    </div>

                    <div class="cart-item-thumb">
                        <img src="<?= htmlspecialchars($row['content_img']) ?>" alt="<?= htmlspecialchars($row['content_name']) ?>">
                    </div>

                    <div class="cart-item-info">
                        <div class="cart-item-brand">밀키친</div>
                        <div class="cart-item-name">
                            ★스비기한입밀떡★ | <?= htmlspecialchars($row['content_name']) ?>
                        </div>
                        <div class="cart-item-price-single">
                            <?= number_format($unit) ?>원
                        </div>
                    </div>

                    <div class="cart-item-qty"
                         data-unit-price="<?= $unit ?>">
                        <button type="button" class="qty-btn minus">-</button>
                        <input type="number" class="qty-input" min="1" value="<?= $qty ?>">
                        <button type="button" class="qty-btn plus">+</button>
                    </div>

                    <div class="cart-item-price">
                        <span class="line-price"><?= number_format($line) ?></span>원
                    </div>

                    <button type="button" class="cart-item-remove" data-cart-id="<?= htmlspecialchars($row['cart_id']) ?>">
                        ×
                    </button>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- 배송비 / 합계 영역 -->
            <div class="cart-summary-box">
                <?php if ($left_for_free > 0): ?>
                <div class="ship-info-row">
                    <div class="ship-left">
                        <span class="ship-label">배송비</span>
                        <span class="ship-fee">
                            <?= number_format($shipping_fee) ?>원
                        </span>
                    </div>
                    <div class="ship-right">
                        <span class="ship-desc">
                            <?= number_format($free_limit) ?>원 이상 무료배송
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <div class="cart-total-row">
                    <div class="cart-total-item">
                        <span class="total-label">배송비</span>
                        <strong class="total-value" id="cartShipFee"><?= number_format($shipping_fee) ?></strong>원
                    </div>
                    <div class="cart-total-item">
                        <span class="total-label">상품가격</span>
                        <strong class="total-value orange" id="cartSubTotal"><?= number_format($sub_total) ?></strong>원
                    </div>
                    <div class="cart-total-item">
                        <span class="total-label">더 담으면 무료배송</span>
                        <strong class="total-value" id="cartLeftForFree"><?= number_format($left_for_free) ?></strong>원
                    </div>
                    <div class="cart-total-item equals">
                        <span>=</span>
                    </div>
                    <div class="cart-total-item final">
                        <span class="total-label">합계</span>
                        <strong class="total-value large" id="cartTotal"><?= number_format($total_price) ?></strong>원
                    </div>
                </div>
            </div>

            <!-- 하단 버튼 -->
            <div class="cart-bottom-actions">
                <button type="button" class="btn-cart-secondary"
                        onclick="location.href='best.php'">
                    쇼핑 계속하기
                </button>
                <button type="button" class="btn-cart-primary" onclick="orderSelected()">
                    주문하기
                </button>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php require_once("inc/footer.php"); ?>

<style>
/* ===== 장바구니 기본 레이아웃 ===== */
.cart-wrapper{
    max-width: 640px;
    margin: 20px auto 80px;
    padding: 0 20px;
}
.cart-title{
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
}
.cart-empty-text{
    padding: 60px 0;
    text-align:center;
    color:#777;
}

/* 상단 탭 바 */
.cart-top-bar{
    margin-bottom: 0;
    border-bottom: 1px solid #e0e0e0;
}
.cart-tabs{
    display: flex;
    gap: 0;
}
.cart-tab{
    flex: 1;
    padding: 12px 0;
    background: #f5f5f5;
    border: none;
    border-bottom: 2px solid transparent;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.cart-tab.active{
    background: white;
    border-bottom: 2px solid #e60000;
}
.cart-tab input[type="checkbox"]{
    margin: 0;
}
.tab-text{
    color: #333;
}

/* 리스트 */
.cart-item-list{
    list-style:none;
    margin:0;
    padding:0;
}
.cart-item{
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
    position: relative;
}
.cart-item-check{
    flex-shrink: 0;
}
.cart-item-thumb{
    flex-shrink: 0;
}
.cart-item-thumb img{
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
}
.cart-item-info{
    flex: 1;
    min-width: 0;
}
.cart-item-brand{
    font-size: 11px;
    color: #999;
    margin-bottom: 4px;
}
.cart-item-name{
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 6px;
    line-height: 1.4;
}
.cart-item-price-single{
    font-size: 12px;
    color: #666;
}
.cart-item-qty{
    display: flex;
    align-items: center;
    gap: 0;
    position: absolute;
    bottom: 16px;
    right: 40px;
}
.qty-btn{
    width: 28px;
    height: 28px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.qty-input{
    width: 40px;
    height: 28px;
    text-align: center;
    border: 1px solid #ddd;
    border-left: none;
    border-right: none;
}
.cart-item-price{
    position: absolute;
    top: 16px;
    right: 40px;
    font-weight: 700;
    font-size: 15px;
}
.cart-item-remove{
    position: absolute;
    top: 16px;
    right: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 20px;
    color: #999;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* 합계 영역 */
.cart-summary-box{
    margin-top: 20px;
    padding: 16px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}
.ship-info-row{
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f0f0f0;
}
.ship-label{ 
    font-weight: 600;
    color: #333;
}
.ship-fee{ 
    margin-left: 8px;
    color: #666;
}
.ship-desc{ 
    color: #999;
    font-size: 12px;
}

/* 총합 */
.cart-total-row{
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
}
.cart-total-item{
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.cart-total-item.equals{
    font-size: 16px;
    font-weight: 700;
}
.cart-total-item.final{
    background: #fff5f0;
    padding: 8px 12px;
    border-radius: 6px;
}
.total-label{
    font-size: 11px;
    color: #666;
}
.total-value{
    font-size: 16px;
    font-weight: 700;
    color: #333;
}
.total-value.orange{
    color: #e60000;
}
.total-value.large{
    font-size: 18px;
    color: #e60000;
}

/* 하단 버튼 */
.cart-bottom-actions{
    margin-top: 20px;
    display: flex;
    gap: 10px;
}
.btn-cart-secondary{
    flex: 1;
    padding: 14px 0;
    background: #fff;
    border: 1px solid #ddd;
    cursor: pointer;
    font-size: 14px;
    border-radius: 6px;
}
.btn-cart-primary{
    flex: 2;
    padding: 14px 0;
    background: #e60000;
    color: #fff;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    border-radius: 6px;
}
</style>

<script>
// JS: 전체선택 / 해제 + 수량 변경 시 합계 업데이트 [v20251126154004]
document.addEventListener('DOMContentLoaded', () => {
    const checkAll = document.getElementById('cartCheckAll');
    const deleteBtn = document.getElementById('btnDeleteSelected');
    const checks = document.querySelectorAll('.cart-check');
    const items = document.querySelectorAll('.cart-item');

    const subTotalEl = document.getElementById('cartSubTotal');
    const shipFeeEl  = document.getElementById('cartShipFee');
    const totalEl    = document.getElementById('cartTotal');
    const leftForFreeEl = document.getElementById('cartLeftForFree');

    const FREE_LIMIT = <?= $free_limit ?>;

    function recalc() {
        let sub = 0;
        items.forEach(item => {
            const checked = item.querySelector('.cart-check').checked;
            if (!checked) return;

            const qtyBox = item.querySelector('.cart-item-qty');
            const unit   = parseInt(qtyBox.dataset.unitPrice, 10) || 0;
            const qty    = parseInt(qtyBox.querySelector('.qty-input').value, 10) || 1;

            sub += unit * qty;
        });

        let ship = 0;
        if (sub > 0 && sub < FREE_LIMIT) ship = 3000;

        const total = sub + ship;
        const leftForFree = Math.max(0, FREE_LIMIT - sub);

        subTotalEl.textContent = sub.toLocaleString();
        shipFeeEl.textContent  = ship.toLocaleString();
        totalEl.textContent    = total.toLocaleString();
        if (leftForFreeEl) leftForFreeEl.textContent = leftForFree.toLocaleString();
    }

    // 전체선택
    if (checkAll) {
        checkAll.addEventListener('change', () => {
            checks.forEach(c => c.checked = checkAll.checked);
            recalc();
        });
    }
    
    // 선택삭제
    if (deleteBtn) {
        deleteBtn.addEventListener('click', () => {
            const selectedIds = [];
            items.forEach(item => {
                const checked = item.querySelector('.cart-check').checked;
                if (checked) {
                    selectedIds.push(item.dataset.cartId);
                }
            });
            
            if (selectedIds.length === 0) {
                alert('삭제할 상품을 선택해주세요.');
                return;
            }
            
            if (confirm(`선택한 ${selectedIds.length}개 상품을 삭제하시겠습니까?`)) {
                // 삭제 요청
                const form = new FormData();
                form.append('cart_ids', selectedIds.join(','));
                
                fetch('cart_delete.php', {
                    method: 'POST',
                    body: form
                })
                .then(r => {
                    // 응답 텍스트 확인
                    return r.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('서버 응답 형식 오류');
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        // DOM에서 삭제된 항목들 제거
                        selectedIds.forEach(id => {
                            const item = document.querySelector(`.cart-item[data-cart-id="${id}"]`);
                            if (item) {
                                item.remove();
                            }
                        });
                        
                        // 전체선택 체크박스 해제
                        if (checkAll) checkAll.checked = false;
                        
                        // 금액 재계산
                        recalc();
                        
                        alert(`선택한 ${selectedIds.length}개 상품이 삭제되었습니다.`);
                        location.reload();
                    } else {
                        let errorMsg = '삭제 중 오류가 발생했습니다.';
                        if (data.error === 'NOT_AUTH') {
                            errorMsg = '로그인이 필요합니다.';
                        } else if (data.error === 'NO_IDS') {
                            errorMsg = '삭제할 상품이 선택되지 않았습니다.';
                        } else if (data.error === 'DB_ERROR') {
                            errorMsg = '데이터베이스 오류가 발생했습니다.';
                        } else if (data.error) {
                            errorMsg = '오류: ' + data.error;
                        }
                        alert(errorMsg);
                    }
                })
                .catch(err => {
                    console.error('삭제 요청 실패:', err);
                    alert('서버와의 통신 중 오류가 발생했습니다.\n잠시 후 다시 시도해주세요.');
                });
            }
        });
    }
    
    checks.forEach(c => c.addEventListener('change', recalc));

    // 개별 삭제 버튼
    document.querySelectorAll('.cart-item-remove').forEach(btn => {
        btn.addEventListener('click', () => {
            const cartId = btn.dataset.cartId;
            if (confirm('이 상품을 삭제하시겠습니까?')) {
                const form = new FormData();
                form.append('cart_ids', cartId);
                
                fetch('cart_delete.php', {
                    method: 'POST',
                    body: form
                })
                .then(r => {
                    // 응답 텍스트 확인
                    return r.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('서버 응답 형식 오류');
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        // DOM에서 해당 항목 제거
                        const item = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
                        if (item) {
                            item.remove();
                        }
                        
                        // 전체선택 체크박스 해제
                        if (checkAll) checkAll.checked = false;
                        
                        // 금액 재계산
                        recalc();
                        
                        alert('상품이 삭제되었습니다.');
                        location.reload();
                    } else {
                        let errorMsg = '삭제 중 오류가 발생했습니다.';
                        if (data.error === 'NOT_AUTH') {
                            errorMsg = '로그인이 필요합니다.';
                        } else if (data.error === 'NO_IDS') {
                            errorMsg = '삭제할 상품이 선택되지 않았습니다.';
                        } else if (data.error === 'DB_ERROR') {
                            errorMsg = '데이터베이스 오류가 발생했습니다.';
                        } else if (data.error) {
                            errorMsg = '오류: ' + data.error;
                        }
                        alert(errorMsg);
                    }
                })
                .catch(err => {
                    console.error('삭제 요청 실패:', err);
                    alert('서버와의 통신 중 오류가 발생했습니다.\n잠시 후 다시 시도해주세요.');
                });
            }
        });
    });

    // 수량 버튼
    document.querySelectorAll('.cart-item-qty').forEach(box => {
        const minus = box.querySelector('.minus');
        const plus  = box.querySelector('.plus');
        const input = box.querySelector('.qty-input');
        const linePriceEl = box.closest('.cart-item').querySelector('.line-price');
        const unit = parseInt(box.dataset.unitPrice, 10) || 0;

        function updateLine() {
            let val = parseInt(input.value, 10) || 1;
            if (val < 1) val = 1;
            input.value = val;
            const line = unit * val;
            linePriceEl.textContent = line.toLocaleString();
            recalc();
        }

        minus.addEventListener('click', () => {
            input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
            updateLine();
        });
        plus.addEventListener('click', () => {
            input.value = (parseInt(input.value, 10) || 1) + 1;
            updateLine();
        });
        input.addEventListener('change', updateLine);
    });

    recalc();
});

// 주문하기 함수
function orderSelected() {
    const checkboxes = document.querySelectorAll('.cart-check:checked');
    if (checkboxes.length === 0) {
        alert('주문할 상품을 선택해주세요.');
        return;
    }

    const selectedIds = [];
    checkboxes.forEach(cb => {
        const item = cb.closest('.cart-item');
        if (item) {
            selectedIds.push(item.dataset.cartId);
        }
    });

    if (selectedIds.length === 0) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'payment.php';

    const modeInput = document.createElement('input');
    modeInput.type = 'hidden';
    modeInput.name = 'mode';
    modeInput.value = 'cart';
    form.appendChild(modeInput);

    const idsInput = document.createElement('input');
    idsInput.type = 'hidden';
    idsInput.name = 'cart_ids';
    idsInput.value = selectedIds.join(',');
    form.appendChild(idsInput);

    document.body.appendChild(form);
    form.submit();
}
</script>

</body>
</html>
