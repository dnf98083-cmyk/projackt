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

// 회원 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_member') {
    $target_id = $_POST['target_id'];
    $current_user_level = (int)($_SESSION['user']['level'] ?? 9);

    // 권한 체크: 최고관리자만 삭제 가능 (또는 정책에 따라 부관리자도 가능하게 할 수 있음)
    if ($current_user_level > 1) {
        echo "<script>alert('권한이 부족합니다. 회원 삭제는 최고 관리자만 가능합니다.'); history.back();</script>";
        exit;
    }

    // 타겟이 최고관리자인지 확인
    $target_info = db_select("SELECT level FROM members WHERE id = ?", [$target_id]);
    if (!empty($target_info) && $target_info[0]['level'] == 1) {
        echo "<script>alert('최고 관리자는 삭제할 수 없습니다.'); history.back();</script>";
        exit;
    }

    db_update_delete("DELETE FROM members WHERE id = ?", [$target_id]);
    echo "<script>alert('회원이 삭제되었습니다.'); location.href='manager_member.php';</script>";
    exit;
}

// 검색 및 페이지네이션
$search_keyword = $_GET['search_keyword'] ?? '';
$sort_option = $_GET['sort'] ?? 'regist_day_desc'; // 기본 정렬: 가입일 내림차순
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$where_clause = "";
$params = [];

if (!empty($search_keyword)) {
    $where_clause = "WHERE id LIKE ? OR name LIKE ?";
    $params[] = "%$search_keyword%";
    $params[] = "%$search_keyword%";
}

// 정렬 조건 설정
$order_by = "regist_day DESC"; // 기본값
switch ($sort_option) {
    case 'regist_day_asc':
        $order_by = "regist_day ASC";
        break;
    case 'level_asc': // 등급 높은 순 (숫자가 작을수록 높음: 1 > 2 > 9)
        $order_by = "level ASC, regist_day DESC";
        break;
    case 'level_desc': // 등급 낮은 순
        $order_by = "level DESC, regist_day DESC";
        break;
    case 'regist_day_desc':
    default:
        $order_by = "regist_day DESC";
        break;
}

// 전체 개수 조회
$count_query = "SELECT COUNT(*) as cnt FROM members $where_clause";
$total_count = db_select($count_query, $params)[0]['cnt'];
$total_pages = ceil($total_count / $limit);

