// js/member.js

// ----------------------------------------------------
// 전역 상태 변수: 아이디 중복 확인 및 본인인증 상태
// ----------------------------------------------------
let id_checked = false; 
let is_verified = false; // 본인인증 성공 여부 플래그

// ----------------------------------------------------
// 1. 회원가입 유효성 검사 (check_input)
// ----------------------------------------------------
function check_input() {
    console.log('check_input() called');

    const form = document.member_form;

    // 1. 아이디 (id) 검사
    if (!form.id.value) {
        alert("아이디를 입력하세요!");
        form.id.focus();
        return;
    }
    
    // 1-A. 중복 확인 여부 검사
    if (!id_checked) {
        alert("아이디 중복 확인을 해주세요!");
        check_id(); 
        return;
    }


    // 2. 비밀번호 (pass) 검사
    if (!form.pass.value) {
        alert("비밀번호를 입력하세요!");
        form.pass.focus();
        return;
    }

    // 3. 비밀번호 확인 (pass_confirm) 검사
    if (!form.pass_confirm.value) {
        alert("비밀번호 확인을 입력하세요!");
        form.pass_confirm.focus();
        return;
    }

    // 4. 이름 (name) 검사
    if (!form.name.value) {
        alert("이름을 입력하세요!");
        form.name.focus();
        return;
    }

    // 5. 휴대전화 (phone) 검사
    if (!form.phone.value) {
        alert("휴대전화 번호를 입력하세요!");
        form.phone.focus();
        return;
    }
    
    // ✅ 5-A. 본인인증 여부 검사
    if (!is_verified) {
        alert("본인인증을 완료해야 합니다.");
        self_certify(); // 인증 함수를 바로 호출하여 사용자 편의 제공
        return;
    }


    // 6. 비밀번호 일치 확인
    if (form.pass.value !== form.pass_confirm.value) {
        alert("비밀번호가 일치하지 않습니다.\n다시 입력해 주세요!");
        form.pass.focus();
        form.pass.select();
        return;
    }

    // 모든 검사 통과 시 폼 전송
    form.submit();
}

// ----------------------------------------------------
// 2. 로그인 폼 전송 (login)
// ----------------------------------------------------
function login() {
    console.log('login() called');
    document.login_form.submit();
}


// ----------------------------------------------------
// 3. 아이디 중복 확인 AJAX 로직 (check_id)
// ----------------------------------------------------
function check_id() {
    const idInput = document.getElementById('member_id');
    const memberId = idInput?.value?.trim();

    if (!memberId) {
        alert('아이디를 먼저 입력해 주세요.');
        idInput.focus();
        return;
    }

    // AJAX 요청 (jQuery 사용)
    $.ajax({
        url: 'check_id.php',
        type: 'POST',
        data: { id: memberId },
        dataType: 'json',
        success: function(response) {
            alert(response.message);
            if (response.result === 'success') {
                id_checked = true;
                console.log('아이디 중복 확인 성공');
            } else {
                id_checked = false;
                idInput.focus();
            }
        },
        error: function() {
            alert('아이디 중복 확인 중 오류가 발생했습니다.');
            id_checked = false; 
        }
    });
}


// ----------------------------------------------------
// 4. 본인인증 함수 (Mock: 가짜 성공 처리)
// ----------------------------------------------------
function self_certify() {
    const phoneInput = document.forms['member_form']['phone'];
    if (phoneInput && phoneInput.value.length < 10) {
        alert('휴대폰 번호를 정확히 입력해주세요.');
        phoneInput.focus();
        return;
    }

    if (confirm('본인인증을 성공 처리하시겠습니까? (Mock)')) {
        is_verified = true;
        alert('✅ 본인인증이 완료되었습니다.');
        
        // UI 변경: 버튼 텍스트와 색상 변경
        const certifyButton = document.querySelector('.form.phone button');
        if (certifyButton) {
            certifyButton.textContent = '인증 완료';
            certifyButton.style.backgroundColor = 'green';
            certifyButton.style.color = 'white';
        }
    }
}


// ----------------------------------------------------
// 5. 이벤트 리스너: 아이디 변경 시 중복 확인 상태 초기화
// ----------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const idInput = document.getElementById('member_id');
    if (idInput) {
        // 아이디 입력 필드에 값이 변경될 경우 중복 확인 상태 초기화
        idInput.addEventListener('input', function() {
            if (id_checked) {
                id_checked = false;
                console.log('아이디 변경 감지, 중복 확인 상태 초기화');
            }
        });
    }
});

// ----------------------------------------------------
// 6. 기타 JS 코드 (기존 코드가 있다면 이 아래에 위치)
// ----------------------------------------------------

/*
const SortSales = document.querySelector('.sort_nav01');
// (중략)
SortSales.addEventListener('click', function () {
// (중략)
});
*/