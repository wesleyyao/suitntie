import { prefix, isWeixin, fetchAccount, loadingMessage, showFloatMessage, currentPage, useMessage } from './common.js';

$(document).ready(function(){
    $('#login_mobile_container').hide();

    let code = 0;
    let expireTime = undefined;
    const loginSuccessMessage = `
    <div class="alert alert-success" role="alert">
        <div class="spinner-border text-success" role="status" style="width: 1.3rem; height: 1.3rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <span>登录成功，正在为您准备数据...</span>
    </div>`;

    let redirectTo = location.search ? location.search.replace('?redirect=','') : `${prefix}/index.html`;
    //redirectTo = encodeURI('https://www.suitntie.cn/' + redirectTo);
    const isWechatBrowser = isWeixin();
    let page = currentPage;
    if (currentPage.indexOf('#') > -1) {
        page = currentPage.substring(0, currentPage.indexOf('#'));
    }
    const toRemoveUrl = page.indexOf('www') > -1 ? 'https://www.suitntie.cn' : 'https://suitntie.cn';
    let redirectUrl = page.indexOf('redirect') !== -1 ? page.split('redirect=')[1] : "/";
    let redirectMobile = encodeURI("https://www.suitntie.cn/public/auth/wechat-login-mobile.php?redirect=" + redirectUrl);
    fetchAccount();

    const obj = new WxLogin({
        id: "login_container",//div的id
        appid: "wxcfeb0aba33ebba0c",
        scope: "snsapi_login",//写死
        redirect_uri: encodeURI("https://www.suitntie.cn/public/auth/wechat-login.php?redirect=" + redirectUrl),
        state: "wechatLogin",
        style: "black",//二维码黑白风格        
        href: ""
    });

    if (isWechatBrowser) {
        $('#phoneLoginBtn').removeClass('btn-active');
        $('#wechatLoginBtn').addClass('btn-active');

        $('#phoneLoginDiv').hide();
        $('#wechatLoginDiv').show();

        $('#login_wechat_mobile_link').prop('href', `https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx25d424c51ed0650d&redirect_uri=${redirectMobile}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect`);
        $('#login_mobile_container').show();
        $('#login_container').hide();
    }

    $('.login-method-btn').click(function(){
        const method = $(this).attr('id').replace('Btn', '');
        $('#' + method + 'Div').fadeIn(300);
        $(this).addClass('btn-active');
        $(this).siblings().removeClass('btn-active');
        $('.login-div').each(function(){
            if($(this).attr('id').indexOf(method) > -1){
                $(this).show();
            }
            else{
                $(this).hide();
            }
        });
    });

    $('#sendPhoneVerifyCodeBtn').click(function () {
        let errorMessage = '';
        const from = $(this).attr('id');
        const buttonId = '#' + from;
        const phone = $('#loginPhone').val().replace(/\s/g, '');
        if (phone.length !== 11 || isNaN(phone) || !phone) {
            errorMessage = useMessage('warning', '请输入正确的手机号。');
            showFloatMessage('#authMsg', errorMessage, 3000);
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
                    errorMessage = useMessage('warning', '连接出现异常，请刷新后重试。');
                    showFloatMessage('#authMsg', errorMessage, 3000);
                    return;
                }
                if (sendStatus.Code === 'Ok' && sendStatus.PhoneNumber === '+86' + phone) {
                    errorMessage = useMessage('success', '验证码已经发送。');
                    showFloatMessage('#authMsg', errorMessage, 3000);
                    return;
                }
            }
            else {
                errorMessage = useMessage('warning', '连接出现异常，请刷新后重试。');
                showFloatMessage('#authMsg', errorMessage, 3000);
                return;
            }
        });
    });

    $('.login-button').click(function () {
        const target = $(this).attr('id');
        let formData = undefined;
        let errorMessage = '';
        if (target === 'loginByPhone') {
            const phone = $('#loginPhone').val().replace(/\s/g, '');
            const verifyCode = $('#loginVerifyCode').val().replace(/\s/g, '');
            const current_time = new Date();
            if (!phone || !verifyCode) {
                errorMessage = useMessage('warning', '请填写必填的项目。');
                showFloatMessage('#authMsg', errorMessage, 3000);
                return;
            }
            if (isNaN(phone)) {
                errorMessage = useMessage('warning', '请填写正确的手机号（11位）。');
                showFloatMessage('#authMsg', errorMessage, 3000);
                return;
            }
            if (parseInt(verifyCode) !== code) {
                errorMessage = useMessage('warning', '验证码不匹配，请重试。');
                showFloatMessage('#authMsg', errorMessage, 3000);
                return;
            }
            if (current_time > expireTime) {
                errorMessage = useMessage('warning', '您的验证码已过期。');
                showFloatMessage('#authMsg', errorMessage, 3000);
                return;
            }
            formData = { phone, by: 'phone', currentPage: redirectTo };
        }
        else {
            const email = $('#loginEmail').val().replace(/\s/g, '').toLowerCase();
            const password = $('#loginPassword').val().replace(/\s/g, '');
            if (!email || !password) {
                errorMessage = useMessage('warning', '请填写必填的项目。');
                showFloatMessage('#authMsg', errorMessage, 3000);
                return;
            }
            formData = { email, password, by: 'email' };
        }
        $.post(`${prefix}/public/auth/user-login.php`, formData).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                const message = useMessage(result.type, result.content);
                showFloatMessage('#authMsg', message, 3000);
                if(result.type === 'success'){
                    setInterval(function(){
                        if(!redirectTo || redirectTo.indexOf('login') > -1 || redirectTo.indexOf('signup') > -1){
                            window.location.href = `${prefix}/index.html`;
                        }
                        else{
                            window.location.href = redirectTo;
                        }
                    }, 2000);
                }
            }
        });
    });

});