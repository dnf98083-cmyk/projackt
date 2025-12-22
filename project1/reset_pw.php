<?php
require_once 'inc/session.php';

if (empty($_SESSION['reset_uid'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 재설정 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .reset-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php require_once 'inc/header.php'; ?>

    <main class="main_wrapper">
        <div class="reset-container">
            <h2 style="text-align:center; margin-bottom:30px;">비밀번호 재설정</h2>
            <form action="reset_pw_process.php" method="post" onsubmit="return checkPw()">
                <div class="form-group">
                    <label>새 비밀번호</label>
                    <input type="password" name="new_pw" id="new_pw" required placeholder="새 비밀번호 입력">
                </div>
                <div class="form-group">
                    <label>비밀번호 확인</label>
                    <input type="password" id="confirm_pw" required placeholder="비밀번호 다시 입력">
                </div>
                <button type="submit" class="btn-submit">변경하기</button>
            </form>
        </div>
    </main>

    <?php require_once 'inc/footer.php'; ?>

    <script>
        function checkPw() {
            const pw = document.getElementById('new_pw').value;
            const confirm = document.getElementById('confirm_pw').value;

            if (pw.length < 4) {
                alert('비밀번호는 4자 이상이어야 합니다.');
                return false;
            }

            if (pw !== confirm) {
                alert('비밀번호가 일치하지 않습니다.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>