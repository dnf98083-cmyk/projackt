<?php
require_once("inc/db.php");
require_once("inc/session.php");

$page = $_GET['page'] ?? 'home';
$member_id = $_SESSION['member_id'] ?? null;

// --- Action Handler ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? '';

    if ($mode === 'insert_inquiry') {
        if (!$member_id) {
            echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
            exit;
        }
        $type = $_POST['type'] ?? '기타';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if ($title && $content) {
            db_insert("INSERT INTO inquiries (member_id, type, title, content) VALUES (?, ?, ?, ?)", [$member_id, $type, $title, $content]);
            echo "<script>alert('문의가 등록되었습니다.'); location.href='customer_center.php?page=inquiry';</script>";
            exit;
        } else {
            echo "<script>alert('제목과 내용을 입력해주세요.'); history.back();</script>";
            exit;
        }
    }
}

// --- Data Fetching ---
$notices = [];
$faqs = [];
$inquiries = [];

if ($page === 'home') {
    $notices = db_select("SELECT * FROM notice ORDER BY reg_date DESC LIMIT 5");
    $faqs = db_select("SELECT * FROM faqs ORDER BY created_at DESC LIMIT 5");
} elseif ($page === 'notice') {
    $notices = db_select("SELECT * FROM notice ORDER BY reg_date DESC");
} elseif ($page === 'faq') {
    $faqs = db_select("SELECT * FROM faqs ORDER BY category, created_at DESC");
} elseif ($page === 'inquiry') {
    if ($member_id) {
        $inquiries = db_select("SELECT * FROM inquiries WHERE member_id = ? ORDER BY created_at DESC", [$member_id]);
    }
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고객센터 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <style>
        /* Customer Center Specific Styles */
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

        /* Home Styles */
        .search-box {
            background-color: #f8f9fa;
            padding: 40px;
            text-align: center;
            border-radius: 15px;
            margin-bottom: 40px;
        }
        .search-box h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .search-box form {
            position: relative;
            max-width: 500px;
            margin: 0 auto 20px;
        }
        .search-box input {
            width: 100%;
            height: 50px;
            padding: 0 20px;
            padding-right: 60px;
            border: 2px solid #e60000;
            border-radius: 25px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .search-box button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 20px;
            color: #e60000;
            cursor: pointer;
        }
        .search-box .tags span {
            display: inline-block;
            margin: 0 5px;
            color: #666;
            background: #fff;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        .lists-container {
            display: flex;
            gap: 30px;
        }
        .list-box {
            flex: 1;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 25px;
        }
        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .list-header h4 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .list-header .more-link {
            font-size: 13px;
            color: #888;
            text-decoration: none;
        }
        .list-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .list-box ul li {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }
        .list-box ul li a {
            color: #444;
            text-decoration: none;
            font-size: 15px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 80%;
        }
        .list-box ul li span {
            font-size: 13px;
            color: #999;
        }

        /* Board Styles (Notice, FAQ, Inquiry) */
        .board-table {
            width: 100%;
            border-top: 2px solid #333;
            border-collapse: collapse;
        }
        .board-table th, .board-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .board-table th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        }
        .board-table td.subject {
            text-align: left;
        }
        .board-table td.subject a {
            color: #333;
            text-decoration: none;
        }
        .board-table td.subject a:hover {
            text-decoration: underline;
        }
        
        /* FAQ Styles */
        .faq-item {
            border-bottom: 1px solid #eee;
        }
        .faq-question {
            padding: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .faq-question .q-mark {
            color: #e60000;
            font-weight: bold;
            margin-right: 10px;
            font-size: 18px;
        }
        .faq-answer {
            display: none;
            padding: 20px;
            background-color: #f9f9f9;
            color: #555;
            line-height: 1.6;
            padding-left: 45px;
        }
        .faq-answer.active {
            display: block;
        }

        /* Inquiry Form */
        .inquiry-form {
            border-top: 2px solid #333;
        }
        .inquiry-row {
            display: flex;
            border-bottom: 1px solid #ddd;
        }
        .inquiry-label {
            width: 150px;
            background-color: #f9f9f9;
            padding: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .inquiry-input {
            flex: 1;
            padding: 20px;
        }
        .inquiry-input input[type="text"], 
        .inquiry-input select,
        .inquiry-input textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .inquiry-input textarea {
            height: 200px;
            resize: vertical;
        }
        .btn-area {
            margin-top: 30px;
            text-align: center;
        }
        .btn-submit {
            background-color: #e60000;
            color: white;
            border: none;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-cancel {
            background-color: #fff;
            color: #666;
            border: 1px solid #ddd;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .customer_layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
            .lists-container {
                flex-direction: column;
            }
        }
    </style>
    <script>
        function toggleFaq(element) {
            const answer = element.nextElementSibling;
            if (answer.style.display === 'block') {
                answer.style.display = 'none';
            } else {
                answer.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    
    <?php require_once __DIR__ . "/inc/header.php"; ?>

    <main class="main_wrapper">
        <div class="customer_layout">
            <!-- Sidebar -->
            <nav class="sidebar">
                <h3>고객센터</h3>
                <ul>
                    <li><a href="customer_center.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>">고객센터 홈</a></li>
                    <li><a href="customer_center.php?page=notice" class="<?= $page === 'notice' ? 'active' : '' ?>">공지사항</a></li>
                    <li><a href="customer_center.php?page=faq" class="<?= $page === 'faq' ? 'active' : '' ?>">자주묻는 질문</a></li>
                    <li><a href="customer_center.php?page=inquiry" class="<?= $page === 'inquiry' || $page === 'inquiry_write' ? 'active' : '' ?>">1:1 문의하기</a></li>
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
                
                <?php if ($page === 'home'): ?>
                    <div class="search-box">
                        <h3>무엇을 도와드릴까요?</h3>
                        <form action="customer_center.php" method="GET">
                            <input type="hidden" name="page" value="faq">
                            <input type="text" name="q" placeholder="궁금하신 내용을 입력해 주세요">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                        <div class="tags">
                            <span>#반품/교환</span>
                            <span>#배송조회</span>
                            <span>#회원혜택</span>
                        </div>
                    </div>

                    <div class="lists-container">
                        <section class="list-box">
                            <div class="list-header">
                                <h4>자주 묻는 질문 TOP 5</h4>
                                <a href="customer_center.php?page=faq" class="more-link">전체보기</a>
                            </div>
                            <ul>
                                <?php foreach ($faqs as $faq): ?>
                                <li>
                                    <a href="customer_center.php?page=faq">
                                        [<?= htmlspecialchars($faq['category']) ?>] <?= htmlspecialchars($faq['question']) ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                                <?php if (empty($faqs)) echo "<li>등록된 FAQ가 없습니다.</li>"; ?>
                            </ul>
                        </section>

                        <section class="list-box">
                            <div class="list-header">
                                <h4>공지사항</h4>
                                <a href="customer_center.php?page=notice" class="more-link">전체보기</a>
                            </div>
                            <ul>
                                <?php foreach ($notices as $notice): ?>
                                <li>
                                    <a href="notice_view.php?id=<?= $notice['id'] ?>">
                                        <?= htmlspecialchars($notice['title']) ?>
                                    </a>
                                    <span><?= date('Y.m.d', strtotime($notice['reg_date'])) ?></span>
                                </li>
                                <?php endforeach; ?>
                                <?php if (empty($notices)) echo "<li>등록된 공지사항이 없습니다.</li>"; ?>
                            </ul>
                        </section>
                    </div>

                <?php elseif ($page === 'notice'): ?>
                    <h2 class="page-title">공지사항</h2>
                    <table class="board-table">
                        <colgroup>
                            <col width="10%">
                            <col width="*">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>제목</th>
                                <th>작성자</th>
                                <th>작성일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notices as $notice): ?>
                            <tr>
                                <td><?= $notice['id'] ?></td>
                                <td class="subject"><a href="notice_view.php?id=<?= $notice['id'] ?>"><?= htmlspecialchars($notice['title']) ?></a></td>
                                <td><?= htmlspecialchars($notice['writer']) ?></td>
                                <td><?= date('Y-m-d', strtotime($notice['reg_date'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($notices)): ?>
                            <tr><td colspan="4">등록된 공지사항이 없습니다.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                <?php elseif ($page === 'faq'): ?>
                    <h2 class="page-title">자주 묻는 질문</h2>
                    <div class="faq-list">
                        <?php foreach ($faqs as $faq): ?>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <span class="q-mark">Q</span>
                                [<?= htmlspecialchars($faq['category']) ?>] <?= htmlspecialchars($faq['question']) ?>
                            </div>
                            <div class="faq-answer">
                                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($faqs)): ?>
                        <div style="padding: 20px; text-align: center;">등록된 질문이 없습니다.</div>
                        <?php endif; ?>
                    </div>

                <?php elseif ($page === 'inquiry'): ?>
                    <h2 class="page-title">1:1 문의하기</h2>
                    <?php if (!$member_id): ?>
                        <div style="text-align: center; padding: 50px 0;">
                            <p style="margin-bottom: 20px; font-size: 16px;">로그인 후 이용 가능합니다.</p>
                            <a href="login.php" class="btn-submit" style="text-decoration: none;">로그인 하러가기</a>
                        </div>
                    <?php else: ?>
                        <div style="text-align: right; margin-bottom: 20px;">
                            <a href="customer_center.php?page=inquiry_write" class="btn-submit" style="text-decoration: none;">문의하기</a>
                        </div>
                        <table class="board-table">
                            <colgroup>
                                <col width="10%">
                                <col width="15%">
                                <col width="*">
                                <col width="15%">
                                <col width="15%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>유형</th>
                                    <th>제목</th>
                                    <th>상태</th>
                                    <th>작성일</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inquiries as $inquiry): ?>
                                <tr onclick="toggleInquiry(this)" style="cursor: pointer;">
                                    <td><?= $inquiry['id'] ?></td>
                                    <td><?= htmlspecialchars($inquiry['type']) ?></td>
                                    <td class="subject"><?= htmlspecialchars($inquiry['title']) ?></td>
                                    <td>
                                        <?php if ($inquiry['status'] === 'waiting'): ?>
                                            <span style="color: #888;">답변대기</span>
                                        <?php else: ?>
                                            <span style="color: #e60000; font-weight: bold;">답변완료</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($inquiry['created_at'])) ?></td>
                                </tr>
                                <tr class="inquiry-detail" style="display: none; background-color: #f9f9f9;">
                                    <td colspan="5" style="text-align: left; padding: 20px;">
                                        <div style="margin-bottom: 20px;">
                                            <strong style="display: block; margin-bottom: 10px; color: #333;">[문의내용]</strong>
                                            <div style="white-space: pre-wrap; color: #555;"><?= htmlspecialchars($inquiry['content']) ?></div>
                                        </div>
                                        <?php if ($inquiry['answer']): ?>
                                        <div style="border-top: 1px solid #ddd; padding-top: 20px;">
                                            <strong style="display: block; margin-bottom: 10px; color: #e60000;">[답변내용]</strong>
                                            <div style="white-space: pre-wrap; color: #333;"><?= htmlspecialchars($inquiry['answer']) ?></div>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($inquiries)): ?>
                                <tr><td colspan="5">등록된 문의 내역이 없습니다.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <script>
                            function toggleInquiry(row) {
                                const detailRow = row.nextElementSibling;
                                if (detailRow && detailRow.classList.contains('inquiry-detail')) {
                                    const isHidden = detailRow.style.display === 'none';
                                    // 모든 상세 행 닫기 (선택 사항: 하나만 열리게 하려면 주석 해제)
                                    // document.querySelectorAll('.inquiry-detail').forEach(el => el.style.display = 'none');
                                    
                                    detailRow.style.display = isHidden ? 'table-row' : 'none';
                                }
                            }
                        </script>
                    <?php endif; ?>

                <?php elseif ($page === 'inquiry_write'): ?>
                    <h2 class="page-title">1:1 문의 작성</h2>
                    <form action="customer_center.php" method="POST" class="inquiry-form">
                        <input type="hidden" name="mode" value="insert_inquiry">
                        
                        <div class="inquiry-row">
                            <div class="inquiry-label">문의유형</div>
                            <div class="inquiry-input">
                                <select name="type">
                                    <option value="주문/결제">주문/결제</option>
                                    <option value="배송">배송</option>
                                    <option value="취소/반품">취소/반품</option>
                                    <option value="상품">상품</option>
                                    <option value="회원">회원</option>
                                    <option value="기타">기타</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="inquiry-row">
                            <div class="inquiry-label">제목</div>
                            <div class="inquiry-input">
                                <input type="text" name="title" placeholder="제목을 입력해주세요" required>
                            </div>
                        </div>
                        
                        <div class="inquiry-row">
                            <div class="inquiry-label">내용</div>
                            <div class="inquiry-input">
                                <textarea name="content" placeholder="문의하실 내용을 자세히 입력해주세요" required></textarea>
                            </div>
                        </div>

                        <div class="btn-area">
                            <button type="submit" class="btn-submit">등록하기</button>
                            <button type="button" class="btn-cancel" onclick="history.back()">취소</button>
                        </div>
                    </form>

                <?php endif; ?>

            </section>
        </div>
    </main>

    <?php require_once("inc/fast_move.php"); ?>
    <?php require_once("inc/footer.php"); ?>

</body>
</html>
