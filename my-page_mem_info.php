<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 체크
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// 회원 정보 조회
$member_id = $_SESSION['member_id'];
$member_data = db_select("SELECT * FROM members WHERE id = ?", array($member_id));

if (empty($member_data)) {
    echo "<script>alert('회원 정보를 찾을 수 없습니다.'); location.href='index.php';</script>";
    exit;
}

$m = $member_data[0];
$email_parts = explode('@', $m['email']);
$email1 = $email_parts[0] ?? '';
$email2 = $email_parts[1] ?? '';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원정보 수정 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .info-form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 20px;
        }
        .form-group:last-child {
            border-bottom: none;
        }
        .form-label {
            width: 150px;
            font-weight: bold;
            color: #333;
            flex-shrink: 0;
        }
        .form-input-wrap {
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-input:read-only {
            background-color: #f9f9f9;
            color: #666;
        }
        .form-input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
        .btn-verify {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            white-space: nowrap;
        }
        .btn-verify:hover {
            background-color: #555;
        }
        .verify-msg {
            color: #0085ff;
            font-size: 13px;
            font-weight: bold;
            display: none;
        }
        .btn-submit {
            background-color: #e60000;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background-color: #cc0000;
        }
        .btn-cancel {
            background-color: white;
            color: #666;
            border: 1px solid #ddd;
            padding: 15px 40px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-cancel:hover {
            background-color: #f9f9f9;
        }
        
        @media (max-width: 768px) {
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-label {
                width: 100%;
                margin-bottom: 8px;
            }
            .form-input-wrap {
                width: 100%;
                flex-wrap: wrap;
            }
        }
    </style>
    <script>
        let is_password_verified = false;

        function verify_current_pass() {
            const current_pass = document.getElementById('current_pass').value;
            if (!current_pass) {
                alert("현재 비밀번호를 입력해주세요.");
                document.getElementById('current_pass').focus();
                return;
            }

            $.ajax({
                url: 'check_password.php',
                type: 'POST',
                data: { current_pass: current_pass },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert("비밀번호가 확인되었습니다.");
                        is_password_verified = true;
                        
                        // UI 변경
                        document.getElementById('btn_verify').style.display = 'none';
                        document.getElementById('verify_msg').style.display = 'inline-block';
                        document.getElementById('current_pass').readOnly = true;
                        
                        // 새 비밀번호 입력 활성화
                        document.getElementById('pass').disabled = false;
                        document.getElementById('pass_confirm').disabled = false;
                        document.getElementById('pass').placeholder = "변경시에만 입력하세요";
                        document.getElementById('pass_confirm').placeholder = "변경시에만 입력하세요";
                    } else {
                        alert(response.message || "비밀번호가 일치하지 않습니다.");
                        document.getElementById('current_pass').value = '';
                        document.getElementById('current_pass').focus();
                    }
                },
                error: function() {
                    alert("시스템 오류가 발생했습니다.");
                }
            });
        }

        function check_update_input() {
            // 비밀번호 확인 여부 체크
            if (!is_password_verified) {
                alert("먼저 현재 비밀번호 확인을 진행해주세요.");
                document.getElementById('current_pass').focus();
                return;
            }

            const pass = document.getElementById('pass').value;
            const pass_confirm = document.getElementById('pass_confirm').value;

            if (pass) {
                if (pass != pass_confirm) {
                    alert("새 비밀번호가 일치하지 않습니다.");
                    document.getElementById('pass_confirm').focus();
                    return;
                }
            }
            document.member_form.submit();
        }
    </script>
</head>
<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper">
        <div class="mypage-wrapper">
            <?php require_once("inc/mypage_sidebar.php"); ?>

            <section class="mypage-content">
                <div class="page-header" style="border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px;">
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0;">회원정보 수정</h2>
                </div>

                <div class="info-form-container">
                    <form name="member_form" method="POST" action="member_update.php">
                        
                        <div class="form-group">
                            <label class="form-label">아이디</label>
                            <div class="form-input-wrap">
                                <input type="text" class="form-input" value="<?= htmlspecialchars($m['id']) ?>" readonly style="background-color: #f0f0f0; width: 50%;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">현재 비밀번호</label>
                            <div class="form-input-wrap">
                                <input type="password" id="current_pass" name="current_pass" class="form-input" placeholder="현재 비밀번호를 입력하세요" style="width: auto; flex-grow: 1;">
                                <button type="button" id="btn_verify" class="btn-verify" onclick="verify_current_pass()">확인</button>
                                <span id="verify_msg" class="verify-msg"><i class="fas fa-check-circle"></i> 확인완료</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">새 비밀번호</label>
                            <div class="form-input-wrap">
                                <input type="password" id="pass" name="pass" class="form-input" placeholder="현재 비밀번호 확인 후 입력 가능" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">비밀번호 확인</label>
                            <div class="form-input-wrap">
                                <input type="password" id="pass_confirm" name="pass_confirm" class="form-input" placeholder="현재 비밀번호 확인 후 입력 가능" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">이름</label>
                            <div class="form-input-wrap">
                                <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($m['name']) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">휴대전화</label>
                            <div class="form-input-wrap">
                                <input type="text" name="phone" class="form-input" value="<?= htmlspecialchars($m['phone']) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">생년월일</label>
                            <div class="form-input-wrap">
                                <input type="text" name="birth" class="form-input" value="<?= htmlspecialchars($m['birth']) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">이메일</label>
                            <div class="form-input-wrap">
                                <input type="text" name="email1" class="form-input" value="<?= htmlspecialchars($email1) ?>" style="width: 40%;">
                                <span>@</span>
                                <input type="text" name="email2" class="form-input" value="<?= htmlspecialchars($email2) ?>" style="width: 40%;">
                            </div>
                        </div>

                        <div style="margin-top: 40px; text-align: center; display: flex; justify-content: center; gap: 10px;">
                            <button type="button" class="btn-submit" onclick="check_update_input()">수정완료</button>
                            <button type="button" class="btn-cancel" onclick="history.back()">취소</button>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>
</body>
</html>