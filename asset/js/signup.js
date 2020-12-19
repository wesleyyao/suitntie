import { prefix, isWeixin, validateEmailFormat, showFloatMessage, submittingMessage, useMessage, fetchAccount, currentPage } from './common.js';

$(document).ready(function () {
    $('#signup_mobile_container').hide();
    let emailCode = 0;
    let emailExpireTime = undefined;

    const redirectTo = location.search ? location.search.replace('?redirect=', '') : `${prefix}/index.html`;
    const isWechatBrowser = isWeixin();
    let page = currentPage;
    if (page.indexOf('#') > -1) {
        page = page.substring(0, page.indexOf('#'));
    }
    const toRemoveUrl = page.indexOf('www') > -1 ? 'https://www.suitntie.cn' : 'https://suitntie.cn';
    let redirectUrl = page.indexOf('redirect') !== -1 ? page.split('redirect=')[1] : "/";
    let redirectMobile = encodeURI("https://www.suitntie.cn/public/auth/wechat-login-mobile.php?redirect=" + redirectUrl);

    fetchAccount();

    const obj = new WxLogin
        ({
            id: "signup_container",//div的id
            appid: "wxcfeb0aba33ebba0c",
            scope: "snsapi_login",//写死
            redirect_uri: encodeURI("https://www.suitntie.cn/public/auth/wechat-login.php?redirect=" + redirectUrl),
            state: "wechatLogin",
            style: "black",//二维码黑白风格        
            href: ""
        });

    if (isWechatBrowser) {
        $('#signup_mobile_container').show();
        $('#signup_container').hide();
        $('#signup_wechat_mobile_link').prop('href', `https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx25d424c51ed0650d&redirect_uri=${redirectMobile}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect`);
    }

    $('.signup-method-btn').click(function () {
        const method = $(this).attr('id').replace('Btn', '');
        $('#' + method + 'Div').fadeIn(300);
        $(this).addClass('btn-active');
        $(this).siblings().removeClass('btn-active');
        $('.login-div').each(function () {
            if ($(this).attr('id').indexOf(method) > -1) {
                $(this).show();
            }
            else {
                $(this).hide();
            }
        });
    });

    $('#sendEmailVerifyCode').click(function () {
        let errorMessage = '';
        emailCode = Math.floor(1000 + Math.random() * 9000);
        const currentTime = new Date();
        emailExpireTime = new Date(currentTime.getTime() + 5 * 60000);
        const email = $('#signupEmail').val().replace(/\s/g, '').toLowerCase();
        if (!email || !validateEmailFormat(email)) {
            errorMessage = useMessage('warning', '请输入正确的邮箱。');
            showFloatMessage('#signupMsg', errorMessage, 3000);
            return;
        }
        $('#sendEmailVerifyCode').prop('disabled', true);

        errorMessage = useMessage('info', '正在发送验证邮件...');
        showFloatMessage('#signupMsg', errorMessage, 0);

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
                    errorMessage = useMessage(result.type, result.content);
                    $('#sendEmailVerifyCode').prop('disabled', false);
                    $('#sendEmailVerifyCode').text('获取验证码');
                    clearInterval(cooling);
                }
                else{
                    errorMessage = useMessage(result.type, result.content);
                }
            }
            else {
                errorMessage = useMessage('warning', '该请求无法被处理，请刷新后重试。');
            }
            showFloatMessage('#signupMsg', errorMessage, 3000);
        });
    });

    $('#signupByEmail').click(function () {
        let message = '';
        const passwordFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
        const current_time = new Date();
        let isValid = true;
        $('.signup-required').each(function () {
            if (!$(this).val().replace(/\s/g, '')) {
                message = useMessage('warning', '请填写所有必填项。');
                showFloatMessage('#signupMsg', message, 3000);
                isValid = false;
                return;
            }
        });
        if (!isValid) {
            message = useMessage('warning', '请填写所有必填项目。');
            showFloatMessage('#signupMsg', message, 3000);
            return;
        }
        const email = $('#signupEmail').val().replace(/\s/g, '').toLowerCase();
        const password = $('#signupPassword').val().replace(/\s/g, '');
        const confirmPassword = $('#signupPasswordConfirm').val().replace(/\s/g, '');
        const code = $('#signupEmailCode').val().replace(/\s/g, '');
        if (!email || !validateEmailFormat(email)) {
            message = useMessage('warning', '输入的邮箱格式错误。');
            showFloatMessage('#signupMsg', message, 3000);
            return;
        }
        if (!password.match(passwordFormat)) {
            message = useMessage('warning', `密码不符合要求。请点击小图标<i class="fas fa-info-circle text-info"></i>查看密码格式，重新输入。`);
            showFloatMessage('#signupMsg', message, 3000);
            return;
        }
        if (password !== confirmPassword) {
            message = useMessage('warning', `密码不一致，重新输入。`);
            showFloatMessage('#signupMsg', message, 3000);
            return;
        }
        if (!code || parseInt(code) !== emailCode) {
            message = useMessage('warning', `验证码错误。`);
            showFloatMessage('#signupMsg', message, 3000);
            return;
        }
        if (current_time > emailExpireTime) {
            message = useMessage('warning', `您的验证码已过期。`);
            showFloatMessage('#signupMsg', message, 3000);
            return;
        }
        showFloatMessage('#signupMsg', submittingMessage, 0);
        $.post(`${prefix}/public/auth/user-signup.php`,
            { by: 'email', email, password, redirectTo }).done(function (data) {
                if (data) {
                    let result = JSON.parse(data);
                    message = useMessage(result.type, result.content);
                    showFloatMessage('#signupMsg', submittingMessage, 3000);
                    if (result.type === 'success') {
                        setTimeout(function () {
                            if(!redirectTo || redirectTo.indexOf('login') > -1 || redirectTo.indexOf('signup') > -1){
                                window.location.href = `${prefix}/index.html`;
                            }
                            else{
                                window.location.href = redirectTo;
                            }
                        }, 2000);
                    }
                }
                else {
                    message = useMessage('warning', `请求无法处理，请刷新重试。`);
                    showFloatMessage('#signupMsg', message, 3000);
                }
            });
    });
});