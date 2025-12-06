<?php
require_once 'inc/db.php';
require_once 'inc/session.php';

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// 취소/반품/교환 상태 목록
$claim_statuses = [
    '취소신청', '취소완료', 
    '반품신청', '반품완료', 
    '교환신청', '교환완료'
];
$status_str = "'" . implode("','", $claim_statuses) . "'";

// 전체 개수 조회
$count_query = "SELECT COUNT(*) as total FROM pay WHERE member_id = ? AND status IN ($status_str)";
$count_result = db_select($count_query, [$member_id]);
$total_count = $count_result[0]['total'] ?? 0;
$total_pages = ceil($total_count / $limit);

// 목록 조회
$query = "SELECT * FROM pay WHERE member_id = ? AND status IN ($status_str) ORDER BY order_date DESC LIMIT $limit OFFSET $offset";
$orders = db_select($query, [$member_id]);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지 - 취소/반품/교환 내역</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .mypage-container {
            display: flex;
            max-width: 1200px;
            margin: 50px auto;
            gap: 30px;
            padding: 0 20px;
        }
        .mypage-content {
            flex: 1;
        }
        .order-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .order-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-date {
            font-weight: bold;
            color: #333;
            margin-right: 15px;
        }
        .order-id {
            color: #666;
            font-size: 0.9rem;
        }
        .order-body {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .product-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }
        .thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        .info-text {
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 5px;
            background: #eee;
            color: #666;
        }
        /* 상태별 뱃지 색상 */
        .status-cancel { background: #ffebee; color: #c62828; }
        .status-return { background: #e3f2fd; color: #1565c0; }
        .status-exchange { background: #e8f5e9; color: #2e7d32; }

        .product-name {
            font-size: 1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .price {
            font-weight: bold;
            color: #333;
        }
        .no-data {
            text-align: center;
            padding: 50px 0;
            color: #888;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/inc/header.php"; ?>

    <main class="mypage-container">
        <!-- 사이드바 -->
        <?php require_once __DIR__ . "/inc/mypage_sidebar.php"; ?>

        <!-- 메인 컨텐츠 -->
        <div class="mypage-content">
            <div style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 1.5rem;">취소/반품/교환 내역</h2>
            </div>

            <?php if (empty($orders)): ?>
                <div class="no-data">
                    <i class="fas fa-box-open" style="font-size: 40px; color: #ddd; margin-bottom: 15px;"></i>
                    <p>취소/반품/교환 내역이 없습니다.</p>
                </div>
            <?php else: ?>
                <div class="order-list">
                    <?php foreach ($orders as $ord): 
                        $items = json_decode($ord['order_contents'], true);
                        $first_item = $items[0] ?? null;
                        
                        if ($first_item) {
                            $title = $first_item['content_name'];
                            if (count($items) > 1) {
                                $title .= " 외 " . (count($items) - 1) . "건";
                            }
                            $img = $first_item['content_img'];
                            $content_code = $first_item['content_code'];
                        } else {
                            $title = "상품 정보 없음";
                            $img = "img/icons/no-image.png";
                            $content_code = "";
                        }

                        $status = $ord['status'];
                        $badge_class = '';
                        if (strpos($status, '취소') !== false) $badge_class = 'status-cancel';
                        elseif (strpos($status, '반품') !== false) $badge_class = 'status-return';
                        elseif (strpos($status, '교환') !== false) $badge_class = 'status-exchange';
                    ?>
                    <div class="order-item">
                        <div class="order-header">
                            <div>
                                <span class="order-date"><?= date('Y.m.d', strtotime($ord['order_date'])) ?></span>
                                <span class="order-id">주문번호 <?= $ord['order_id'] ?></span>
                            </div>
                            <a href="contents_detail.php?content_code=<?= $content_code ?>" style="font-size: 0.9rem; color: #666; text-decoration: none;">상품 상세보기 ></a>
                        </div>
                        <div class="order-body">
                            <div class="product-info">
                                <img src="<?= $img ?>" alt="상품이미지" class="thumb" onerror="this.src='https://placehold.co/80x80?text=No+Image'">
                                <div class="info-text">
                                    <div class="status-badge <?= $badge_class ?>"><?= $status ?></div>
                                    <div class="product-name"><?= htmlspecialchars($title) ?></div>
                                    <div class="price"><?= number_format($ord['total_price']) ?>원</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- 페이지네이션 -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination" style="display: flex; justify-content: center; gap: 5px; margin-top: 30px;">
                    <?php 
                    $qs = $_GET;
                    unset($qs['page']);
                    $base_url = '?' . http_build_query($qs);
                    ?>
                    
                    <?php if ($page > 1): ?>
                        <a href="<?= $base_url ?>&page=1" style="padding: 8px 12px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 4px;">&lt;&lt;</a>
                    <?php endif; ?>

                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    
                    for ($i = $start_page; $i <= $end_page; $i++):
                        $active = ($i == $page) ? 'background: #333; color: white; border-color: #333;' : 'background: white; color: #666; border: 1px solid #ddd;';
                    ?>
                        <a href="<?= $base_url ?>&page=<?= $i ?>" style="padding: 8px 12px; text-decoration: none; border-radius: 4px; <?= $active ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="<?= $base_url ?>&page=<?= $total_pages ?>" style="padding: 8px 12px; border: 1px solid #ddd; color: #666; text-decoration: none; border-radius: 4px;">&gt;&gt;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </main>

    <?php require_once __DIR__ . "/inc/footer.php"; ?>
</body>
</html>
