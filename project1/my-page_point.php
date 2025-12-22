<?php
require_once 'inc/db.php';
require_once 'inc/session.php';

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$member_id = $_SESSION['member_id'];

// 현재 포인트 조회
$member = db_select("SELECT point FROM members WHERE id = ?", [$member_id]);
$current_point = $member[0]['point'] ?? 0;

// 오늘 출석 여부 확인
$today_attendance = db_select("SELECT COUNT(*) as cnt FROM point_history WHERE member_id = ? AND type = 'attendance' AND DATE(reg_date) = CURDATE()", [$member_id]);
$is_attended = ($today_attendance[0]['cnt'] > 0);

// 포인트 내역 조회 (최신순 20개)
$history = db_select("SELECT * FROM point_history WHERE member_id = ? ORDER BY reg_date DESC LIMIT 20", [$member_id]);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지 - 포인트 관리</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* 마이페이지 레이아웃 (기존 스타일 참고) */
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
        
        /* 포인트 페이지 전용 스타일 */
        .point-summary {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }
        .point-summary h2 {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 10px;
        }
        .point-summary .point-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }
        .point-summary .point-unit {
            font-size: 1.2rem;
            color: #888;
        }

        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .action-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .action-card i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #333;
        }
        .action-card h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        .action-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .action-card .btn-action {
            display: inline-block;
            padding: 8px 20px;
            background: #333;
            color: white;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .action-card.disabled {
            background: #f0f0f0;
            cursor: default;
            opacity: 0.7;
        }
        .action-card.disabled:hover {
            transform: none;
            box-shadow: none;
        }
        .action-card.disabled .btn-action {
            background: #999;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .history-table th, .history-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .history-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
        }
        .history-table td.plus {
            color: #2ecc71;
            font-weight: bold;
        }
        .history-table td.minus {
            color: #e74c3c;
            font-weight: bold;
        }
        .no-history {
            text-align: center;
            padding: 30px;
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
                <h2 style="margin: 0; font-size: 1.5rem;">포인트 관리</h2>
            </div>

            <!-- 현재 포인트 -->
            <div class="point-summary">
                <h2>나의 보유 포인트</h2>
                <div>
                    <span class="point-value" id="currentPoint"><?= number_format($current_point) ?></span>
                    <span class="point-unit">P</span>
                </div>
            </div>

            <!-- 적립 액션 -->
            <div class="action-cards">
                <!-- 출석체크 -->
                <div class="action-card <?= $is_attended ? 'disabled' : '' ?>" onclick="<?= $is_attended ? '' : 'doAttendance()' ?>">
                    <i class="fas fa-calendar-check" style="color: #e67e22;"></i>
                    <h3>매일매일 출석체크</h3>
                    <p>하루 한 번 출석하고 10P 받으세요!</p>
                    <span class="btn-action"><?= $is_attended ? '출석완료' : '출석하기' ?></span>
                </div>

                <!-- 광고보기 -->
                <div class="action-card" onclick="watchAd()">
                    <i class="fas fa-play-circle" style="color: #3498db;"></i>
                    <h3>광고 보고 적립</h3>
                    <p>짧은 광고 보고 10P 적립받으세요!</p>
                    <span class="btn-action">광고보기</span>
                </div>
            </div>

            <!-- 포인트 내역 -->
            <h3 style="font-size: 1.2rem; margin-bottom: 15px;">최근 포인트 내역</h3>
            <?php if (empty($history)): ?>
                <div class="no-history">
                    <i class="fas fa-history" style="font-size: 30px; margin-bottom: 10px; color: #ddd;"></i>
                    <p>포인트 내역이 없습니다.</p>
                </div>
            <?php else: ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>날짜</th>
                            <th>내용</th>
                            <th>구분</th>
                            <th>포인트</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                        <tr>
                            <td><?= date('Y.m.d H:i', strtotime($row['reg_date'])) ?></td>
                            <td style="text-align: left; padding-left: 20px;"><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <?php
                                    switch($row['type']) {
                                        case 'attendance': echo '출석'; break;
                                        case 'ad': echo '광고'; break;
                                        case 'purchase': echo '구매'; break;
                                        case 'use': echo '사용'; break;
                                        default: echo '기타';
                                    }
                                ?>
                            </td>
                            <td class="<?= $row['amount'] > 0 ? 'plus' : 'minus' ?>">
                                <?= $row['amount'] > 0 ? '+' : '' ?><?= number_format($row['amount']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </main>

    <?php require_once __DIR__ . "/inc/footer.php"; ?>

    <script>
    function doAttendance() {
        fetch('point_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=attendance'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('오류가 발생했습니다.');
        });
    }

    function watchAd() {
        if(confirm('광고를 시청하시겠습니까? (시뮬레이션)')) {
            // 실제로는 여기서 광고 SDK 등을 호출
            setTimeout(() => {
                fetch('point_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=ad'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('오류가 발생했습니다.');
                });
            }, 1000); // 1초 후 적립 (광고 시청 시간 시뮬레이션)
        }
    }
    </script>
</body>
</html>
