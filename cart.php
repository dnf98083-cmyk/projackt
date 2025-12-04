<?php
require_once("cart.import.php");
require_once("inc/db.php");
require_once("inc/session.php");

if (!isset($result) || !is_array($result)) $result = [];
$is_empty = empty($result);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <title>장바구니 - Meal Kitchen</title>
</head>

<body>
    <div class="adv_main">adv</div>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper cart">
        <section class="cart_header">
            <span class="cart_title">장바구니</span>
            <div class="delete_buttons">
                <button type="button" onclick="deleteCartItems('selected')">선택 상품 삭제하기</button>
                <button type="button" onclick="deleteCartItems('all')">전체 상품 삭제하기</button>
            </div>
        </section>

        <form action="pay.submit.php" method="POST" name="cart_form">
            <section class="table">
                <table>
                    <tr class="table_header">
                        <td><input class="check_all" type="checkbox" onclick="checkAll(this)"></td>
                        <td>이미지</td>
                        <td>상품정보</td>
                        <td>가격</td>
                        <td>수량</td>
                        <td>배송비</td>
                        <td>합계</td>
                    </tr>

                    <?php if ($is_empty): ?>
                    <tr>
                        <td colspan="7" class="no-results">장바구니가 비어 있습니다.</td>
                    </tr>

                    <?php else: ?>
                    <?php 
                    $count = 1;
                    $total_price = 0;

                    foreach($result as $r):
                        $prod = db_select("SELECT * FROM contents WHERE content_code = ?", array($r["content_code"]));
                        if (empty($prod)) continue;

                        $p = $prod[0];
                        $price = $p['content_price'];
                        $amount = $r['content_amount'];
                        $total = $price * $amount;
                        $total_price += $total;
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox"
                                   class="cart-item-checkbox"
                                   value="<?= $r['cart_id'] ?>"
                                   name="check_box<?= $count ?>">
                        </td>
                        <td>
                            <div class="img_wrapper">
                                <img src="<?= $p['content_img'] ?>" alt="">
                            </div>
                        </td>

                        <td class="content_info">
                            <input type="hidden" name="content_code<?= $count ?>" value="<?= $p['content_code'] ?>">
                            <input type="hidden" name="content_options<?= $count ?>" value="<?= $r['content_options'] ?>">
                            <span class="content_options"><?= $r['content_options'] ?></span>
                            <span class="content_name"><?= $p['content_name'] ?></span>
                        </td>

                        <td><?= number_format($price) ?>원</td>

                        <td>
                            <input type="hidden" name="content_amount<?= $count ?>" value="<?= $amount ?>">
                            <?= $amount ?>개
                        </td>

                        <td>무료배송</td>
                        <td><?= number_format($total) ?>원</td>
                    </tr>

                    <?php $count++; endforeach; ?>
                    <?php endif; ?>

                    <tr class="order_receipt">
                        <td colspan="5" class="font_weight">총 결제 예정 금액</td>
                        <td></td>
                        <td><span class="total_price"><?= number_format($total_price) ?>원</span></td>
                    </tr>

                </table>
            </section>

            <section class="purchase_buttons">
                <button type="submit" name="mode" value="all">전체 상품 주문하기</button>
                <button type="submit" name="mode" value="selected">선택 상품 주문하기</button>
            </section>
        </form>
    </main>

    <?php require_once("inc/fast_move.php"); ?>
    <?php require_once("inc/footer.php"); ?>
</body>
</html>
