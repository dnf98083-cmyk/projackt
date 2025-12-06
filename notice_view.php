<?php
require_once("inc/db.php");
require_once("inc/session.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 공지사항 조회
$notice = db_select("SELECT * FROM notice WHERE id = ?", [$id]);

if (empty($notice)) {
    echo "<script>alert('존재하지 않는 공지사항입니다.'); history.back();</script>";
    exit;
}

$n = $notice[0];

// 조회수 증가 (선택 사항)
// db_update("UPDATE notice SET views = views + 1 WHERE id = ?", [$id]);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($n['title']) ?> - 공지사항</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <style>
        .customer_layout {
            display: flex;
            gap: 40px;
            margin-top: 40px;
            margin-bottom: 60px;
        }
        .sidebar {
            width: 200px;
            flex-shrink: 0;
        }
        .sidebar h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            display: block;
            padding: 12px 15px;
            color: #555;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.2s;
            font-size: 16px;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #f5f5f5;
            color: #e60000;
            font-weight: bold;
        }
        .sidebar-contact {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        .sidebar-contact strong {
            display: block;
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        .sidebar-contact .phone {
            font-size: 24px;
            font-weight: bold;
            color: #e60000;
            margin-bottom: 10px;
        }
        .sidebar-contact .details {
            font-size: 13px;
            color: #777;
            line-height: 1.6;
        }

        .main-content {
            flex: 1;
        }
        .page-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .notice-view-table {
            width: 100%;
            border-top: 2px solid #333;
            border-collapse: collapse;
        }
        .notice-view-table th, .notice-view-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .notice-view-table th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
            width: 120px;
            text-align: center;
        }
        .notice-view-table td {
            color: #555;
        }
        .notice-content {
            padding: 40px 20px;
            min-height: 300px;
            line-height: 1.6;
            color: #333;
            border-bottom: 1px solid #ddd;
        }
        .btn-area {
            margin-top: 30px;
            text-align: center;
        }
        .btn-list {
            display: inline-block;
            padding: 12px 40px;
            background-color: #333;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
        }
        .btn-list:hover {
            background-color: #555;
        }

        @media (max-width: 768px) {
            .customer_layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <?php require_once __DIR__ . "/inc/header.php"; ?>

    <main class="main_wrapper">
        <div class="customer_layout">
            <!-- Sidebar -->
            <nav class="sidebar">
                <h3>고객센터</h3>
                <ul>
                    <li><a href="customer_center.php?page=home">고객센터 홈</a></li>
                    <li><a href="customer_center.php?page=notice" class="active">공지사항</a></li>
                    <li><a href="customer_center.php?page=faq">자주묻는 질문</a></li>
                    <li><a href="customer_center.php?page=inquiry">1:1 문의하기</a></li>
                </ul>
                <div class="sidebar-contact">
                    <strong>밀키친 고객센터</strong>
                    <p class="phone">1588-1234</p>
                    <p class="details">
                        · 평일: 10:00-18:00<br>
                        · 점심: 12:00-13:00<br>
                        · 주말/공휴일 휴무
                    </p>
                </div>
            </nav>

            <!-- Main Content -->
            <section class="main-content">
                <h2 class="page-title">공지사항</h2>
                
                <table class="notice-view-table">
                    <tr>
                        <th>제목</th>
                        <td colspan="3"><?= htmlspecialchars($n['title']) ?></td>
                    </tr>
                    <tr>
                        <th>작성자</th>
                        <td><?= htmlspecialchars($n['writer']) ?></td>
                        <th>작성일</th>
                        <td><?= date('Y-m-d', strtotime($n['reg_date'])) ?></td>
                    </tr>
                </table>

                <div class="notice-content">
                    <?= nl2br(htmlspecialchars($n['content'])) ?>
                </div>

                <div class="btn-area">
                    <a href="customer_center.php?page=notice" class="btn-list">목록으로</a>
                </div>

            </section>
        </div>
    </main>

    <?php require_once("inc/fast_move.php"); ?>
    <?php require_once("inc/footer.php"); ?>

</body>
</html>
