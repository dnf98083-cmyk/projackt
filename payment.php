<?php
// payment.php - 주문/결제 통합 페이지
require_once("inc/db.php");
require_once("inc/session.php");

// 1. 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];
$member_data = db_select("select * from members where id= ?", array($member_id));

if (empty($member_data)) {
    // 세션은 있지만 DB에 회원이 없는 경우 (삭제된 회원 등)
    session_destroy();
    echo "<script>alert('회원 정보를 찾을 수 없습니다. 다시 로그인해주세요.'); location.href='login.php';</script>";
    exit;
}

$member = $member_data[0];

// 2. 주문 모드 확인 (cart: 장바구니, direct: 바로구매)
$mode = $_POST['mode'] ?? 'direct';

// 상품 목록을 담을 배열 초기화
$order_items = [];
$total_product_price = 0; // 총 상품 금액

// A. 장바구니에서 넘어온 경우
if ($mode === 'cart') {
    $cart_ids_str = $_POST['cart_ids'] ?? '';
    if (empty($cart_ids_str)) {
        echo "<script>alert('선택된 상품이 없습니다.'); history.back();</script>"; exit;
    }
    
    // DB에서 장바구니 정보 조회 (IN 절 사용을 위해 파싱)
    $cart_ids = explode(',', $cart_ids_str);
    // 보안을 위해 정수로 변환
    $cart_ids = array_map('intval', $cart_ids);
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));

    $sql = "
        SELECT c.content_name, c.content_img, c.content_price, 
               cart.content_options, cart.content_amount, cart.cart_id, c.content_code
        FROM cart 
        JOIN contents c ON cart.content_code = c.content_code
        WHERE cart.cart_id IN ($placeholders) AND cart.user_id = ?
    ";
    
    // 파라미터 병합 (cart_ids + member_id)
    $params = array_merge($cart_ids, [$member_id]);
    $results = db_select($sql, $params);

    foreach ($results as $row) {
        $price = $row['content_price'] * $row['content_amount'];
        $total_product_price += $price;
        
        $order_items[] = [
            'name' => $row['content_name'],
            'img' => $row['content_img'],
            'options' => $row['content_options'],
            'amount' => $row['content_amount'],
            'price' => $price,
            'content_code' => $row['content_code']
        ];
    }

// B. 상세페이지에서 바로구매로 넘어온 경우
} else {
    $content_code = $_POST['content_code'] ?? null;
    $options      = $_POST['content_options'] ?? '기본';
    $amount       = (int)($_POST['content_amount'] ?? 1);

    if (!$content_code) {
        echo "<script>alert('잘못된 접근입니다.'); history.back();</script>"; exit;
    }

    $product = db_select("select * from contents where content_code= ?", array($content_code))[0];
    
    $price = $product['content_price'] * $amount;
    $total_product_price += $price;

    $order_items[] = [
        'name' => $product['content_name'],
        'img' => $product['content_img'],
        'options' => $options,
        'amount' => $amount,
        'price' => $price,
        'content_code' => $content_code
    ];
}

