<?php
require_once 'inc/session.php';
require_once 'inc/db.php';

if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

// 레벨 업데이트 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_level') {
    $target_id = $_POST['target_id'];
    $new_level = (int)$_POST['new_level'];
    
    $current_user_level = (int)($_SESSION['user']['level'] ?? 9);

    // 권한 체크: 부관리자(Lv.2)는 최고관리자(Lv.1)를 건드릴 수 없음
    // 또한 부관리자는 누군가를 최고관리자(Lv.1)로 승격시킬 수 없음
    if ($current_user_level > 1) {
        // 타겟이 최고관리자인지 확인
        $target_info = db_select("SELECT level FROM members WHERE id = ?", [$target_id]);
        if (!empty($target_info) && $target_info[0]['level'] == 1) {
            echo "<script>alert('권한이 부족합니다. 최고 관리자의 등급은 변경할 수 없습니다.'); history.back();</script>";
            exit;
        }

        // 최고관리자로 승격 시도 차단
        if ($new_level == 1) {
            echo "<script>alert('권한이 부족합니다. 최고 관리자 권한은 부여할 수 없습니다.'); history.back();</script>";
            exit;
        }
    }

    db_update_delete("UPDATE members SET level = ? WHERE id = ?", [$new_level, $target_id]);
    echo "<script>alert('회원 등급이 변경되었습니다.'); location.href='manager_member.php';</script>";
    exit;
}

// 회원 목록 조회
$members = db_select("SELECT * FROM members ORDER BY regist_day DESC");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/manager.css">
    <title>관리자 페이지 - 회원 관리</title>
    <style>
        .member-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .member-table th, .member-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .member-table th {
            background-color: #f4f4f4;
        }
        .level-select {
            padding: 5px;
        }
        .update-btn {
            padding: 5px 10px;
            background-color: #4aadff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body id="manager_body">
    <main class="manager_wrapper home">
        <div class="main_menu_wrapper">
            <a href="index.php"><div class="menu" style="background-color: #444; color: white;"> 메인으로 </div></a>
            <a href="manager_home.php"><div class="menu"> 홈 </div></a>
            <a href="manager_member.php"><div class="menu" style="background-color: rgb(74 173 255);"> 회원 관리 </div></a>
            <a href="manager_notice.php"><div class="menu"> 공지사항 관리 </div></a>
            <a href="manager_product.php"><div class="menu"> 상품 관리 </div></a>
            <a href="manager_event.php"><div class="menu"> 이벤트 관리 </div></a>
            <a href="manager_inquiry.php"><div class="menu"> 고객 문의 관리 </div></a>
        </div>

        <div class="main_display">
            <header>
                <div class="login_info">
                    <span class="on_id"> 접속 아이디: <?= $_SESSION['member_id'] ?? 'admin' ?> </span>
                    <span class="on_dep"> 관리자 </span>
                </div>
                <a href="logout.php"><button class="logout"> logout </button></a>
            </header>
            <section class="contents">
                <section class="contents_header">
                    <span class="title">회원 관리</span>
                </section>
                <section class="board">
                    <table class="member-table">
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>아이디</th>
                                <th>이름</th>
                                <th>가입일</th>
                                <th>현재 등급</th>
                                <th>등급 변경</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $current_user_level = (int)($_SESSION['user']['level'] ?? 9);
                            foreach ($members as $m): 
                                $is_target_super_admin = ($m['level'] == 1);
                                $can_edit = true;
                                
                                // 부관리자는 최고관리자를 수정할 수 없음
                                if ($current_user_level > 1 && $is_target_super_admin) {
                                    $can_edit = false;
                                }
                            ?>
                            <tr>
                                <td><?= $m['num'] ?></td>
                                <td><?= $m['id'] ?></td>
                                <td><?= $m['name'] ?></td>
                                <td><?= $m['regist_day'] ?></td>
                                <td>
                                    <?php 
                                    if ($m['level'] == 1) echo '최고 관리자';
                                    elseif ($m['level'] == 2) echo '부 관리자';
                                    else echo '일반 회원';
                                    ?>
                                    (Lv.<?= $m['level'] ?>)
                                </td>
                                <td>
                                    <?php if ($can_edit): ?>
                                    <form method="post" style="display:flex; gap:5px; justify-content:center;">
                                        <input type="hidden" name="action" value="update_level">
                                        <input type="hidden" name="target_id" value="<?= $m['id'] ?>">
                                        <select name="new_level" class="level-select">
                                            <option value="9" <?= $m['level'] == 9 ? 'selected' : '' ?>>일반 회원 (9)</option>
                                            <option value="2" <?= $m['level'] == 2 ? 'selected' : '' ?>>부 관리자 (2)</option>
                                            <?php if ($current_user_level == 1): // 최고관리자만 최고관리자 임명 가능 ?>
                                            <option value="1" <?= $m['level'] == 1 ? 'selected' : '' ?>>최고 관리자 (1)</option>
                                            <?php endif; ?>
                                        </select>
                                        <button type="submit" class="update-btn">변경</button>
                                    </form>
                                    <?php else: ?>
                                        <span style="color:#999; font-size:12px;">수정 불가</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </section>
        </div>
    </main>
</body>
</html>