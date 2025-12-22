<?php
// ===== 기본 설정 =====
$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE === '') $BASE = '/';
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    <title>회원가입 - Meal Kitchen</title>
</head>

<body>
    <?php require_once __DIR__ . '/inc/header.php'; ?>

    <main class="main_wrapper sign_up">
        
        <div class="progress">
            <div class="progress_step step1">
                <span class="title">STEP 1</span>
                <span>이용약관 동의</span>
            </div>
            <div class="progress_step step2 is-active">
                <span class="title">STEP 2</span>
                <span>회원정보 입력</span>
            </div>
            <div class="progress_step step3">
                <span class="title">STEP 3</span>
                <span>회원가입 완료</span>
            </div>
        </div>
        
        <span class="join_us_title">JOIN US</span>
        
        <div class="join_box">
            <form name="member_form" id="member_form" method="POST" action="member_insert.php" class="member_form" onsubmit="return validateSignUp();">
                <div class="member_form_col">
                    
                    <div class="ref">필수입력</div>
                    <div class="member_form_row row1">
                        
                        <div class="form id">
                            <div class="col1">아이디</div>
                            <div class="col2 flex-with-btn">
                                <input type="text" name="id" id="member_id" placeholder="아이디를 입력하세요." required>
                                <button type="button" class="btn btn--secondary btn--sm" onclick="check_id()">
                                    중복 확인
                                </button>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="form">
                            <div class="col1">비밀번호</div>
                            <div class="col2">
                                <input type="password" name="pass" placeholder="6~16자 영문, 숫자 특수문자 1개 이상의 혼용" required>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="form">
                            <div class="col1">비밀번호 확인</div>
                            <div class="col2">
                                <input type="password" name="pass_confirm" placeholder="비밀번호를 한번 더 입력하세요." required>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="form">
                            <div class="col1">이름</div>
                            <div class="col2">
                                <input type="text" name="name" placeholder="이름을 입력해 주세요." required>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="form phone">
                            <div class="col1">휴대전화</div>
                            <div class="col2 flex-with-btn">
                                <input type="text" name="phone" placeholder="본인인증 버튼을 눌러주세요." required>
                                <button type="button" class="btn btn--secondary btn--sm" onclick="self_certify()">
                                    본인인증 <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="ref">선택입력</div>
                    <div class="member_form_row row2">
                        
                        <div class="form">
                            <div class="col1">생년월일</div>
                            <div class="col2">
                                <input type="text" name="birth" placeholder="선택입력">
                            </div>
                        </div>
                        
                        <div class="form email">
                            <div class="col1">이메일</div>
                            <div class="col2 flex-with-btn">
                                <input type="text" name="email1" placeholder="이메일을 입력하세요.">@
                                <input type="text" name="email2" placeholder="직접입력">
                            </div>
                        </div>
                        
                        <div class="form">
                            <div class="col1">추천인 아이디</div>
                            <div class="col2">
                                <input type="text" name="refferer">
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </form>
        </div>
        
        <section class="action_buttons">
            <button class="button_join btn btn--primary btn--xl" onclick="check_input()">가입</button>
            <button class="button_cancel btn btn--secondary btn--xl" onclick="history.back()">취소</button>
        </section>

    </main>

    <?php require_once __DIR__ . '/inc/footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script> 
    <script src="js/member.js"></script>
</body>

</html>