import { validateEmailFormat, generateMessage, prefix } from './common.js';

$(document).ready(function () {
    const pagesRequiredLogin = ['dimension-test', 'user'];
    const pagesRequiredPhone = ['dimension-test'];
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    const loginSuccessMessage = `
    <div class="alert alert-success" role="alert">
        <div class="spinner-border text-success" role="status" style="width: 1.3rem; height: 1.3rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <span>登录成功，正在为您准备数据...</span>
    </div>`;
    let message = '';
    let code = 0;
    let emailCode = 0;
    let expireTime = undefined;
    let emailExpireTime = undefined;
    let currentPage = decodeURIComponent(window.location.href);
    if (currentPage.indexOf('#') > -1) {
        currentPage = currentPage.substring(0, currentPage.indexOf('#'));
    }

    $('#showSignupModal').click(function () {
        $('#userLoginModal').modal('hide');
        $('#userSignUpModal').modal('show');
    });

    $('.carousel').carousel({
        interval: 6000
    });
    const toRemoveUrl = currentPage.indexOf('www') > -1 ? 'https://www.suitntie.cn' : 'https://suitntie.cn';
    const redirectUrl = currentPage.replace(toRemoveUrl, "");

    const obj = new WxLogin
        ({
            id: "login_container",//div的id
            appid: "wxcfeb0aba33ebba0c",
            scope: "snsapi_login",//写死
            redirect_uri: encodeURI("https://www.suitntie.cn/public/auth/wechat-login.php?redirect=" + redirectUrl),
            state: "wechatLogin",
            style: "black",//二维码黑白风格        
            href: ""
        });

    const obj2 = new WxLogin
        ({
            id: "signup_container",//div的id
            appid: "wxcfeb0aba33ebba0c",
            scope: "snsapi_login",//写死
            redirect_uri: encodeURI("https://www.suitntie.cn/public/auth/wechat-login.php?redirect=" + redirectUrl),
            state: "wechatLogin",
            style: "black",//二维码黑白风格        
            href: ""
        });
    fetchAccount();

    $('.send-verify-code').click(function () {
        const from = $(this).attr('id');
        const buttonId = '#' + from;
        const messageDiv = from === 'sendSignupCode' ? '#profileCompleteMessage' : '#loginByPhoneMessage';
        const phoneInputId = from === 'sendSignupCode' ? '#signupPhone' : '#loginPhone';
        const phone = $(phoneInputId).val().replace(/\s/g, '');
        if (phone.length !== 11 || isNaN(phone) || !phone) {
            $(messageDiv).html(generateMessage('warning', '请输入正确的手机号'));
            return;
        }

        $(buttonId).prop('disabled', true);
        let seconds = 45;
        let cooling = setInterval(function () {
            if (seconds > 0) {
                seconds--;
                $(buttonId).text(`${seconds}秒后重新发送`);
            }
            else {
                $(buttonId).prop('disabled', false);
                $(buttonId).text('获取验证码');
                clearInterval(cooling);
            }
        }, 1000);

        code = Math.floor(1000 + Math.random() * 9000);
        const currentTime = new Date();
        expireTime = new Date(currentTime.getTime() + 1 * 60000);
        $(messageDiv).html(loading);
        $.post(`${prefix}/public/api/phone-verify-local.php`, { phone, code }).done(function (data) {
            if (data) {
                //const result = { SendStatusSet: [{ Code: 'Ok', PhoneNumber: '13141036635' }] };
                const result = JSON.parse(data);
                const sendStatus =
                    result &&
                        result.SendStatusSet &&
                        Array.isArray(result.SendStatusSet) &&
                        result.SendStatusSet.length > 0 ? result.SendStatusSet[0] : undefined;
                if (!sendStatus) {
                    $(messageDiv).html(generateMessage('warning', '连接出现异常，请刷新后重试。'));
                    return;
                }
                if (sendStatus.Code === 'Ok' && sendStatus.PhoneNumber === '+86' + phone) {
                    $(messageDiv).html(generateMessage('success', '验证码已经发送。'));
                    return;
                }
            }
            else {
                $(messageDiv).html(generateMessage('warning', '连接出现异常，请刷新后重试。'));
            }
        });
    });


    $('#sendEmailVerifyCode').click(function () {
        emailCode = Math.floor(1000 + Math.random() * 9000);
        const currentTime = new Date();
        emailExpireTime = new Date(currentTime.getTime() + 5 * 60000);
        const email = $('#signupEmail').val().replace(/\s/g, '').toLowerCase();
        if (!email || !validateEmailFormat(email)) {
            $('#signupEmailVerifyMessage').html(generateMessage('warning', '请输入正确的邮箱。'));
            return;
        }
        $('#sendEmailVerifyCode').prop('disabled', true);

        $('#signupEmailVerifyMessage').html(generateMessage('info', '正在发送验证邮件...'));
        let seconds = 45;
        let cooling = setInterval(function () {
            if (seconds > 0) {
                seconds--;
                $('#sendEmailVerifyCode').text(`${seconds}秒后重新发送`);
            }
            else {
                $('#sendEmailVerifyCode').prop('disabled', false);
                $('#sendEmailVerifyCode').text('获取验证码');
                clearInterval(cooling);
            }
        }, 1000);
        $.post(`${prefix}/public/api/signup.php?by=email`, { email, code: emailCode }).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                if (result.content == '该邮箱已经注册过。') {
                    $('#signupEmailVerifyMessage').html(generateMessage(result.type, result.content));
                    $('#sendEmailVerifyCode').prop('disabled', false);
                    $('#sendEmailVerifyCode').text('获取验证码');
                    clearInterval(cooling);
                    return;
                }
                $('#signupEmailVerifyMessage').html(generateMessage(result.type, result.content));
                return;
            }
            else {
                $('#signupEmailVerifyMessage').html(generateMessage('warning', '该请求无法被处理，请刷新后重试。'));
                return;
            }
        });
    });

    $('#showLoginModal').click(function () {
        $('#userSignUpModal').modal('hide');
        $('#userLoginModal').modal('show');
    });

    $('.signup-button').click(function () {
        const submitId = $(this).attr('id');
        const passwordFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
        const current_time = new Date();
        if (submitId === 'signupByEmail') {
            let isValid = true;
            $('.signup-required').each(function () {
                if (!$(this).val().replace(/\s/g, '')) {
                    $('#signupByEmailMessage').html(generateMessage('warning', '请填写所有必填项。'));
                    isValid = false;
                    return;
                }
            });
            if (!isValid) {
                $('#signupByEmailMessage').html(generateMessage('warning', '请检查您的输入。'));
                return;
            }
            const email = $('#signupEmail').val().replace(/\s/g, '').toLowerCase();
            const password = $('#signupPassword').val().replace(/\s/g, '');
            const confirmPassword = $('#signupPasswordConfirm').val().replace(/\s/g, '');
            const code = $('#signupEmailCode').val().replace(/\s/g, '');
            if (!email || !validateEmailFormat(email)) {
                $('#signupByEmailMessage').html(generateMessage('warning', '输入的邮箱格式错误。'));
                return;
            }
            if (!password.match(passwordFormat)) {
                $('#signupByEmailMessage').html(generateMessage('warning', `密码不符合要求。请点击小图标<i class="fas fa-info-circle text-info"></i>查看密码格式，重新输入。`));
                return;
            }
            if (password !== confirmPassword) {
                $('#signupByEmailMessage').html(generateMessage('warning', `密码不一致，重新输入。`));
                return;
            }
            if (!code || parseInt(code) !== emailCode) {
                $('#signupByEmailMessage').html(generateMessage('warning', `验证码错误。`));
                return;
            }
            if (current_time > emailExpireTime) {
                $('#signupByEmailMessage').html(generateMessage('warning', '您的验证码已过期。'));
                return;
            }
            $('#signupByEmailMessage').html(loading);
            $.post(`${prefix}/public/auth/user-signup.php`,
                { by: 'email', email, password }).done(function (data) {
                    if (data) {
                        let result = JSON.parse(data);
                        $('#signupByEmailMessage').html(generateMessage(result.type, result.content));
                        if (result.type === 'success') {
                            setTimeout(function () {
                                window.location.href = currentPage;
                            }, 2000);
                        }
                    }
                    else {
                        $('#signupByEmailMessage').html(generateMessage('warning', '请求无法处理，请刷新重试。'));
                    }
                });
        }
    });

    $('.login-button').click(function () {
        const target = $(this).attr('id');
        let formData = undefined;
        if (target === 'loginByPhone') {
            const phone = $('#loginPhone').val().replace(/\s/g, '');
            const verifyCode = $('#loginVerifyCode').val().replace(/\s/g, '');
            const current_time = new Date();
            if (!phone || !verifyCode) {
                $('#loginByPhoneMessage').html(generateMessage('warning', '请填写必填的项目。'));
                return;
            }
            if (isNaN(phone)) {
                $('#loginByPhoneMessage').html(generateMessage('warning', '请填写正确的手机号（11位）。'));
                return;
            }
            if (parseInt(verifyCode) !== code) {
                $('#loginByPhoneMessage').html(generateMessage('warning', '验证码不匹配，请重试。'));
                return;
            }
            if (current_time > expireTime) {
                $('#loginByPhoneMessage').html(generateMessage('warning', '您的验证码已过期。'));
                return;
            }
            formData = { phone, by: 'phone' };
        }
        else {
            const email = $('#loginEmail').val().replace(/\s/g, '').toLowerCase();
            const password = $('#loginPassword').val().replace(/\s/g, '');
            if (!email || !password) {
                $('#loginByEmailMessage').html(generateMessage('warning', '请填写必填的项目。'));
                return;
            }
            formData = { email, password, by: 'email' };
        }
        $.post(`${prefix}/public/auth/user-login.php`, formData).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                const messageDiv = target === 'loginByPhone' ? '#loginByPhoneMessage' : '#loginByEmailMessage';
                if (result.type === 'success') {
                    $(messageDiv).html(loginSuccessMessage);
                    setTimeout(function () {
                        window.location.href = currentPage;
                    }, 2000);
                }
                else{
                    $(messageDiv).html(generateMessage(result.type, result.content));
                }
            }
        });
    });

    $('#userProfileCompleteForm').submit(function (e) {
        e.preventDefault();
        const phone = $('#signupPhone').val().replace(/\s/g, '');;
        if (!phone || isNaN(phone) || phone.length !== 11) {
            $('#profileCompleteMessage').html(generateMessage('danger', '请填写有效的手机号。'));
            return;
        }
        $('#profileCompleteMessage').html(loading);
        $.post(`${prefix}/public/auth/user-signup.php`, { phone, by: 'phone' }).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                $('#profileCompleteMessage').html(generateMessage(result.type, result.content));
                if (result.type === 'success') {
                    setTimeout(function () {
                        window.location.href = currentPage;
                    }, 2000);
                }
            }
            else {
                $('#profileCompleteMessage').html(generateMessage('danger', '请求未被处理，请重试。'));
                return;
            }
        });
    });

    $('.close-signup-btn').click(function () {
        $('#userSignUpModal').modal('hide');
        $('#userLoginModal').modal('show');
    });

    $('#startTesting').click(function () {
        $('#testOrView').fadeOut();
        $('#mainContentDiv').fadeIn();
        $('html, body').animate({
            scrollTop: $("#message").offset().top - 130
        }, 1000);
    });

    function fetchAccount() {
        $('#testOrView').hide();
        $.get(`${prefix}/public/api/account.php`).done(function (data) {
            console.log(data)
            const result = JSON.parse(data);
            const pageTitle = $('#hide_title').val();
            if (result === 'no login' && pagesRequiredLogin.includes(pageTitle)) {
                $('#userLoginModal').modal('show');
                $('#mainContentDiv').fadeOut();
                message = generateMessage('warning', '未找到登录用户，请先<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>');
                $('#message').html(message);
            }
            if(result === 'no login' && currentPage.indexOf('/programs/explore') !== -1){
                let blurContent = `
                <div class="row">
                <div class="col-12">
                        <div class="alert alert-warning text-center" role="alert">
                            浏览下面内容需要先登录 <a href="#/" data-toggle="modal" data-target="#userSignUpModal" class="btn btn-warning">点击登录</a>
                        </div>
                    </div>
                    </div>
                <div class="row">
                    <div class="col-12">
                        <img src="${prefix}/asset/image/${window.innerWidth > 768 ? 'program-blur' : 'program-blur-mobile'}.png" style="width: 100%; height: auto;" alt="blur content" />
                    </div>
                </div>`;
                $('#membershipContentDiv').html(blurContent);
            }
            const user = result.user ? result.user : undefined;
            const noLogin = `<a href="#/" data-toggle="modal" data-target="#userSignUpModal">登录 | 注册</a>`;
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
                $('#testOrView').show();
                $('#loginUserAvatarInNav').attr('src', user.headImg ? user.headImg : `${prefix}/asset/image/avatar.png`);
            }
            let isPageRequirePhone = 0;
            pagesRequiredPhone.forEach(function (item) {
                if (currentPage.indexOf(item) > -1) {
                    isPageRequirePhone = 1;
                    return;
                }
            });

            if (isPageRequirePhone == 1 && user && !user.email && !user.phone) {
                $('#mainContentDiv').fadeOut();
                $('#userProfileCompleteModal').modal('show');
                $('#message').html(generateMessage(
                    'warning',
                    '在开始测试前，请您点击<a href="#/" data-toggle="modal" data-target="#userProfileCompleteModal">此处</a>完善联系方式。'));
                return;
            }
            if (pageTitle == 'dimension-test' && user) {
                $('#mainContentDiv').hide();
            }
        });
    }
});