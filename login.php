<?php
// ===== 기본 설정 =====
$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>로그인 - Meal Kitchen</title>

  <link rel="stylesheet" href="<?= $BASE ?>/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

  <?php require_once __DIR__ . '/inc/header.php'; ?>


  <main class="main_wrapper auth">
    <div id="login_wrapper"> <div class="login_start">
          <div class="login_title">로그인</div>
        </div>
        
        <div class="login_sns_wrapper">
          <button class="kakao_login">
            <span class="kakao_login_title">카카오 1초 로그인 / 회원가입</span>
          </button>
          <button class="naver_login">
            <span class="naver_login_title">네이버 로그인</span>
          </button>
        </div>
        
        <div class="login_or">
          <span class="login_or_title">OR</span>
        </div>
        
        <form method="post" action="<?= $BASE ?>/login.post.php" name="login_form" autocomplete="on">
          
          <div class="ID_wrapper">
            <input type="text" name="member_id" class="ID_field-container" placeholder="아이디를 입력하세요." required>
          </div>
          
          <div class="FW_wrapper">
            <input type="password" name="member_pw" class="FW_field-container" placeholder="비밀번호를 입력하세요." required>
          </div>

          <div class="login_keep_wrapper">
            <div class="ID_keep_check">
              <label><input type="checkbox" name="save_id"> 아이디 저장</label>
            </div>
            <div class="all_keep_check">
              <label><input type="checkbox" name="keep_login"> 로그인 상태 유지</label>
            </div>
          </div>

          <div class="finish_login_wrapper">
            <button type="button" class="finish_login" onclick="login()">
              <span class="finish_login_title">로그인</span>
            </button>
          </div>
        </form>

        <div class="find_wrapper">
          <ul>
            <li><a href="<?= $BASE ?>/find_account.php?type=id"><span>아이디 찾기</span></a></li>
            <li><a href="<?= $BASE ?>/find_account.php?type=pw"><span>비밀번호 찾기</span></a></li>
            <li class="none_border"><a href="<?= $BASE ?>/sign_up.php"><span>회원가입</span></a></li>
          </ul>
        </div>
        
    </div>
  </main>


  <?php require_once __DIR__ . '/inc/footer.php'; ?>

  <script src="js/member.js"></script>

</body>
</html>