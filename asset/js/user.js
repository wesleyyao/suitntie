$(document).ready(function () {
    const prefix = '/suitntie';
    const pagesRequiredLogin = ['dimension-test', 'user'];
    const pagesRequiredPhone = ['dimension-test'];
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    let message = '';
    const currentPage = decodeURIComponent(window.location.href);
    console.log(currentPage);
    $('#showSignupModal').click(function () {
        $('#userLoginModal').modal('hide');
        $('#userSignUpModal').modal('show');
    });

    $.get(`${prefix}/public/api/account.php`).done(function (data) {
        const result = JSON.parse(data);
        console.log(result)
        const pageTitle = $('#hide_title').val();
        if (result === 'no login' && pagesRequiredLogin.includes(pageTitle)) {
            $('#userLoginModal').modal('show');
            $('#mainContentDiv').fadeOut();
            message = generateMessage('warning', '未找到登录用户，请先<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>');
            $('#message').html(message);
        }
        user = result.user ? result.user : undefined;
        const noLogin = `<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录 | 注册</a>`;
        const isLogin = `<div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" id="userLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img id="loginUserAvatarInNav" src="" width="25" height="25" style="border-radius: 50%"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userLinks">
                <a class="dropdown-item" href="${prefix}/account/user.php">个人中心</a>
                <a class="dropdown-item" href="${prefix}/public/auth/user-logout.php">登出</a>
            </div>
        </div>`;
        $('#userInNav').html(user ? isLogin : noLogin);
        if (user) {
            $('#loginUserAvatarInNav').attr('src', user.headImg ? user.headImg : `${prefix}/asset/image/avatar.png`);
        }
        if (user && !user.phone) {
            if (pagesRequiredPhone.includes(pageTitle)) {
                //window.location.href = `${prefix}/account/user.php`;
                $('#profileCompletePhoneCell').css('display', 'block');
                $('#userProfileCompleteModal').modal('show');
                message = generateMessage('warning', '在开始测试前，请您点击<a href="#/" data-toggle="modal" data-target="#userProfileCompleteModal">此处</a>完善联系方式。');
                $('#message').html(message);
            }
        }
        return;
    });

    $('.submit-button').click(function () {
        const submitId = $(this).attr('id');
        const passwordFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
        if (submitId === 'signupByEmail') {
            let isValid = true;
            $('.signup-required').each(function () {
                if (!$(this).val().replace(/\s/g, '')) {
                    const emptyErrorMessage = generateMessage('warning', '请填写所有必填项。');
                    $('#signupMessage').html(emptyErrorMessage);
                    isValid = false;
                    return;
                }
            });
            if(!isValid){
                return;
            }
            const email = $('#signupEmail').val();
            const password = $('#signupPassword').val();
            const confirmPassword = $('#signupPasswordConfirm').val();
            const phone = $('#signupPhone').val();
            if(!validateEmailFormat($('#signupEmail').val())){
                $('#signupMessage').html(generateMessage('warning', '输入的邮箱格式错误。'));
                isValid = false;
            }
            if(!$('#signupPassword').val().match(passwordFormat)){
                $('#signupMessage').html(generateMessage('warning', `密码不符合要求。请点击小图标<i class="fas fa-info-circle text-info"></i>查看密码格式，重新输入。`));
                isValid = false;
            }
            if(password !== confirmPassword){
                $('#signupMessage').html(generateMessage('warning', `密码不一致，重新输入。`));
                isValid = false;
            }
            if (isValid) {
                $('#signupMessage').html(loading);
                $.post(`${prefix}/public/api/signup.php?redirect=${currentPage}`, { submit_signup: 'yes', usr_email: email, usr_password: password, usr_phone: phone }).done(function (data) {
                    if (data) {
                        let result = JSON.parse(data);
                        const message = generateMessage(result.type, result.content);
                        $('#signupMessage').html(message);
                    }
                });
            }
        }
    });

    $('#loginForm').submit(function (e) {
        if (!formValidation('.login-required', '#loginMessage', $('#loginEmail').val())) {
            e.preventDefault();
        }
    });

    $('#userProfileCompleteForm').submit(function (e) {
        e.preventDefault();
        const email = $('#toFinishEmail').val();
        const phone = $('#toFinishPhone').val();
        $('#profileCompleteMessage').html(loading);
        if (!formValidation('.profile-required', '#profileCompleteMessage', 'test@abc.ca')) {
            return;
        }
        $.post(`${prefix}/public/api/account.php`, { email, phone, isSaveContact: true }).done(function (data) {
            if (data) {
                window.location.href = `${prefix}/tests/dimension-test.php`;
            }
            else {
                $('#profileCompleteMessage').html(generateMessage('danger', '请求未被处理，请重试。'));
            }
        });
    });

    $('.close-signup-btn').click(function () {
        $('#userSignUpModal').modal('hide');
        $('#userLoginModal').modal('show');
    });

    function formValidation(className, message, email) {
        let isValid = true;
        $(className).each(function () {
            if (!$(this).val().replace(/\s/g, '')) {
                $(message).html(generateMessage('warning', '请填写所有必填项。'));
                isValid = false;
                return;
            }
            else if (!validateEmailFormat(email)) {
                $(message).html(generateMessage('warning', '输入的邮箱格式错误。'));
                isValid = false;
                return;
            }
            else {
                isValid = true;
                return;
            }
        });
        return isValid;
    }

    function validateEmailFormat(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return true;
        }
        return false;
    }

    function generateMessage(type, message) {
        return `
        <div class="row">
            <div class="col-12">
                <div class="alert alert-${type}">
                    <i class="${type == 'success' ? 'fas fa-check' : type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-times'}"></i> ${message}
                </div>
            </div>
        </div>`;
    }
});