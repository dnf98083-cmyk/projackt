<?php
require_once 'inc/session.php';
require_once 'inc/db.php'; // DB 연결 추가

if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

// 문의 목록 조회
$inquiries = db_select("SELECT * FROM inquiries ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/manager.css?v=<?= time() ?>">
    <title>관리자 페이지-고객문의</title>
    <style>
        /* manager.css 오버라이드 및 추가 스타일 */
        #manager_body .manager_wrapper {
            height: auto;
            min-height: 100vh;
        }
        .board.inquiry .table_col.status {
            width: 10%;
            text-align: center;
        }
        /* 기존 views 클래스 대신 status 사용 */
        .board.inquiry .table_col.views {
            display: none; 
        }
        .table_header .table_col.status, .table_row .table_col.status {
            flex: 1;
            text-align: center;
        }
        
        /* 상태 뱃지 */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-waiting {
            background-color: #eee;
            color: #666;
        }
        .status-answered {
            background-color: #e6f7ff;
            color: #1890ff;
        }
    </style>
</head>

<body id="manager_body">
    <main class="manager_wrapper notice">
        <div class="main_menu_wrapper">
            <a href="index.php">
                <div class="menu" style="background-color: #444; color: white;"> 메인으로 </div>
            </a>

            <a href="manager_home.php">
                <div class="menu"> 홈 </div>
            </a>
            <a href="manager_member.php">
                <div class="menu"> 회원 관리 </div>
            </a>
            <a href="manager_notice.php">
                <div class="menu"> 공지사항 관리 </div>
            </a>
            <a href="manager_product.php">
                <div class="menu"> 상품 관리 </div>
            </a>
            <a href="manager_event.php">
                <div class="menu"> 이벤트 관리 </div>
            </a>
            <a href="manager_inquiry.php">
                <div class="menu" style="background-color:  rgb(74 173 255);"> 고객 문의 관리 </div>
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
            <section class="contents">
                <section class="contents_header">
                    <span class="title"> 고객 문의 관리 </span>
                    <div class="notification"><?= count($inquiries) ?></div>
                </section>
                <article class="scroller">
                    <section class="board inquiry">
                        <div class="table_header">
                            <div class="table_col class" style="width: 15%;">분류</div>
                            <div class="table_col title" style="width: 40%;">제목</div>
                            <div class="table_col writer" style="width: 15%;">글쓴이</div>
                            <div class="table_col status" style="width: 15%;">상태</div>
                            <div class="table_col date" style="width: 15%;">날짜</div>
                        </div>
                        
                        <?php foreach ($inquiries as $inquiry): ?>
                        <div class="table_row">
                            <div class="table_col class" style="width: 15%;"><?= htmlspecialchars($inquiry['type']) ?></div>
                            <div class="table_col title" style="width: 40%; text-align: left; padding-left: 20px;">
                                <?= htmlspecialchars($inquiry['title']) ?>
                            </div>
                            <div class="table_col writer" style="width: 15%;"><?= htmlspecialchars($inquiry['member_id']) ?></div>
                            <div class="table_col status" style="width: 15%;">
                                <?php if ($inquiry['status'] === 'waiting'): ?>
                                    <span class="status-badge status-waiting">답변대기</span>
                                <?php else: ?>
                                    <span class="status-badge status-answered">답변완료</span>
                                <?php endif; ?>
                            </div>
                            <div class="table_col date" style="width: 15%;"><?= date('Y-m-d', strtotime($inquiry['created_at'])) ?></div>
                        </div>
                        <?php endforeach; ?>

                        <?php if (empty($inquiries)): ?>
                        <div class="table_row" style="justify-content: center; padding: 20px;">
                            등록된 문의가 없습니다.
                        </div>
                        <?php endif; ?>
                    </section>
                </article>
            </section>
        </div>
    </main>
</body>
</html>