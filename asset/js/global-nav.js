$(document).ready(function () {
    $('.navbar-toggler-icon').click(function () {
        $(".navbar-toggler-icon-x").toggleClass('open');
    });
    $('#signup_mobile_container').hide();
    $('#login_mobile_container').hide();

    const flag = isPC();
    const isWechatBrowser = isWeixin();

    let currentPage = decodeURIComponent(window.location.href);
    if (currentPage.indexOf('#') > -1) {
        currentPage = currentPage.substring(0, currentPage.indexOf('#'));
    }
    const toRemoveUrl = currentPage.indexOf('www') > -1 ? 'https://www.suitntie.cn' : 'https://suitntie.cn';
    let redirectUrl = currentPage.replace(toRemoveUrl, "");
    redirectUrl = encodeURI("https://www.suitntie.cn/public/auth/wechat-login-mobile.php?redirect=" + redirectUrl);
    if (isWechatBrowser) {
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
    else {
        $('#phoneLogin-tab').addClass('active');
        $('#wechatLogin-tab').removeClass('active');
        $('#phoneLogin').addClass('active');
        $('#phoneLogin').addClass('show');
        $('#wechatLogin').removeClass('active');
        $('#wechatLogin').removeClass('show');
    }

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

    function isWeixin() {
        let ua = navigator.userAgent.toLowerCase();
        return ua.match(/MicroMessenger/i) == "micromessenger";
    }
});

