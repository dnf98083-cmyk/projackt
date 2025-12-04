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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <title>회원정보 수정 - Meal Kitchen</title>
    <style>
        /* my-page.css의 기본 스타일을 보완 및 재정의 */
        .main_wrapper.my-page {
            display: block; /* 사이드바 제거로 인한 레이아웃 변경 */
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .main_wrapper.my-page .view {
            width: 100%;
            border: none;
            padding: 0;
        }
        .main_wrapper.my-page .join_box {
            height: auto; /* 고정 높이 제거 */
            padding: 40px;
            display: flex;
            justify-content: center;
            border: none; /* 테두리 제거 (mypage-content 내부로 이동 시) */
            margin-top: 0;
        }
        .main_wrapper.my-page .join_box .member_form {
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
        }
        .main_wrapper.my-page .join_box .member_form .form {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            width: 100%;
        }
        .main_wrapper.my-page .join_box .member_form .form .col1 {
            width: 140px;
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }
        .main_wrapper.my-page .join_box .member_form .form .col2 {
            flex: 1;
            display: flex;
            align-items: center;
        }
        .main_wrapper.my-page .join_box .member_form .form input[type="text"],
        .main_wrapper.my-page .join_box .member_form .form input[type="password"] {
            width: 100%;
            height: 40px;
            padding: 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .main_wrapper.my-page .join_box .member_form .form.email .col2 {
            display: flex;
            align-items: center;
        }
        .main_wrapper.my-page .join_box .member_form .form.email input[type="text"] {
            width: 45%;
        }
        .main_wrapper.my-page .join_box .member_form .form.id input {
            background-color: #f5f5f5;
            color: #666;
            border: 1px solid #ccc;
        }
        
        /* 상품 등록 버튼 스타일 */
        .btn-product-add {
            display: block;
            width: 100%;
            max-width: 600px;
            margin: 20px auto 0;
            padding: 15px;
            background: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.2s;
        }
        .btn-product-add:hover {
            background: #45a049;
        }
        .btn-product-add i {
            margin-right: 8px;
        }
        
        /* 버튼 스타일 */
        .button_area {
            margin-top: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .button_join, .button_cancel {
            width: 120px;
            height: 45px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .button_join {
            background-color: #ff8b8b;
            color: white;
            border: none;
        }
        .button_join:hover {
            background-color: #ff6b6b;
        }
        .button_cancel {
            background-color: #fff;
            color: #666;
            border: 1px solid #ddd;
        }
        .button_cancel:hover {
            background-color: #f9f9f9;
        }
        
        /* 비밀번호 확인 버튼 스타일 */
        .main_wrapper.my-page .btn_verify {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            padding: 0 20px;
            margin-left: 10px;
            background-color: #333;
            color: #fff;
            border: 1px solid #333;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .main_wrapper.my-page .btn_verify:hover {
            background-color: #555;
            border-color: #555;
        }
        .verify_msg {
            margin-left: 10px;
            font-size: 13px;
            font-weight: bold;
            color: #0085ff;
            display: none;
            white-space: nowrap;
        }

        /* 모바일 반응형 */
        @media (max-width: 768px) {
            .main_wrapper.my-page .view {
                padding: 0 15px;
            }
            .main_wrapper.my-page .join_box {
                padding: 20px 10px;
                border: none;
            }
            .main_wrapper.my-page .join_box .member_form .form {
                flex-direction: column;
                align-items: flex-start;
            }
            .main_wrapper.my-page .join_box .member_form .form .col1 {
                width: 100%;
                margin-bottom: 5px;
            }
            .main_wrapper.my-page .join_box .member_form .form .col2 {
                width: 100%;
                flex-wrap: wrap;
            }
            .main_wrapper.my-page .btn_verify {
                margin-left: 0;
                margin-top: 5px;
                width: 100%;
            }
            .verify_msg {
                margin-left: 0;
                margin-top: 5px;
                width: 100%;
            }
        }
    </style>
    <script>
        let is_password_verified = false;

        function verify_current_pass() {
            const current_pass = document.member_form.current_pass.value;
            if (!current_pass) {
                alert("현재 비밀번호를 입력해주세요.");
                document.member_form.current_pass.focus();
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
                        document.querySelector('.btn_verify').style.display = 'none';
                        document.querySelector('.verify_msg').style.display = 'inline-block';
                        document.member_form.current_pass.readOnly = true;
                        document.member_form.current_pass.style.backgroundColor = '#f0f0f0';
                        
                        // 새 비밀번호 입력 활성화
                        document.member_form.pass.disabled = false;
                        document.member_form.pass_confirm.disabled = false;
                        document.member_form.pass.placeholder = "변경시에만 입력하세요";
                        document.member_form.pass_confirm.placeholder = "변경시에만 입력하세요";
                    } else {
                        alert(response.message || "비밀번호가 일치하지 않습니다.");
                        document.member_form.current_pass.value = '';
                        document.member_form.current_pass.focus();
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
                document.member_form.current_pass.focus();
                return;
            }

            if (!document.member_form.pass.value) {
                // 비밀번호 변경 안함 -> 통과
            } else {
                if (document.member_form.pass.value != document.member_form.pass_confirm.value) {
                    alert("새 비밀번호가 일치하지 않습니다.");
                    document.member_form.pass_confirm.focus();
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
                <div class="member_pwd_title" style="text-align: left; font-size: 24px; font-weight: bold; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ddd;"><span>회원 정보 수정</span></div>
                <div class="join_box">
                    <form name="member_form" method="POST" action="member_update.php" class="member_form">
                        
                        <div class="form id">
                            <div class="col1">아이디</div>
                            <div class="col2">
                                <input type="text" name="id" value="<?= htmlspecialchars($m['id']) ?>" readonly>
                            </div>
                        </div>

                        <div class="form">
                        <div class="col1">현재 비밀번호</div>
                        <div class="col2">
                            <input type="password" name="current_pass" placeholder="현재 비밀번호를 입력하세요" style="flex: 1; width: auto;">
                            <button type="button" class="btn_verify" onclick="verify_current_pass()">비밀번호 확인</button>
                            <span class="verify_msg">비밀번호 확인완료 <i class="fas fa-check"></i></span>
                        </div>
                    </div>
                    
                    <div class="form">
                        <div class="col1">새 비밀번호</div>
                        <div class="col2">
                            <input type="password" name="pass" placeholder="현재 비밀번호 확인 후 입력 가능" disabled>
                        </div>
                    </div>
                    
                    <div class="form">
                        <div class="col1">비밀번호 확인</div>
                        <div class="col2">
                            <input type="password" name="pass_confirm" placeholder="현재 비밀번호 확인 후 입력 가능" disabled>
                        </div>
                    </div>

                    <div class="form">
                        <div class="col1">이름</div>
                        <div class="col2">
                            <input type="text" name="name" value="<?= htmlspecialchars($m['name']) ?>">
                        </div>
                    </div>
                    
                    <div class="form">
                        <div class="col1">휴대전화</div>
                        <div class="col2">
                            <input type="text" name="phone" value="<?= htmlspecialchars($m['phone']) ?>">
                        </div>
                    </div>
                    
                    <div class="form">
                        <div class="col1">생년월일</div>
                        <div class="col2">
                            <input type="text" name="birth" value="<?= htmlspecialchars($m['birth']) ?>">
                        </div>
                    </div>
                    
                    <div class="form email">
                        <div class="col1">이메일</div>
                        <div class="col2">
                            <input type="text" name="email1" value="<?= htmlspecialchars($email1) ?>"> 
                            <span style="margin: 0 10px;">@</span>
                            <input type="text" name="email2" value="<?= htmlspecialchars($email2) ?>">
                        </div>
                    </div>

                    <div class="button_area">
                        <button type="button" class="button_join" onclick="check_update_input()">수정완료</button>
                        <button type="button" class="button_cancel" onclick="history.back()">취소</button>
                    </div>

                </form>
            </div>
            </section>
        </div>
    </main>

    <?php require_once("inc/fast_move.php"); ?>
    <?php require_once("inc/footer.php"); ?>

    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="js/hot_issue.js"></script>
    <script src="js/member.js"></script>

</body>

</html>