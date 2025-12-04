<?php
require_once("inc/session.php");
require_once("inc/db.php");

$order_id = $_GET['order_id'] ?? null;
if (empty($order_id)) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='index.php';</script>";
    exit;
}

// 주문 정보 조회
$order = db_select("SELECT * FROM pay WHERE order_id = ?", [$order_id]);
if (empty($order)) {
    // 디버깅을 위해 ID를 함께 출력
    echo "<script>alert('주문 정보를 찾을 수 없습니다. (Order ID: " . htmlspecialchars($order_id) . ")'); location.href='index.php';</script>";
    exit;
}
$ord = $order[0];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>주문 완료 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <style>
        .order-complete-wrapper {
            max-width: 800px;
            margin: 100px auto;
            text-align: center;
            padding: 40px;
            border: 1px solid #eee;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .icon-check {
            font-size: 60px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .order-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .order-desc {
            color: #666;
            margin-bottom: 30px;
        }
        .order-info-box {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 15px;
        }
        .info-row:last-child { margin-bottom: 0; }
        .info-label { color: #888; }
        .info-value { font-weight: 600; }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-home {
            padding: 12px 30px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-mypage {
            padding: 12px 30px;
            background: var(--mk-red);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper">
        <div class="order-complete-wrapper">
            <i class="fas fa-check-circle icon-check"></i>
            <h1 class="order-title">주문이 완료되었습니다!</h1>
            <p class="order-desc">고객님의 주문이 성공적으로 처리되었습니다.</p>

            <div class="order-info-box">
                <div class="info-row">
                    <span class="info-label">주문번호</span>
                    <span class="info-value"><?= htmlspecialchars($ord['order_id']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제금액</span>
                    <span class="info-value"><?= number_format($ord['total_price']) ?>원</span>
                </div>
                <div class="info-row">
                    <span class="info-label">주문일시</span>
                    <span class="info-value"><?= $ord['order_date'] ?? date('Y-m-d H:i:s') ?></span>
                </div>
            </div>

            <div class="btn-group">
                <button class="btn-home" onclick="location.href='index.php'">쇼핑 계속하기</button>
                <button class="btn-mypage" onclick="location.href='my-page_order.php'">주문내역 확인</button>
            </div>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>
</body>
</html>
