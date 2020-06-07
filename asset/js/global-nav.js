$(document).ready(function () {
    $('.navbar-toggler-icon').click(function () {
        $(".navbar-toggler-icon-x").toggleClass('open');
    });

    function isPC() {
        let userAgentInfo = navigator.userAgent; console.log(userAgentInfo)
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
    console.log(flag)
});

