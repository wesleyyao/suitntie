import { fetchAccount, prefix, showFloatMessage, useMessage, isPC, windowSize } from './common.js';
$(document).ready(function () {
    $('#mainResultDiv').hide();
    let message = '';
    let code = '';
    let title = '';
    let description = '';
    let basicAnalysis = '';
    let career = undefined;
    let characteristics = [];
    let jobs = [];
    let programs = [];
    let tags = [];
    let weights = [];
    let majorDimensions = [];
    let notification = false;
    let imgSrc = '';
    let customer = undefined;
    let chart1 = undefined;
    let chart2 = undefined;
    let chart3 = undefined;
    let chart4 = undefined;
    const isPCBrowser = isPC();

    fetchAccount();

    $('#screenshotModeBtn').click(function () {
        $('#screenshotDiv').show();
        $('#testResultShareModal').modal('show');
    });

    $('#testResultShareModal').on('shown.bs.modal', function () {
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
    })

    $.get(`${prefix}/public/api/result.php`).done(function (data) {
        const result = JSON.parse(data);
        if (result) {
            if (result === 'no login') {
                $('#userLoginModal').modal('show');
                $('#loadingDiv').hide();
                message = useMessage('warning', `未找到登录用户，请先<a href="../auth/login.html?redirect=${prefix}/tests/dimension-test-result">登录</a>`);
                showFloatMessage('#testMsg', message, 0);
                return;
            }
            if (result === 'no result') {
                $('#loadingDiv').hide();
                message = useMessage('warning', `未找到结果报告，请点击<a href="${prefix}/account/index.html">用户中心</a>查询您的测试结果。`);
                showFloatMessage('#testMsg', message, 0);
                return;
            }
            const dimensionResult = result.result;
            if (!dimensionResult) {
                message = useMessage('warning', '无法找到该数据。请刷新页面重试。');
                showFloatMessage('#testMsg', message, 3000);
                return;
            }
            code = dimensionResult.code;
            title = dimensionResult.title;
            description = dimensionResult.description;
            basicAnalysis = dimensionResult.basicAnalysis;
            career = dimensionResult.career;
            characteristics = dimensionResult.characteristics;
            jobs = dimensionResult.jobs;
            programs = dimensionResult.programs;
            tags = dimensionResult.tags;
            weights = dimensionResult.weights;
            majorDimensions = dimensionResult.majorDimensions;
            notification = result.saved_notification ? true : false;
            imgSrc = dimensionResult.img;
            customer = result.user;
            //presentation
            $('#resultTitle').html(`${customer ? customer.full_name : '你'}的测试结果为： ${code} ${title}`);
            $('#resultDescription').html(description);
            $('#characterImg').attr('src', prefix + imgSrc);

            let tagHtml = `<h4>你的标签： </h4>`;
            tags.forEach(function (tag) {
                tagHtml += `<span class="badge badge-light" style="margin: 0 3px">#${tag.name}</span>`
            });
            $('#resultTags').html(tagHtml);

            let dimensionAnalytics = `<ul class="list-group list-group-flush">`;
            majorDimensions.forEach(function (item) {
                dimensionAnalytics += `<li class="list-group-item"><strong>${item.code} ${item.title}</strong>： ${item.description}</li>`;
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

            let programList = `<div class="programFit"><h5>可能适合的专业：</h5><ul class="row">`;

            programs.forEach(function (item) {
                programList += `<li class="col-lg-3 col-md-3 col-sm-4 col-4 chart-column">
                    <a href="${prefix}/programs/explore.html?title=${item.title}" target="_blank">${item.title}</a></li>`;
            });
            programList += `</ul></div>`;

            let jobList = `<div class="careerFit"><h5>你适合的职业：</h5><ul>`;
            jobs.forEach(function (item) {
                jobList += `<li>${item.description}</li>`;
            });
            jobList += `</ul></div>`;
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
                        chart1 = setupChart(chartData, 'myChart0');
                    }
                }
                else if (index == 1) {
                    if (chart2) {
                        chart2.data = chartData;
                        chart2.update();
                    }
                    else {
                        chart2 = setupChart(chartData, 'myChart1');
                    }
                }
                else if (index == 2) {
                    if (chart3) {
                        chart3.data = chartData;
                        chart3.update();
                    }
                    else {
                        chart3 = setupChart(chartData, 'myChart2');
                    }
                }
                else {
                    if (chart4) {
                        chart4.data = chartData;
                        chart4.update();
                    }
                    else {
                        chart4 = setupChart(chartData, 'myChart3');
                    }
                }
            });

            //render share screen
            if (!isPCBrowser) {
                $('#screenshotModeBtn').show();
                $('#testResultShareDiv').load(`${prefix}/tests/components/share-result.html`, function () {
                    let tagContent = '';
                    tags.forEach(function (tag) {
                        tagContent += `<span class="badge badge-light" style="margin: 0 3px">#${tag.name}</span>`
                    });
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
                });
            }

            if (notification) {
                const noticePopup = `<div id="dimension-result-notification" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
                <div class="toast-header">
                  <img src="${prefix}/asset/image/notice.png" class="rounded mr-2" alt="..." style="width: 20px; height: 20px">
                  <strong class="mr-auto">通知</strong>
                  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="toast-body">
                  新的测试结果已经保存了。点击<a href="${prefix}/account/index.html">个人主页</a>可查看历史记录。
                </div>
              </div>`;
                $('#notification').html(noticePopup);
                $('#dimension-result-notification').toast('show');
            }

            $('#programAndJobAnalytics').html(careerAnalytics + programList + jobList);
            $('#loadingDiv').hide();
            $('#mainResultDiv').fadeIn();
        }
        sendNotification();
    });

    function setupChart(chartData, id) {
        let thisChart = document.getElementById(id).getContext('2d');
        $('#' + id).css({ 'height': windowSize.width > 1024 ? '160px' : '130px' });
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

    function sendNotification() {
        $.get(`${prefix}/public/api/proceed-result.php?action=send_email`).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                if (result && result.status) {
                    //
                }
            }
        });
    }
});