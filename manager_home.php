<?php
require_once 'inc/session.php';
require_once 'inc/db.php';

if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

// 통계 데이터 조회
$count_members = db_select("SELECT COUNT(*) as cnt FROM members")[0]['cnt'];
$count_products = db_select("SELECT COUNT(*) as cnt FROM contents")[0]['cnt'];
$count_reviews = db_select("SELECT COUNT(*) as cnt FROM review")[0]['cnt'];
$count_orders = db_select("SELECT COUNT(*) as cnt FROM pay")[0]['cnt'];

// 최근 가입 회원
$recent_members = db_select("SELECT * FROM members ORDER BY regist_day DESC LIMIT 5");

// 최근 주문
$recent_orders = db_select("SELECT * FROM pay ORDER BY order_date DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 대시보드</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/manager.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* 기존 manager.css의 고정 높이 오버라이드 */
        #manager_body .manager_wrapper {
            height: auto;
            min-height: 100vh;
        }
        .main_display {
            background-color: #f5f7fa;
        }
        
        /* Dashboard Styles (Backup) */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .stat-card .icon {
            font-size: 30px;
            color: #4aadff;
            align-self: flex-end;
            margin-top: -30px;
            opacity: 0.3;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 0 20px 20px 20px;
        }

        .dashboard-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .dashboard-section h2 {
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #333;
        }

        .recent-list {
            list-style: none;
            padding: 0;
        }

        .recent-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recent-list li:last-child {
            border-bottom: none;
        }

        .recent-list .info {
            display: flex;
            flex-direction: column;
        }

        .recent-list .primary {
            font-weight: bold;
            font-size: 14px;
        }

        .recent-list .secondary {
            font-size: 12px;
            color: #888;
        }

        .recent-list .date {
            font-size: 12px;
            color: #999;
        }

        /* Responsive adjustments */
        @media (max-width: 1000px) {
            .dashboard-stats {
                grid-template-columns: 1fr 1fr;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body id="manager_body">
    <main class="manager_wrapper home">
        <div class="main_menu_wrapper">
            <a href="index.php">
                <div class="menu" style="background-color: #444; color: white;"> 메인으로 </div>
            </a>
            <a href="manager_home.php">
                <div class="menu" style="background-color: rgb(74 173 255);"> 홈 </div>
            </a>
            <a href="manager_member.php">
                <div class="menu"> 회원 관리 </div>
            </a>
            <a href="manager_notice.php">
                <div class="menu"> 공지사항 관리 </div>
            </a>
            <a href="manager_inquiry.php">
                <div class="menu"> 고객 문의 관리 </div>
            </a>
        </div>

        <div class="main_display">
            <header>
                <div class="login_info">
                    <span class="on_id"> 접속 아이디: <?= $_SESSION['member_id'] ?> </span>
                    <span class="on_dep"> 관리자 </span>
                </div>
                <a href="logout.php"><button class="logout"> logout </button></a>
            </header>
            
            <section class="contents" style="padding: 0;">
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>총 회원수</h3>
                        <div class="number"><?= number_format($count_members) ?>명</div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="stat-card">
                        <h3>총 상품수</h3>
                        <div class="number"><?= number_format($count_products) ?>개</div>
                        <div class="icon"><i class="fas fa-box"></i></div>
                    </div>
                    <div class="stat-card">
                        <h3>총 주문수</h3>
                        <div class="number"><?= number_format($count_orders) ?>건</div>
                        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                    <div class="stat-card">
                        <h3>총 리뷰수</h3>
                        <div class="number"><?= number_format($count_reviews) ?>개</div>
                        <div class="icon"><i class="fas fa-star"></i></div>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-section">
                        <h2>최근 가입 회원</h2>
                        <ul class="recent-list">
                            <?php foreach ($recent_members as $m): ?>
                            <li>
                                <div class="info">
                                    <span class="primary"><?= $m['name'] ?> (<?= $m['id'] ?>)</span>
                                    <span class="secondary">Lv.<?= $m['level'] ?></span>
                                </div>
                                <span class="date"><?= substr($m['regist_day'], 0, 10) ?></span>
                            </li>
                            <?php endforeach; ?>
                            <?php if (empty($recent_members)) echo "<li style='justify-content:center; color:#999;'>가입 회원이 없습니다.</li>"; ?>
                        </ul>
                    </div>

                    <div class="dashboard-section">
                        <h2>최근 주문 내역</h2>
                        <ul class="recent-list">
                            <?php foreach ($recent_orders as $o): ?>
                            <li>
                                <div class="info">
                                    <span class="primary">주문번호: <?= $o['order_id'] ?></span>
                                    <span class="secondary"><?= number_format($o['total_price']) ?>원 / <?= $o['status'] ?></span>
                                </div>
                                <span class="date"><?= substr($o['order_date'], 0, 10) ?></span>
                            </li>
                            <?php endforeach; ?>
                            <?php if (empty($recent_orders)) echo "<li style='justify-content:center; color:#999;'>주문 내역이 없습니다.</li>"; ?>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>