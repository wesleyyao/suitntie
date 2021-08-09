import { generateMessage, prefix, useMessage, showFloatMessage, renderUserInNav, isPC } from './common.js';

$(document).ready(function () {
    let chart1 = undefined;
    let chart2 = undefined;
    let chart3 = undefined;
    let chart4 = undefined;
    let user = undefined;
    let tests = [];
    const loading = `
    <div class="pt-2 pb-2">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-warning" role="status" style="width: 1.5rem; height: 1.5rem">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="pl-2 h5">正在加载...</span>
        </div>
    </div>`;
    $('#accountCenter').hide();
    $('#testResultsSection').hide();
    $('#screenshotDiv').hide();
    $('#userResultMsg').hide();

    fetchUserInfo();
    $('#profileEditorDiv').load(`${prefix}/account/components/profile-editor.html`);
    $('#testResultDiv').load(`${prefix}/account/components/dimension-test-result.html`, function () {
        const isPCBrowser = isPC();
        if (!isPCBrowser && window.innerWidth < 464) {
            $('#screenshotModeBtn').show();
        }
        window.addEventListener("resize", function () {
            if (window.innerWidth > 464) {
                $('#screenshotModeBtn').hide();
                $('#screenshotDiv').hide();
            }
            else {
                $('#screenshotModeBtn').show();
            }
        }, false);
    });

    $('.account-nav').click(function () {
        const item = $(this).attr('id');
        $('.account-center-section').hide();
        $('#' + item + 'Section').fadeIn();
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    });

    $('#profileEditForm').submit(function (e) {
        let isValid = true;
        e.preventDefault();
        if (isValid) {
            const data = {
                nickName: $('#profileEditNickname').val(),
                sex: $('#profileEditSex').val(),
                city: $('#profileEditCity').val(),
                province: $('#profileEditProvince').val(),
                country: $('#profileEditCountry').val(),
                isUpdate: true
            }
            $('#profileEditMessage').html(loading);
            $.post(`${prefix}/public/api/account.php`, data).done(function (result) {
                if (result) {
                    const data = JSON.parse(result);
                    if (data == "exist") {
                        $('#profileEditMessage').html(generateMessage('warning', '该邮箱或手机已经被注册。'));
                        return;
                    }
                    fetchUserInfo();
                    $('#profileEditMessage').html(generateMessage('success', '您的信息已更新成功, 请稍后...'));
                    timeoutHideModal();
                }
                else {
                    $('#profileEditMessage').html(generateMessage('danger', '您的请求未能被处理，请重试。'));
                }
            }).fail(function () {
                $('#profileEditMessage').html(generateMessage('danger', '您的请求未能被处理，请重试。'));
            });
        }
    });

    $(document).on('click', '.check-result-btn', function () {
        $('#userResultMsg').html('');
        const resultId = $(this).attr('id').replace('result_', '');
        showFloatMessage('#accountMsg', loading, 0);
        $.get(`${prefix}/public/api/result.php?result=${resultId}`).done(function (data) {
            const result = JSON.parse(data);
            let message = '';
            if (result) {
                if (result === 'no login') {
                    if (!data) {
                        message = useMessage('warning', '服务器连接失败，请刷新后重试。');
                        showFloatMessage('#accountMsg', message, 3000);
                        return;
                    }
                    const result = JSON.parse(data);
                    const user = result.user ? result.user : undefined;
                    renderUserInNav(user ? true : false, user ? user.headImg : '');
                }
                if (result === 'no result') {
                    message = useMessage('info', '未找到结果报告，请点击此处查询你的所有历史结果。');
                    showFloatMessage('#accountMsg', message, 3000);
                    return;
                }
                const dimensionResult = result.result;
                const customer = result.user;
                if (!dimensionResult) {
                    message = useMessage('warning', '无法找到该数据。请刷新页面重试。');
                    showFloatMessage('#accountMsg', message, 3000);
                    return;
                }
                $('#accountMsg').hide();
                const code = dimensionResult.code;
                const title = dimensionResult.title;
                const description = dimensionResult.description;
                const basicAnalysis = dimensionResult.basicAnalysis;
                const career = dimensionResult.career;
                const characteristics = dimensionResult.characteristics;
                const jobs = dimensionResult.jobs;
                const programs = dimensionResult.programs;
                const tags = dimensionResult.tags;
                const weights = dimensionResult.weights;
                const majorDimensions = dimensionResult.majorDimensions;
                const notification = result.saved_notification ? true : false;
                const imgSrc = dimensionResult.img;
                //presentation
                $('#userResultTitle').html(`${code} ${title}`);

                $('#userResultDescription').html(description);
                $('#userCharacterImg').attr('src', prefix + encodeURI(imgSrc));

                let tagTitle = `<span>你的标签： </span>`;
                let tagContent = '';
                tags.forEach(function (tag) {
                    tagContent += `<span class="badge badge-light" style="margin: 0 3px">#${tag.name}</span>`
                });
                $('#resultTags').html(tagTitle + tagContent);

                let dimensionAnalytics = `<ul class="list-group list-group-flush">`;
                majorDimensions.forEach(function (item) {
                    dimensionAnalytics += `<li class="list-group-item">${item.code} ${item.title}： ${item.description}</li>`;
                });
                dimensionAnalytics += `</ul>`;
                $('#dimensionAnalytics').html(dimensionAnalytics);

                $('#basicAnalytics').html(basicAnalysis);

                let advantages = `<ul>`;
                characteristics.forEach(function (item) {
                    if (item.type === '优势') {
                        advantages += `<li>${item.description}</li>`;
                    }
                });
                advantages += `</ul>`;
                $('#advantageList').html(advantages);

                let disadvantages = `<ul>`;
                characteristics.forEach(function (item) {
                    if (item.type === '盲点') {
                        disadvantages += `<li>${item.description}</li>`;
                    }
                });
                disadvantages += `</ul>`;
                $('#disadvantageList').html(disadvantages);

                let careerAnalytics = `<p>${career.description}</p>`;

                let programList = '<div class="pb-3">';
                programList += `<h5>可能适合的专业：</h5>`;
                programs.forEach(function (item) {
                    programList += `<a style="padding: 3px 5px; color: #6d6d6d;" href="${prefix}/programs/explore.html?title=${item.title}" target="_blank">${item.title}</a>`;
                });
                programList += `</div>`;

                let jobList = `<h5>你适合的职业：</h5><ul>`;
                jobs.forEach(function (item) {
                    jobList += `<li>${item.description}</li>`;
                });
                jobList += `</ul>`;
                weights.forEach(function (weight, index) {
                    const chartData = {
                        labels: weight.map((item) => item.code + ' ' + item.title),
                        datasets: [{
                            data: weight.map((w) => w.total),
                            borderColor: '#fc8803',
                            backgroundColor: 'rgba(252, 136, 3, 0.47)',
                            label: '得分'
                        }]
                    };
                    if (index == 0) {
                        if (chart1) {
                            chart1.data = chartData;
                            chart1.update();
                        }
                        else {
                            chart1 = setupChart(chartData, 'userMyChart0');
                        }
                    }
                    else if (index == 1) {
                        if (chart2) {
                            chart2.data = chartData;
                            chart2.update();
                        }
                        else {
                            chart2 = setupChart(chartData, 'userMyChart1');
                        }
                    }
                    else if (index == 2) {
                        if (chart3) {
                            chart3.data = chartData;
                            chart3.update();
                        }
                        else {
                            chart3 = setupChart(chartData, 'userMyChart2');
                        }
                    }
                    else {
                        if (chart4) {
                            chart4.data = chartData;
                            chart4.update();
                        }
                        else {
                            chart4 = setupChart(chartData, 'userMyChart3');
                        }
                    }
                });
                $('#programAndJobAnalytics').html(careerAnalytics + programList + jobList);
                //screenshot modal
                $('.title-banner').css('background-image', `url(${prefix}/asset/image/screenshot/hero-bg.jpg)`);
                $('#scTitle').text(`${code} ${title}`);
                $('#scTitleDesc').html(description);
                $('#scCharacterImg').attr('src', prefix + encodeURI(imgSrc));
                $('.tags-right').html(tagContent);
                let scPrograms = '';
                programs.forEach(function (item) {
                    scPrograms += `<span class="user-rec-program">${item.title}</span>`;
                });
                $('#programRec').html(scPrograms);
                $('#scLogo').attr('src', `${prefix}/asset/image/screenshot/Logo-White2x.png`);
                $('#scQRcode').attr('src', `${prefix}/asset/image/screenshot/公众号2x.png`);
            }
            else {
                message = useMessage('warning', '未找到该报告。');
                showFloatMessage('#accountMsg', message, 3000);
            }
            $('#screenshotDiv').hide();
            $('#finalScreenshot').attr('src', '');
            $('#finalScreenshot').hide();
            $('#testResultBody').show();
            $('#testResultModal').modal('show');
        });
    });

    function setupChart(chartData, id) {
        let thisChart = document.getElementById(id).getContext('2d');
        return new Chart(thisChart, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            display: false,
                            beginAtZero: true
                        }
                    }]
                },
                responsive: false,
            }
        });
    }

    function timeoutHideModal() {
        setTimeout(function () { $('#editProfileModal').modal('hide'); $('#profileEditMessage').html(''); }, 2000);
    }

    $(document).on('click', '#screenshotModeBtn', function () {
        $('#testResultBody').hide();
        $('#screenshotDiv').show();
        $('#userResultMsg').html(
            `<div class="d-flex justify-content-center">
            <div class="spinner-border text-warning" style="width: 1.5rem; height: 1.5rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="pl-2" style="font-size: 1.2rem;"><strong>正在生成测试结果分享图...</strong></span>
        </div>`
        );
        $('#userResultMsg').show();
        html2canvas(document.querySelector("#screenshotDiv")).then(canvas => {
            $('#screenshotDiv').hide();
            const dataUrl = canvas.toDataURL('image/png', 0.6);
            $('#finalScreenshot').attr('src', dataUrl);
            $('#finalScreenshot').show();
            $('#userResultMsg').html(`
            <i class="fas fa-check-circle"></i>
            <span class="pl-1" style="font-size: 1.2rem;"><strong>分享图已生成，请长按保存图片</strong></span>
            `);
            $('#userResultMsg').css({ 'color': '#28a745' });
        });
    });

    function fetchUserInfo() {
        $('#accountCenter').hide();
        $('#loadingDiv').html(loading);
        $.get(`${prefix}/public/api/account.php`).done(function (data) {
            let errorMessage = '';
            if (!data) {
                errorMessage = useMessage('warning', '服务器连接失败，请刷新后重试。');
                showFloatMessage('#accountMsg', errorMessage, 3000);
                return;
            }
            const result = JSON.parse(data);
            if (result === 'no login') {
                $('#loadingDiv').html('');
                $('#loadingDiv').css('padding', '20px 0 80px 0');
                errorMessage = useMessage('warning', '未找到登录用户，请先<a href="../auth/login.html">登录</a>');
                showFloatMessage('#accountMsg', errorMessage, 0);
                return;
            }
            user = result.user ? result.user : undefined;
            renderUserInNav(user ? true : false, user && user.headImg ? user.headImg : '');
            tests = result.results ? result.results : [];
            if (user) {
                //mount user info
                $('#profileNickname').html(user.nick_name ? user.nick_name : '-');
                $('#profileCellphone').html(user.phone ? user.phone : '-');
                $('#profileEmail').html(user.email ? user.email : '-');
                $("#profileHeadImg").attr('src', user.headImg ? user.headImg : `${prefix}/asset/image/avatar.png`);
                $('#profileSex').html(user.sex ? user.sex === 1 ? '男' : '女' : '-');
                $('#profileCity').html(user.city ? user.city : '-');
                $('#profileProvince').html(user.province ? user.province : '-');
                $('#profileCountry').html(user.country ? user.country : '-');
                //mount user info in editor
                $('#profileEditNickname').val(user.nick_name);
                $('#profileEditPhone').val(user.phone);
                $('#profileEditEmail').val(user.email);
                $('#profileEditSex').val(user.sex ? user.sex.toString() : '');
                $('#profileEditCity').val(user.city);
                $('#profileEditProvince').val(user.province);
                $('#profileEditCountry').val(user.country);
            }
            if (tests.length > 0) {
                //mount tests
                let testCategories = '';
                tests.forEach(function (item, index) {
                    if (index === 0) {
                        testCategories += `<li class="list-group-item test-tab active" id="test_${item.id}">${item.title}</li>`;
                    }
                    else {
                        testCategories += `<li class="list-group-item" id="test_${item.id}">${item.title}</li>`;
                    }
                });
                $('#testCategory').html(testCategories);
                //mount first test results
                let resultList = '';
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
                if (tests[0].results && tests[0].results.length > 0) {
                    tests[0].results.map(function (item) {
                        let jsDate = undefined;
                        if (item.create_date) {
                            jsDate = moment(item.create_date);
                            jsDate.add(moment.duration(12, 'hours'));
                        }
                        resultList += `<tr>
                        <td>${jsDate ? jsDate.format('YYYY-MM-DD HH:mm') : ''}</td>
                        <td class="text-right"><button id="result_${item.id}" class="btn btn-outline-dark check-result-btn">查看</button></td>
                        </tr>`;
                    });
                }
                $('#resultList').html(resultList);
            }
            $('#loadingDiv').html('');
            $('#accountCenter').fadeIn();
            if (window.location.search.indexOf('view=history') > -1) {
                $('#userProfile').removeClass('active');
                $('#testResults').addClass('active');
                $('#userProfileSection').hide();
                $('#testResultsSection').fadeIn();
            }
        });
    }
});