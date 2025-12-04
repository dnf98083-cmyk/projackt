<?php
require_once 'inc/session.php';
require_once 'inc/db.php';

if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='manager_notice.php';</script>";
    exit;
}

$id = $_GET['id'];
$notice = db_select("SELECT * FROM notice WHERE id = ?", [$id]);

if (empty($notice)) {
    echo "<script>alert('존재하지 않는 공지사항입니다.'); location.href='manager_notice.php';</script>";
    exit;
}

$notice = $notice[0];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지 - 공지사항 수정</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/manager.css?v=<?= time() ?>">
    <style>
        #manager_body .manager_wrapper {
            height: auto;
            min-height: 100vh;
        }
        .write-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        textarea.form-control {
            height: 300px;
            resize: vertical;
        }
        .btn-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn-submit {
            padding: 10px 30px;
            background-color: #4aadff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-cancel {
            padding: 10px 30px;
            background-color: #999;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            text-decoration: none;
        }
        .info-text {
            margin-bottom: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body id="manager_body">
    <main class="manager_wrapper notice">
        <div class="main_menu_wrapper">
            <a href="index.php"><div class="menu" style="background-color: #444; color: white;"> 메인으로 </div></a>
            <a href="manager_home.php"><div class="menu"> 홈 </div></a>
            <a href="manager_member.php"><div class="menu"> 회원 관리 </div></a>
            <a href="manager_notice.php"><div class="menu" style="background-color: rgb(74 173 255);"> 공지사항 관리 </div></a>
            <a href="manager_product.php"><div class="menu"> 상품 관리 </div></a>
            <a href="manager_event.php"><div class="menu"> 이벤트 관리 </div></a>
            <a href="manager_inquiry.php"><div class="menu"> 고객 문의 관리 </div></a>
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
                    <span class="title">공지사항 수정</span>
                </section>
                
                <section class="board">
                    <form action="manager_notice_update.php" method="post" class="write-form">
                        <input type="hidden" name="id" value="<?= $notice['id'] ?>">
                        
                        <div class="info-text">
                            작성자: <?= $notice['writer'] ?> | 작성일: <?= $notice['reg_date'] ?> | 조회수: <?= $notice['views'] ?>
                        </div>

                        <div class="form-group">
                            <label for="title">제목</label>
                            <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($notice['title']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="content">내용</label>
                            <textarea id="content" name="content" class="form-control" required><?= htmlspecialchars($notice['content']) ?></textarea>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn-submit">수정</button>
                            <a href="manager_notice.php" class="btn-cancel">취소</a>
                        </div>
                    </form>
                </section>
            </section>
        </div>
    </main>
</body>
</html>