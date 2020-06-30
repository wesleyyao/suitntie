$(document).ready(function () {
    $('.navbar-toggler-icon').click(function () {
        $(".navbar-toggler-icon-x").toggleClass('open');
    });
    $('#signup_mobile_container').hide();
    $('#login_mobile_container').hide();
    function isPC() {
        let userAgentInfo = navigator.userAgent;
        let Agents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
        let flag = true;
        for (let v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    }

    const flag = isPC();
    const currentPage = decodeURIComponent(window.location.href);
    let redirectUrl = currentPage.replace("http://www.suitntie.cn", '');
    redirectUrl = encodeURI("http://www.suitntie.cn/suitntie/public/auth/wechat-login-mobile.php?redirect=" + redirectUrl);
    if(!flag){
        $('#phoneLogin-tab').removeClass('active');
        $('#wechatLogin-tab').addClass('active');
        $('#phoneLogin').removeClass('active');
        $('#phoneLogin').removeClass('show');
        $('#wechatLogin').addClass('active');
        $('#wechatLogin').addClass('show');

        $('#login_wechat_mobile_link').prop('href', `https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx25d424c51ed0650d&redirect_uri=${redirectUrl}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect`);
        $('#login_mobile_container').show();
        $('#login_container').hide();
        $('#signup_mobile_container').show();
        $('#signup_container').hide();
        $('#signup_wechat_mobile_link').prop('href', `https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx25d424c51ed0650d&redirect_uri=${redirectUrl}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect`);
    }
    else{
        $('#phoneLogin-tab').addClass('active');
        $('#wechatLogin-tab').removeClass('active');
        $('#phoneLogin').addClass('active');
        $('#phoneLogin').addClass('show');
        $('#wechatLogin').removeClass('active');
        $('#wechatLogin').removeClass('show');
    }
});

