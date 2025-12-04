<?php
$type = $_GET['type'] ?? 'id';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>계정 찾기 - Meal Kitchen</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .find-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .find-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
        }
        .find-tab {
            flex: 1;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            font-weight: bold;
            color: #888;
        }
        .find-tab.active {
            color: #333;
            border-bottom: 2px solid #333;
        }
        .find-form {
            display: none;
        }
        .find-form.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
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
        .btn-submit:hover {
            background: #555;
        }
        .result-box {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
            text-align: center;
            display: none;
        }
        .result-box.success {
            color: #28a745;
            border: 1px solid #28a745;
        }
        .result-box.error {
            color: #dc3545;
            border: 1px solid #dc3545;
        }
    </style>
</head>
<body>
    <?php require_once 'inc/header.php'; ?>

    <main class="main_wrapper">
        <div class="find-container">
            <div class="find-tabs">
                <div class="find-tab <?= $type === 'id' ? 'active' : '' ?>" onclick="switchTab('id')">아이디 찾기</div>
                <div class="find-tab <?= $type === 'pw' ? 'active' : '' ?>" onclick="switchTab('pw')">비밀번호 찾기</div>
            </div>

            <!-- 아이디 찾기 폼 -->
            <div id="form-id" class="find-form <?= $type === 'id' ? 'active' : '' ?>">
                <div class="form-group">
                    <label>이름</label>
                    <input type="text" id="id_name" placeholder="가입한 이름을 입력하세요">
                </div>
                <div class="form-group">
                    <label>휴대폰 번호</label>
                    <input type="text" id="id_phone" placeholder="가입한 휴대폰 번호를 입력하세요 (- 없이)">
                </div>
                <button class="btn-submit" onclick="findId()">아이디 찾기</button>
                <div id="result-id" class="result-box"></div>
            </div>

            <!-- 비밀번호 찾기 폼 -->
            <div id="form-pw" class="find-form <?= $type === 'pw' ? 'active' : '' ?>">
                <div class="form-group">
                    <label>아이디</label>
                    <input type="text" id="pw_id" placeholder="아이디를 입력하세요">
                </div>
                <div class="form-group">
                    <label>이름</label>
                    <input type="text" id="pw_name" placeholder="이름을 입력하세요">
                </div>
                <div class="form-group">
                    <label>휴대폰 번호</label>
                    <input type="text" id="pw_phone" placeholder="휴대폰 번호를 입력하세요 (- 없이)">
                </div>
                <button class="btn-submit" onclick="findPw()">비밀번호 재설정</button>
                <div id="result-pw" class="result-box"></div>
            </div>
        </div>
    </main>

    <?php require_once 'inc/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function switchTab(type) {
            document.querySelectorAll('.find-tab').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.find-form').forEach(el => el.classList.remove('active'));
            
            if (type === 'id') {
                document.querySelector('.find-tab:first-child').classList.add('active');
                document.getElementById('form-id').classList.add('active');
            } else {
                document.querySelector('.find-tab:last-child').classList.add('active');
                document.getElementById('form-pw').classList.add('active');
            }
        }

        function findId() {
            const name = $('#id_name').val();
            const phone = $('#id_phone').val();

            if (!name || !phone) {
                alert('이름과 휴대폰 번호를 모두 입력해주세요.');
                return;
            }

            $.post('find_process.php', { mode: 'find_id', name: name, phone: phone }, function(res) {
                const box = $('#result-id');
                box.show().removeClass('success error');
                
                if (res.status === 'success') {
                    box.addClass('success').html(`회원님의 아이디는 <strong>${res.id}</strong> 입니다.<br><a href="login.php" style="color:#333; text-decoration:underline; margin-top:10px; display:inline-block;">로그인하러 가기</a>`);
                } else {
                    box.addClass('error').text(res.message);
                }
            }, 'json');
        }

        function findPw() {
            const id = $('#pw_id').val();
            const name = $('#pw_name').val();
            const phone = $('#pw_phone').val();

            if (!id || !name || !phone) {
                alert('모든 정보를 입력해주세요.');
                return;
            }

            $.post('find_process.php', { mode: 'find_pw', id: id, name: name, phone: phone }, function(res) {
                const box = $('#result-pw');
                box.show().removeClass('success error');
                
                if (res.status === 'success') {
                    alert('정보가 확인되었습니다. 비밀번호 재설정 페이지로 이동합니다.');
                    location.href = 'reset_pw.php';
                } else {
                    box.addClass('error').text(res.message);
                }
            }, 'json');
        }
    </script>
</body>
</html>