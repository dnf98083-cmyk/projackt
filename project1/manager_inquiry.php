<?php
require_once 'inc/session.php';
require_once 'inc/db.php'; // DB 연결 추가

if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}

// 문의 목록 조회
$inquiries = db_select("SELECT * FROM inquiries ORDER BY created_at DESC");

// 답변 등록 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reply_inquiry') {
    $inquiry_id = $_POST['inquiry_id'];
    $answer = $_POST['answer'];
    
    // 답변 내용이 비어있지 않은지 확인
    if (empty(trim($answer))) {
        echo "<script>alert('답변 내용을 입력해주세요.'); history.back();</script>";
        exit;
    }

    // DB 업데이트
    db_update_delete("UPDATE inquiries SET answer = ?, status = 'answered' WHERE id = ?", [$answer, $inquiry_id]);
    
    echo "<script>alert('답변이 등록되었습니다.'); location.href='manager_inquiry.php';</script>";
    exit;
}
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

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-container {
            background: white;
            width: 600px;
            max-width: 90%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 18px;
            font-weight: bold;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        .modal-body {
            padding: 20px;
            overflow-y: auto;
        }
        .inquiry-info {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 80px;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .inquiry-content-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            white-space: pre-wrap;
            line-height: 1.5;
        }
        .reply-section {
            margin-top: 20px;
        }
        .reply-textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            margin-bottom: 10px;
            font-family: inherit;
        }
        .reply-btn {
            width: 100%;
            padding: 12px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .reply-btn:hover {
            background: #357abd;
        }
        .table_row {
            cursor: pointer;
            transition: background 0.1s;
        }
        .table_row:hover {
            background-color: #f5f5f5;
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
                        <div class="table_row" onclick="openInquiryModal(<?= htmlspecialchars(json_encode($inquiry)) ?>)">
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
                        <div class="table_row" style="justify-content: center; padding: 20px; cursor: default;">
                            등록된 문의가 없습니다.
                        </div>
                        <?php endif; ?>
                    </section>
                </article>
            </section>
        </div>
    </main>

    <!-- Inquiry Detail Modal -->
    <div id="inquiryModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">문의 내용 확인</div>
                <button class="modal-close" onclick="closeInquiryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="inquiry-info">
                    <div class="info-row">
                        <div class="info-label">분류</div>
                        <div class="info-value" id="modalType"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">작성자</div>
                        <div class="info-value" id="modalWriter"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">작성일</div>
                        <div class="info-value" id="modalDate"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">제목</div>
                        <div class="info-value" id="modalTitle" style="font-weight: bold;"></div>
                    </div>
                    <div class="inquiry-content-box" id="modalContent"></div>
                </div>

                <div class="reply-section">
                    <h3 style="margin-bottom: 10px; font-size: 16px;">답변 작성</h3>
                    <form method="POST" action="manager_inquiry.php">
                        <input type="hidden" name="action" value="reply_inquiry">
                        <input type="hidden" name="inquiry_id" id="modalInquiryId">
                        <textarea name="answer" id="modalAnswer" class="reply-textarea" placeholder="답변 내용을 입력하세요..."></textarea>
                        <button type="submit" class="reply-btn">답변 등록하기</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openInquiryModal(data) {
            document.getElementById('modalType').textContent = data.type;
            document.getElementById('modalWriter').textContent = data.member_id;
            document.getElementById('modalDate').textContent = data.created_at;
            document.getElementById('modalTitle').textContent = data.title;
            document.getElementById('modalContent').textContent = data.content;
            document.getElementById('modalInquiryId').value = data.id;
            
            const answerField = document.getElementById('modalAnswer');
            if (data.answer) {
                answerField.value = data.answer;
            } else {
                answerField.value = '';
            }

            document.getElementById('inquiryModal').style.display = 'flex';
        }

        function closeInquiryModal() {
            document.getElementById('inquiryModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('inquiryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInquiryModal();
            }
        });
    </script>
</body>
</html>