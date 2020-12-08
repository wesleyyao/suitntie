import { prefix } from './common.js';

$(document).ready(function () {
    $('#headerDiv').load(`${prefix}/theme/header.html`, function () {
        $('#headerLogoImg').attr('src', `${prefix}/asset/image/logo/main-logo.png`);
        $('#headerLogo').attr('href', `${prefix}/index.html`);
        $('#testLink').attr('href', `${prefix}/tests/dimension-test.html`);
        $('#oneOnOneLink').attr('href', `${prefix}/one-on-one/index.html`);
        $('#programLink').attr('href', `${prefix}/programs/index.html`);
        $('#consultantLink').attr('href', `${prefix}/consult/index.html`);
        $('#aboutLink').attr('href', `${prefix}/about/index.html`);
        $('#homeLink').attr('href', `${prefix}/index.html`);
    });
    $('#footerDiv').load(`${prefix}/theme/footer.html`, function () {
        $('#aboutFooterLink').attr('href', `${prefix}/about/index.html`);
        $('#contactFooterLink').attr('href', `${prefix}/index.html#contactUs`);
        $('#consultFooterLink').attr('href', `${prefix}/one-on-one/index.html#ourTeachers`);
        $('#testFooterLink').attr('href', `${prefix}/tests/dimension-test.html`);
        $('#oneOnOneFooterLink').attr('href', `${prefix}/one-on-one/index.html`);
        $('#programFooterLink').attr('href', `${prefix}/programs/index.html`);
        $('#footerQR1').attr('src', `${prefix}/asset/image/qr/zhushou.JPG`);
        $('#footerQR2').attr('src', `${prefix}/asset/image/qr/gongzhonghao.jpg`);
    });

    $(document).on('click', '.navbar-toggler-icon', function () {
        $(".navbar-toggler-icon-x").toggleClass('open');
    });

    let programsDropDown = '';
    $.get(`${prefix}/public/api/program.php?type=all`).done(function (data) {
        if (data) {
            const result = JSON.parse(data);
            if (Array.isArray(result) && result.length > 0) {
                result.forEach(function (item, index) {
                    programsDropDown += `
                        <li class="dropdown-submenu">
                            <a class="program-category dropdown-submenu-toggle" id="category${item.id}" tabindex="-1" href="#/">${item.name} 
                            <i class="fas fa-caret-right pl-1" style="color: #c7c7c7;"></i>
                            </a>
                        <ul class="level-3-dropdown" id="navLevel3${index}">`;
                    if (item.details && Array.isArray(item.details) && item.details.length > 0) {
                        item.details.forEach(function (item) {
                            programsDropDown += `<li><a href="${prefix}/programs/explore.html?title=${item.title}">${item.title}</a></li>`;
                        });
                    }
                    programsDropDown += `</ul></li>`;
                });
            }
            $('#programMenu').append(programsDropDown);
        }
    });

    $(document).on('click', '.program-category', function (e) {
        e.stopPropagation();
        const id = $(this).next('.level-3-dropdown').attr('id');
        $(this).next('.level-3-dropdown').toggle();
        $('.level-3-dropdown').each(function () {
            if ($(this).attr('id') !== id) {
                $(this).hide();
            }
        });
    });

    $('.contact-btn').click(function () {
        $('html, body').animate({
            scrollTop: $("#contactUs").offset().top
        }, 1000);
    });
});