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

// 배송지 목록 조회
$delivery_addresses = db_select("SELECT * FROM delivery_address WHERE member_id = ? ORDER BY is_default DESC, id DESC", [$member_id]);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <style>
        .payment-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .payment-container h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 40px 0 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* 주문 상품 정보 */
        .order-item-box {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .order-item-box:last-child {
            border-bottom: none;
        }
        .order-item-box img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
            border: 1px solid #eee;
        }
        .order-item-info {
            flex: 1;
        }
        .order-item-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        .order-item-meta {
            font-size: 14px;
            color: #888;
        }
        .order-item-price {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        /* 테이블 스타일 */
        .info-table {
            width: 100%;
            border-top: 1px solid #333;
            border-collapse: collapse;
        }
        .info-table th {
            width: 140px;
            padding: 15px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-weight: normal;
            color: #333;
        }
        .info-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-table input[type="text"],
        .info-table input[type="tel"],
        .info-table input[type="number"],
        .info-table select {
            height: 40px;
            padding: 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .info-table button {
            height: 40px;
            padding: 0 15px;
            border: 1px solid #333;
            background: #333;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        .info-table button:hover {
            background: #555;
        }

        /* 결제 금액 요약 */
        .price-summary {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 15px;
            color: #666;
        }
        .price-row.total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .price-row.total span:last-child {
            color: #e60000;
            font-size: 24px;
        }

        /* 결제 버튼 */
        .btn-pay {
            display: block;
            width: 100%;
            padding: 20px;
            background: #e60000;
            color: white;
            font-size: 20px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-pay:hover {
            background: #cc0000;
        }

        /* 배송지 목록 버튼 */
        .btn-address-list {
            padding: 8px 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            color: #333;
            transition: all 0.2s;
        }
        .btn-address-list:hover {
            background: #f9f9f9;
            border-color: #ccc;
        }
    </style>
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
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
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
                <button type="button" class="btn-address-list" onclick="openAddressListModal()">
                    <i class="fas fa-list"></i> 배송지 목록
                </button>
            </div>
            <table class="info-table">
                <colgroup>
                    <col style="width: 140px;">
                    <col>
                </colgroup>
                <tr>
                    <th>받는 분 <span style="color:red">*</span></th>
                    <td><input type="text" name="receiver_name" id="receiver_name" required style="width: 200px;"></td>
                </tr>
                <tr>
                    <th>연락처 <span style="color:red">*</span></th>
                    <td><input type="tel" name="receiver_phone" id="receiver_phone" required style="width: 200px;" placeholder="010-0000-0000"></td>
                </tr>
                <tr>
                    <th>주소 <span style="color:red">*</span></th>
                    <td>
                        <div style="display:flex; gap:5px; margin-bottom:8px;">
                            <input type="text" name="zipcode" id="zipcode" style="width:100px; background:#f5f5f5;" readonly placeholder="우편번호">
                            <button type="button" onclick="searchAddress()">주소검색</button>
                        </div>
                        <input type="text" name="address1" id="address1" readonly style="width: 100%; margin-bottom:8px; background:#f5f5f5;" placeholder="기본주소">
                        <input type="text" name="address2" id="address2" style="width: 100%;" placeholder="상세주소를 입력해주세요">
                    </td>
                </tr>
                <tr>
                    <th>배송 메모</th>
                    <td>
                        <select name="delivery_memo" style="width:100%; max-width: 400px;">
                            <option value="">배송시 요청사항을 선택해주세요</option>
                            <option value="문 앞에 놔주세요">문 앞에 놔주세요</option>
                            <option value="경비실에 맡겨주세요">경비실에 맡겨주세요</option>
                            <option value="배송 전 연락바랍니다">배송 전 연락바랍니다</option>
                            <option value="택배함에 넣어주세요">택배함에 넣어주세요</option>
                            <option value="직접 입력">직접 입력</option>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="section-title">할인 / 포인트</div>
            <table class="info-table">
                <colgroup>
                    <col style="width: 140px;">
                    <col>
                </colgroup>
                <tr>
                    <th>포인트 사용</th>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="number" name="use_point" id="use_point" value="0" min="0" max="<?= $member['point'] ?? 0 ?>" 
                                   style="width: 120px; text-align: right;" onchange="calculateTotal()"> 
                            <span style="font-weight: bold;">P</span>
                            <button type="button" onclick="useAllPoints()" style="background: white; color: #333; border: 1px solid #ddd;">전액사용</button>
                        </div>
                        <div style="margin-top: 8px; font-size: 13px; color: #888;">
                            보유 포인트: <span style="color: #333; font-weight: bold;"><?= number_format($member['point'] ?? 0) ?> P</span>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="section-title">결제 수단</div>
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                <div class="payment-methods">
                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="card" checked>
                        <div class="method-content">
                            <i class="far fa-credit-card" style="font-size: 24px; margin-bottom: 5px;"></i>
                            <span>신용카드</span>
                        </div>
                    </label>
                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="kakaopay">
                        <div class="method-content">
                            <span style="display:inline-block; width:24px; height:24px; background:#FEE500; color:#3C1E1E; border-radius:50%; text-align:center; line-height:24px; font-weight:bold; font-size:12px; margin-bottom:5px;">P</span>
                            <span>카카오페이</span>
                        </div>
                    </label>
                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="phone">
                        <div class="method-content">
                            <i class="fas fa-mobile-alt" style="font-size: 24px; margin-bottom: 5px;"></i>
                            <span>휴대폰결제</span>
                        </div>
                    </label>
                    <label class="payment-method-item">
                        <input type="radio" name="payment_method" value="bank">
                        <div class="method-content">
                            <i class="fas fa-university" style="font-size: 24px; margin-bottom: 5px;"></i>
                            <span>무통장입금</span>
                        </div>
                    </label>
                </div>
            </div>

            <style>
                .payment-methods {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }
                .payment-method-item {
                    flex: 1;
                    min-width: 100px;
                    cursor: pointer;
                }
                .payment-method-item input {
                    display: none;
                }
                .payment-method-item .method-content {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    color: #666;
                    transition: all 0.2s;
                    height: 100%;
                }
                .payment-method-item input:checked + .method-content {
                    border-color: #e60000;
                    background-color: #fff5f5;
                    color: #e60000;
                    font-weight: bold;
                    box-shadow: 0 0 0 1px #e60000 inset;
                }
                .payment-method-item:hover .method-content {
                    background-color: #f9f9f9;
                }
            </style>

            <div class="price-summary">
                <div class="price-row"><span>총 상품금액</span><span><?= number_format($total_product_price) ?>원</span></div>
                <div class="price-row"><span>배송비</span><span>+<?= number_format($delivery_fee) ?>원</span></div>
                <div class="price-row" style="color: #e53935;"><span>포인트 사용</span><span id="discount_display">-0원</span></div>
                <div class="price-row total"><span>최종 결제금액</span><span id="final_price_display"><?= number_format($final_price) ?>원</span></div>
            </div>

            <button type="submit" class="btn-pay"><span id="btn_price_text"><?= number_format($final_price) ?></span>원 결제하기</button>
        </form>
    </main>

    <!-- 배송지 목록 모달 -->
    <div id="addressListModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <div class="modal-content" style="background:white; padding:25px; border-radius:12px; width:500px; max-width:90%; max-height:80vh; overflow-y:auto; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                <h3 style="margin: 0; font-size: 18px;">배송지 목록</h3>
                <button type="button" onclick="document.getElementById('addressListModal').style.display='none'" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;">&times;</button>
            </div>
            
            <?php if (empty($delivery_addresses)): ?>
                <div style="text-align:center; padding:40px 0; color:#888;">
                    <i class="fas fa-map-marker-alt" style="font-size: 40px; color: #ddd; margin-bottom: 15px;"></i>
                    <p>등록된 배송지가 없습니다.</p>
                    <a href="my-page_address.php" style="display: inline-block; margin-top: 10px; color: #333; text-decoration: underline; font-size: 13px;">배송지 관리에서 추가하기</a>
                </div>
            <?php else: ?>
                <ul style="list-style:none; padding:0; margin:0;">
                    <?php foreach ($delivery_addresses as $addr): ?>
                    <li style="border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 10px; display:flex; justify-content:space-between; align-items:center; transition: border-color 0.2s;">
                        <div style="flex: 1; padding-right: 15px;">
                            <div style="font-weight:bold; margin-bottom:5px; display: flex; align-items: center; gap: 8px;">
                                <?= htmlspecialchars($addr['recipient_name']) ?>
                                <?php if ($addr['is_default']): ?>
                                    <span style="font-size:11px; color:#e60000; background: #fff0f0; padding: 2px 6px; border-radius: 4px;">기본</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:14px; color:#333; margin-bottom:4px; line-height: 1.4;">
                                <?= htmlspecialchars($addr['address']) ?> <?= htmlspecialchars($addr['address_detail']) ?>
                            </div>
                            <div style="font-size:13px; color:#888;"><?= htmlspecialchars($addr['recipient_phone']) ?></div>
                        </div>
                        <button type="button" onclick='selectAddress(<?= json_encode($addr) ?>)' style="padding:8px 15px; background:#333; color:white; border:none; border-radius:4px; cursor:pointer; font-size: 13px; white-space: nowrap;">선택</button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function openAddressListModal() {
        document.getElementById('addressListModal').style.display = 'flex';
    }

    function selectAddress(addr) {
        document.getElementById('receiver_name').value = addr.recipient_name;
        document.getElementById('receiver_phone').value = addr.recipient_phone;
        
        document.getElementById('zipcode').value = ''; // 우편번호가 없으므로 비움
        document.getElementById('address1').value = addr.address;
        document.getElementById('address2').value = addr.address_detail;
        
        document.getElementById('addressListModal').style.display = 'none';
    }
    </script>

    <?php require_once("inc/footer.php"); ?>

<script>
    // PHP 변수 -> JS
    const originalTotal = <?= $total_product_price ?>;
    const deliveryFee = <?= $delivery_fee ?>;
    const maxPoint = <?= $member['point'] ?? 0 ?>;

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
        if(!document.getElementById('receiver_phone').value) { alert('연락처를 입력해주세요.'); return false; }
        if(!document.getElementById('address1').value) { alert('배송지 주소를 입력해주세요.'); return false; }
        return confirm('결제를 진행하시겠습니까?');
    }
</script>
</body>
</html>