// 데이터 조회
$query = "SELECT * FROM members $where_clause ORDER BY $order_by LIMIT $limit OFFSET $offset";
$members = db_select($query, $params);
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
        /* Global Reset & Base */
        * { box-sizing: border-box; }

        /* Layout Override (manager.css .board 스타일 덮어쓰기) */
        .board {
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 20px 30px !important;
            background: transparent !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 20px; /* 요소 간 간격 */
            box-sizing: border-box !important;
        }
        
        /* Table Wrapper */
        .table-wrapper {
            width: 100%;
            max-height: 400px; /* 세로 높이 축소 (회색 박스에 맞춤) */
            overflow-y: auto;  /* 세로 스크롤 */
            overflow-x: auto;  /* 가로 스크롤 */
            border: 1px solid #e0e0e0;
            margin-top: 0 !important; /* board gap 사용 */
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Search Box */
        .search-box {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0 !important; /* board gap 사용 */
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            box-sizing: border-box;
        }
        .member-table {
            width: 100%;
            min-width: 1200px; /* 가로 스크롤을 확실하게 보여주기 위해 너비 증가 */
            border-collapse: collapse;
            font-size: 13px; /* 폰트 사이즈 축소 */
            table-layout: fixed; /* 컬럼 너비 고정 */
        }
        .member-table th, .member-table td {
            padding: 8px 10px !important; /* 패딩 강제 축소 */
            text-align: center;
            border-bottom: 1px solid #eee;
            color: #333;
            vertical-align: middle;
            white-space: nowrap;
        }
        /* ID와 이름은 왼쪽 정렬 */
        .member-table td:nth-child(2),
        .member-table td:nth-child(3) {
            text-align: left;
            padding-left: 20px !important;
        }
        .member-table th {
            background-color: #f5f7fa; /* 헤더 배경색 변경 */
            font-weight: 600;
            color: #444;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid #ddd;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
        .member-table tr:hover {
            background-color: #f0f7ff;
        }

        /* Column Widths - Percentage based for responsiveness */
        .member-table th:nth-child(1) { width: 5%; } /* 번호 */
        .member-table th:nth-child(2) { width: 15%; } /* 아이디 */
        .member-table th:nth-child(3) { width: 10%; } /* 이름 */
        .member-table th:nth-child(4) { width: 20%; } /* 가입일 */
        .member-table th:nth-child(5) { width: 15%; } /* 등급 */
        .member-table th:nth-child(6) { width: 35%; } /* 관리 */

        /* Level Badges */
        .level-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px; /* 둥글게 */
            font-size: 11px;
            font-weight: bold;
            margin-left: 6px;
            vertical-align: middle;
            line-height: 1;
        }
        .level-badge.lv1 { background-color: #ffebee; color: #d32f2f; border: 1px solid #ffcdd2; }
        .level-badge.lv2 { background-color: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
        .level-badge.lv9 { background-color: #f5f5f5; color: #616161; border: 1px solid #e0e0e0; }

        /* Buttons */
        .update-btn, .delete-btn {
            padding: 4px 8px !important;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
            color: white;
            height: 26px; /* 높이 고정 */
            line-height: 1;
        }
        .update-btn {
            background-color: #2196f3;
        }
        .update-btn:hover {
            background-color: #1976d2;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        
        /* Level Select in Table */
        .level-select {
            padding: 0 5px;
            height: 26px; /* 버튼과 높이 맞춤 */
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
            outline: none;
            min-width: 90px;
            vertical-align: middle;
            background-color: white;
        }

        /* Search Box */
        .search-box {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0 !important; /* board gap 사용 */
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            box-sizing: border-box;
        }
        .search-box form {
            display: flex;
            gap: 10px;
            width: 100%;
            align-items: center;
        }
        .search-box select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 150px;
            outline: none;
        }
        .search-box input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
        }
        .search-box button {
            padding: 10px 25px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        .search-box button:hover {
            background: #555;
        }

        /* Header Info Box */
        .login_info {
            background-color: #e3f2fd;
            padding: 15px 20px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 10px;
            border: 1px solid #bbdefb;
        }
        .login_info span {
            display: block;
            color: #1565c0;
            font-size: 14px;
            line-height: 1.5;
        }
        .logout {
            background-color: #0288d1 !important;
            color: white !important;
            border-radius: 20px !important;
            padding: 8px 20px !important;
            border: none !important;
            font-size: 13px !important;
            transition: background 0.2s;
        }
        .logout:hover {
            background-color: #0277bd !important;
        }

        /* Pagination */
        .pagination {
            margin-top: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        .pagination a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 5px;
            border: 1px solid #ddd;
            color: #555;
            text-decoration: none;
            background: white;
            border-radius: 4px;
            font-size: 13px;
            transition: all 0.2s;
        }
        .pagination a:hover {
            background-color: #f5f5f5;
            border-color: #ccc;
        }
        .pagination a.active {
            background-color: #333;
            color: white;
            border-color: #333;
        }
        
        /* Level Select in Table */
        .level-select {
            padding: 0 5px;
            height: 26px; /* 버튼과 높이 맞춤 */
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
            outline: none;
            min-width: 90px;
            vertical-align: middle;
            background-color: white;
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
                <!-- contents_header 제거됨 -->
                <section class="board">
                    <!-- 검색 및 정렬 폼 -->
                    <div class="search-box">
                        <form method="get" action="manager_member.php">
                            <select name="sort" onchange="this.form.submit()">
                                <option value="regist_day_desc" <?= $sort_option == 'regist_day_desc' ? 'selected' : '' ?>>가입일 최신순</option>
                                <option value="regist_day_asc" <?= $sort_option == 'regist_day_asc' ? 'selected' : '' ?>>가입일 오래된순</option>
                                <option value="level_asc" <?= $sort_option == 'level_asc' ? 'selected' : '' ?>>등급 높은순 (관리자 우선)</option>
                                <option value="level_desc" <?= $sort_option == 'level_desc' ? 'selected' : '' ?>>등급 낮은순 (일반회원 우선)</option>
                            </select>
                            <input type="text" name="search_keyword" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="아이디 또는 이름 검색">
                            <button type="submit">검색</button>
                        </form>
                    </div>

                    <div class="table-wrapper">
                        <table class="member-table">
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>아이디</th>
                                    <th>이름</th>
                                    <th>가입일</th>
                                    <th>현재 등급</th>
                                    <th style="width: 250px;">관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $current_user_level = (int)($_SESSION['user']['level'] ?? 9);
                                if (empty($members)) {
                                    echo "<tr><td colspan='6' style='padding: 30px; color: #999;'>검색 결과가 없습니다.</td></tr>";
                                } else {
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
                                        if ($m['level'] == 1) echo '최고 관리자 <span class="level-badge lv1">Lv.1</span>';
                                        elseif ($m['level'] == 2) echo '부 관리자 <span class="level-badge lv2">Lv.2</span>';
                                        else echo '일반 회원 <span class="level-badge lv9">Lv.9</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($can_edit): ?>
                                        <div style="display:flex; gap:6px; justify-content:center; align-items: center;">
                                            <form method="post" style="display:flex; gap:6px; align-items: center; margin:0;">
                                                <input type="hidden" name="action" value="update_level">
                                                <input type="hidden" name="target_id" value="<?= $m['id'] ?>">
                                                <select name="new_level" class="level-select">
                                                    <option value="9" <?= $m['level'] == 9 ? 'selected' : '' ?>>일반 회원</option>
                                                    <option value="2" <?= $m['level'] == 2 ? 'selected' : '' ?>>부 관리자</option>
                                                    <?php if ($current_user_level == 1): // 최고관리자만 최고관리자 임명 가능 ?>
                                                    <option value="1" <?= $m['level'] == 1 ? 'selected' : '' ?>>최고 관리자</option>
                                                    <?php endif; ?>
                                                </select>
                                                <button type="submit" class="update-btn">변경</button>
                                            </form>
                                            
                                            <?php if ($current_user_level == 1): // 삭제는 최고관리자만 가능 ?>
                                            <form method="post" onsubmit="return confirm('정말 이 회원을 삭제하시겠습니까? 복구할 수 없습니다.');" style="margin:0;">
                                                <input type="hidden" name="action" value="delete_member">
                                                <input type="hidden" name="target_id" value="<?= $m['id'] ?>">
                                                <button type="submit" class="delete-btn">삭제</button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                        <?php else: ?>
                                            <span style="color:#999; font-size:12px;">수정 불가</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach; 
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- 페이지네이션 -->
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php 
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        if ($page > 1): ?>
                            <a href="?page=1&search_keyword=<?= urlencode($search_keyword) ?>&sort=<?= $sort_option ?>">&lt;&lt;</a>
                            <a href="?page=<?= $page - 1 ?>&search_keyword=<?= urlencode($search_keyword) ?>&sort=<?= $sort_option ?>">&lt;</a>
                        <?php endif; ?>

                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <a href="?page=<?= $i ?>&search_keyword=<?= urlencode($search_keyword) ?>&sort=<?= $sort_option ?>" 
                               class="<?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>&search_keyword=<?= urlencode($search_keyword) ?>&sort=<?= $sort_option ?>">&gt;</a>
                            <a href="?page=<?= $total_pages ?>&search_keyword=<?= urlencode($search_keyword) ?>&sort=<?= $sort_option ?>">&gt;&gt;</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </section>
            </section>
        </div>
    </main>
</body>
</html>