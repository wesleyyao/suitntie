import { generateMessage, prefix } from './common.js';
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

    sendNotification();

    $.get(`${prefix}/public/api/result.php`).done(function (data) {
        const result = JSON.parse(data);
        if (result) {
            if (result === 'no login') {
                $('#userLoginModal').modal('show');
                $('#loadingDiv').hide();
                message = generateMessage('warning', '未找到登录用户，请先<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>');
                $('#message').html(message);
                return;
            }
            if (result === 'no result') {
                $('#loadingDiv').hide();
                message = generateMessage('warning', `未找到结果报告，请点击<a href="${prefix}/account/user.php">用户中心</a>查询您的测试结果。`);
                $('#message').html(message);
                return;
            }
            const dimensionResult = result.result;
            if (!dimensionResult) {
                message = generateMessage('warning', '无法找到该数据。请刷新页面重试。');
                $('#message').html(message);
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
            //presentation
            $('#resultTitle').html(`你的结果为： ${code} ${title}`);
            $('#resultDescription').html(description);
            $('#characterImg').attr('src', imgSrc);

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
                programList += `<li class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <a href="${prefix}/programs/explore.php?title=${item.title}" target="_blank">${item.title}</a></li>`;
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
                        data: weight.map((item) => item.total),
                        borderColor: '#fc8803',
                        backgroundColor: 'rgba(252, 136, 3, 0.47)',
                        label: '得分'
                    }]
                };
                new Chart($('#myChart' + index)[0].getContext('2d'), {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        legend: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            });

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
                  新的测试结果已经保存了。点击<a href="${prefix}/account/user.php">个人主页</a>可查看历史记录。
                </div>
              </div>`;
                $('#notification').html(noticePopup);
                $('#dimension-result-notification').toast('show');
            }

            $('#programAndJobAnalytics').html(careerAnalytics + programList + jobList);
            $('#loadingDiv').hide();
            $('#mainResultDiv').fadeIn();
        }
    });

    function sendNotification(){
        $.get(`${prefix}/public/api/proceed-result.php?action=send_email`).done(function(data){
            if(data){
                const result = JSON.parse(data);
                if(result && result.status){
                    //
                }
            }
        });
    }
});