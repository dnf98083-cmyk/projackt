<?php
require_once 'inc/session.php';
require_once 'inc/db.php';

if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

// 공지사항 삭제 처리
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    db_update_delete("DELETE FROM notice WHERE id = ?", [$_POST['id']]);
    echo "<script>alert('삭제되었습니다.'); location.href='manager_notice.php';</script>";
    exit;
}

// 공지사항 목록 조회
$notices = db_select("SELECT * FROM notice ORDER BY reg_date DESC");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지 - 공지사항 관리</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/manager.css?v=<?= time() ?>">
    <style>
        /* 기존 manager.css의 고정 높이 오버라이드 */
        #manager_body .manager_wrapper {
            height: auto;
            min-height: 100vh;
        }
        .table-wrapper {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #ddd;
            margin-top: 20px;
            background: white;
        }
        .notice-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-top: 20px; 제거 */
            background: white;
        }
        .notice-table th, .notice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .notice-table th {
            background-color: #f4f4f4;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
            border-top: none;
        }
        .notice-table td.title {
            text-align: left;
            padding-left: 20px;
        }
        .btn-write {
            padding: 8px 16px;
            background-color: #4aadff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-delete {
            padding: 4px 8px;
            background-color: #ff4a4a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .top-controls {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
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
                    <span class="title">공지사항 관리</span>
                </section>
                
                <section class="board" style="padding: 20px;">
                    <div class="top-controls">
                        <a href="manager_notice_write.php" class="btn-write">공지사항 등록</a>
                    </div>

                    <div class="table-wrapper">
                        <table class="notice-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">번호</th>
                                    <th>제목</th>
                                    <th style="width: 100px;">작성자</th>
                                    <th style="width: 80px;">조회수</th>
                                    <th style="width: 150px;">작성일</th>
                                    <th style="width: 80px;">관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notices as $n): ?>
                                <tr>
                                    <td><?= $n['id'] ?></td>
                                    <td class="title">
                                        <a href="manager_notice_edit.php?id=<?= $n['id'] ?>" style="text-decoration: none; color: inherit;">
                                            <?= htmlspecialchars($n['title']) ?>
                                        </a>
                                    </td>
                                    <td><?= $n['writer'] ?></td>
                                    <td><?= number_format($n['views']) ?></td>
                                    <td><?= substr($n['reg_date'], 0, 10) ?></td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                            <button type="submit" class="btn-delete">삭제</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($notices)): ?>
                                <tr>
                                    <td colspan="6" style="padding: 30px; color: #999;">등록된 공지사항이 없습니다.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </div>
    </main>
</body>
</html>