// 3. 배송비 계산 (4만원 이상 무료)
$delivery_fee = ($total_product_price >= 40000) ? 0 : 3000;
$final_price = $total_product_price + $delivery_fee;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>주문/결제 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/pay.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
</head>
<body>
    <?php require_once("inc/header.php"); ?>

    <main class="payment-container">
        <h1>주문/결제</h1>

        <form action="order_create.php" method="POST" id="orderForm" onsubmit="return validatePayment()">
            <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">
            
            <?php if ($mode === 'cart'): ?>
                <input type="hidden" name="cart_ids" value="<?= htmlspecialchars($cart_ids_str) ?>">
            <?php else: ?>
                <input type="hidden" name="content_code" value="<?= $order_items[0]['content_code'] ?>">
                <input type="hidden" name="options" value="<?= htmlspecialchars($order_items[0]['options']) ?>">
                <input type="hidden" name="amount" value="<?= $order_items[0]['amount'] ?>">
            <?php endif; ?>
            
            <input type="hidden" name="total_price_hidden" id="total_price_hidden" value="<?= $final_price ?>">

            <div class="section-title">주문 상품 정보 (총 <?= count($order_items) ?>개)</div>
            <div style="border: 1px solid #eee; padding: 0 15px; border-radius: 8px;">
                <?php foreach ($order_items as $item): ?>
                <div class="order-item-box">
                    <img src="<?= htmlspecialchars($item['img']) ?>" alt="상품이미지">
                    <div class="order-item-info">
                        <div class="order-item-title"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="order-item-meta">
                            옵션: <?= htmlspecialchars($item['options']) ?> | 수량: <?= $item['amount'] ?>개
                        </div>
                    </div>
                    <div class="order-item-price"><?= number_format($item['price']) ?>원</div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="section-title">
                배송지 정보
                <button type="button" onclick="loadMemberInfo()" style="float: right; font-size: 12px; padding: 5px 10px; cursor:pointer;">내 정보 불러오기</button>
            </div>
            <table class="info-table">
                <tr><th>받는 분</th><td><input type="text" name="receiver_name" id="receiver_name" required></td></tr>
                <tr><th>연락처</th><td><input type="tel" name="receiver_phone" id="receiver_phone" required></td></tr>
                <tr><th>주소</th>
                    <td>
                        <div style="display:flex; gap:5px; margin-bottom:5px;">
                            <input type="text" name="zipcode" id="zipcode" style="width:100px;" readonly>
                            <button type="button" onclick="searchAddress()">주소검색</button>
                        </div>
                        <input type="text" name="address1" id="address1" readonly style="margin-bottom:5px;">
                        <input type="text" name="address2" id="address2" placeholder="상세주소">
                    </td>
                </tr>
                <tr><th>배송 메모</th>
                    <td>
                        <select name="delivery_memo" style="width:100%; padding:8px;">
                            <option value="">요청사항 선택</option>
                            <option value="문 앞에 놔주세요">문 앞에 놔주세요</option>
                            <option value="경비실에 맡겨주세요">경비실에 맡겨주세요</option>
                            <option value="직접 입력">직접 입력</option>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="section-title">할인 / 포인트</div>
            <table class="info-table">
                <tr><th>포인트 사용</th>
                    <td>
                        <input type="number" name="use_point" id="use_point" value="0" min="0" max="<?= $member['point'] ?? 0 ?>" 
                               style="width: 100px; text-align: right;" onchange="calculateTotal()"> P
                        <span style="font-size: 13px; color: #888;">(보유: <?= number_format($member['point'] ?? 0) ?> P)</span>
                        <button type="button" onclick="useAllPoints()" style="margin-left:5px;">전액사용</button>
                    </td>
                </tr>
            </table>

            <div class="price-summary">
                <div class="price-row"><span>총 상품금액</span><span><?= number_format($total_product_price) ?>원</span></div>
                <div class="price-row"><span>배송비</span><span>+<?= number_format($delivery_fee) ?>원</span></div>
                <div class="price-row" style="color: #e53935;"><span>포인트 사용</span><span id="discount_display">-0원</span></div>
                <div class="price-row total"><span>최종 결제금액</span><span id="final_price_display"><?= number_format($final_price) ?>원</span></div>
            </div>

            <button type="submit" class="btn-pay"><span id="btn_price_text"><?= number_format($final_price) ?></span>원 결제하기</button>
        </form>
    </main>
    <?php require_once("inc/footer.php"); ?>

<script>
    // PHP 변수 -> JS
    const originalTotal = <?= $total_product_price ?>;
    const deliveryFee = <?= $delivery_fee ?>;
    const maxPoint = <?= $member['point'] ?? 0 ?>;
    const memberInfo = {
        name: "<?= htmlspecialchars($member['name'] ?? '') ?>",
        phone: "<?= htmlspecialchars($member['phone'] ?? '') ?>",
        zipcode: "<?= htmlspecialchars($member['zipcode'] ?? '') ?>",
        address1: "<?= htmlspecialchars($member['address1'] ?? '') ?>",
        address2: "<?= htmlspecialchars($member['address2'] ?? '') ?>"
    };

    function loadMemberInfo() {
        document.getElementById('receiver_name').value = memberInfo.name;
        document.getElementById('receiver_phone').value = memberInfo.phone;
        document.getElementById('zipcode').value = memberInfo.zipcode;
        document.getElementById('address1').value = memberInfo.address1;
        document.getElementById('address2').value = memberInfo.address2;
    }

    function useAllPoints() {
        let currentTotal = originalTotal + deliveryFee;
        let usable = (maxPoint > currentTotal) ? currentTotal : maxPoint;
        document.getElementById('use_point').value = usable;
        calculateTotal();
    }

    function calculateTotal() {
        let usePoint = parseInt(document.getElementById('use_point').value) || 0;
        if (usePoint < 0) usePoint = 0;
        if (usePoint > maxPoint) { alert('보유 포인트를 초과할 수 없습니다.'); usePoint = maxPoint; }
        
        let total = originalTotal + deliveryFee - usePoint;
        if (total < 0) { usePoint = originalTotal + deliveryFee; total = 0; }
        
        document.getElementById('use_point').value = usePoint;
        document.getElementById('discount_display').innerText = '-' + usePoint.toLocaleString() + '원';
        document.getElementById('final_price_display').innerText = total.toLocaleString() + '원';
        document.getElementById('btn_price_text').innerText = total.toLocaleString();
        document.getElementById('total_price_hidden').value = total;
    }

    function searchAddress() {
        new daum.Postcode({
            oncomplete: function(data) {
                document.getElementById('zipcode').value = data.zonecode;
                document.getElementById('address1').value = data.address;
                document.getElementById('address2').focus();
            }
        }).open();
    }

    function validatePayment() {
        if(!document.getElementById('receiver_name').value) { alert('받는 분 이름을 입력해주세요.'); return false; }
        if(!document.getElementById('address1').value) { alert('배송지 주소를 입력해주세요.'); return false; }
        return confirm('결제를 진행하시겠습니까?');
    }
</script>
</body>
</html>