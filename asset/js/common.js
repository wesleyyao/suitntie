export const prefix = '/suitntie';
export const currentPage = decodeURIComponent(window.location.href);
export const windowSize = { width: window.innerWidth, height: window.innerHeight };

export function validateEmailFormat(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return true;
    }
    return false;
}

export function generateMessage(type, message) {
    return `
    <div class="row">
        <div class="col-12">
            <div class="alert alert-${type}">
                <i class="${type == 'success' ?
            'fas fa-check' : type === 'warning' ?
                'fas fa-exclamation-triangle' : type == 'info' ?
                    'fas fa-info-circle' : 'fas fa-times'}"></i> ${message}
            </div>
        </div>
    </div>`;
}

export const submittingMessage = `
<div class="row pb-2">
    <div class="col-12 text-center">
        <div class="spinner-border text-warning" style="width: 1.5rem; height: 1.5rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span class="h5">正在处理...</span>
    </div>
</div>`;

export function formValidation(className, message, email) {
    let isValid = true;
    $(className).each(function () {
        if (!$(this).val().replace(/\s/g, '')) {
            isValid = false;
            return;
        }
        else if (email && !validateEmailFormat(email)) {
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

export const loadingPage = `
<div class="row pb-3" pt-3>
    <div class="col-12 text-center">
        <div class="spinner-border text-warning" style="width: 1.5rem; height: 1.5rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span class="h5">正在加载...</span>
    </div>
</div>`;

export const loadingMessage = `
<div class="row">
    <div class="col-12 text-center">
        <div class="spinner-border text-warning" style="width: 1.3rem; height: 1.3rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span>正在加载...</span>
    </div>
</div>`;

export function useMessage(status, content) {
    return status === 'success' ?
        `<label class="text-success"><i class="fas fa-check-circle"></i><span class="pl-1">${content}</span></label>` :
        status === 'warning' ?
            `<label class="text-danger"><i class="fas fa-exclamation-circle"></i><span class="pl-1">${content}</span></label>` :
            `<label class="text-info"><i class="fas fa-info-circle"></i><span class="pl-1">${content}</span></label>`;
}

export function isPC() {
    let userAgentInfo = navigator.userAgent;
    let Agents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
    let flag = true;
    for (let v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > -1) {
            flag = false;
            break;
        }
    }
    return flag;
}

export function isWeixin() {
    let ua = navigator.userAgent.toLowerCase();
    return ua.match(/MicroMessenger/i) == "micromessenger";
}

export async function fetchAccountData() {
    return $.when($.get(`${prefix}/public/api/account.php`));
}

export function fetchAccount() {
    fetchAccountData().then(function (data) {
        let errorMessage = '';
        if (!data) {
            errorMessage = useMessage('warning', '服务器连接失败，请刷新后重试。');
            showFloatMessage('#testMsg', errorMessage, 3000);
            return;
        }
        const result = JSON.parse(data);
        const user = result.user ? result.user : undefined;
        renderUserInNav(user ? true : false, user ? user.headImg : '');
        if (user && (currentPage.indexOf('login') > -1 || currentPage.indexOf('signup') > -1)) {
            window.location.href = `${prefix}/index.html`;
        }
    });
}

export function renderUserInNav(isLogin, avatar) {
    //const redirectTo = location.search ? location.search.replace('?redirect=', '') : `${prefix}/index.html`;
    const redirectTo = window.location.href;
    const noSignIn = `<a style="display: inline; padding: 0 3px;" href="${prefix}/auth/login.html?redirect=${redirectTo}">登录</a> | <a style="display: inline; padding: 0 3px;" href="${prefix}/auth/signup.html?redirect=${redirectTo}">注册</a>`;
    const signedIn = `<div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="userLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img id="loginUserAvatarInNav" src="" width="25" height="25" style="border-radius: 50%"/>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userLinks">
            <a class="dropdown-item" href="${prefix}/account/index.html">个人中心</a>
            <a class="dropdown-item" href="${prefix}/public/auth/user-logout.php?redirect=${window.location.href}">登出</a>
        </div>
    </div>`;
    $('#userInNav').html(isLogin ? signedIn : noSignIn);
    $('#loginUserAvatarInNav').attr('src', isLogin && avatar ? avatar : `${prefix}/asset/image/avatar.png`);
    $('#userInNav').css({ 'padding-top': !isLogin ? '5px' : '0' });
}

export function showFloatMessage(div, message, sec) {
    $(div).html(message);
    $(div).fadeIn(200);
    if (sec > 0) {
        $(div).delay(sec).fadeOut();
    }
}

export function hideFloatMessage(div, sec) {
    if (sec > 0) {
        $(div).delay(sec).fadeOut();
        return;
    }
    $(div).fadeOut();
}