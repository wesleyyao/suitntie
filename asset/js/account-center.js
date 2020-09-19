import { validateEmailFormat, generateMessage, prefix } from './common.js';
$(document).ready(function () {
    let user = undefined;
    let tests = [];
    const loading = `
    <div class="pt-2 pb-2">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="pl-2 h5">正在加载...</span>
        </div>
    </div>`;
    $('#accountCenter').hide();
    $('#testResultsSection').hide();
    fetchUserInfo();

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
        // $('.required-input').each(function () {
        //     if (!$(this).val().replace(/\s/g, '')) {
        //         $('#profileEditMessage').html(generateMessage('danger', '请填写所有必填项。'));
        //         isValid = false;
        //         return;
        //     }
        //     else if (!validateEmailFormat($('#profileEditEmail').val())) {
        //         $('#profileEditMessage').html(generateMessage('danger', '输入的邮箱格式错误。'));
        //         isValid = false;
        //         return;
        //     }
        //     else {
        //         isValid = true;
        //         return;
        //     }
        // });
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
        $('#loadingDiv').html(loading);
        const resultId = $(this).attr('id').replace('result_', '');
        $.get(`${prefix}/public/api/result.php?result=${resultId}`).done(function (data) {
            const result = JSON.parse(data);
            $('#loadingDiv').html('');
            if (result) {
                if (result === 'no login') {
                    $('#userLoginModal').modal('show');
                    
                    message = generateMessage('warning', '未找到登录用户，请先<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>');
                    $('#message').html(message);
                    return;
                }
                if (result === 'no result') {
               
                    message = generateMessage('warning', '未找到结果报告，请点击此处查询你的所有历史结果。');
                    $('#message').html(message);
                    return;
                }
                const dimensionResult = result.result;
                if (!dimensionResult) {
                    message = generateMessage('warning', '无法找到该数据。请刷新页面重试。');
                    $('#message').html(message);
                    return;
                }
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
                $('#resultTitle').html(`${code} ${title}`);
                $('#resultDescription').html(description);
                $('#characterImg').attr('src', encodeURI(imgSrc));

                let tagHtml = `<span>你的标签： </span>`;
                tags.forEach(function (tag) {
                    tagHtml += `<span class="badge badge-light" style="margin: 0 3px">#${tag.name}</span>`
                });
                $('#resultTags').html(tagHtml);

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

                let programList = `<h5>可能适合的专业：</h5><ul>`;
                programs.forEach(function (item) {
                    programList += `<li><a href="${'/programs/explore.php?title=' + item.title}" target="_blank">${item.title}</a></li>`;
                });
                programList += `</ul>`;

                let jobList = `<h5>你适合的职业：</h5><ul>`;
                jobs.forEach(function (item) {
                    jobList += `<li>${item.description}</li>`;
                });
                jobList += `</ul>`;
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
                $('#programAndJobAnalytics').html(careerAnalytics + programList + jobList);
            }
            else {
                $('#testResultMessage').html(generateMessage('warning', '未找到该报告。'));
            }
            $('#testResultModal').modal('show');
        });
    });

    function timeoutHideModal() {
        setTimeout(function () { $('#editProfileModal').modal('hide'); $('#profileEditMessage').html(''); }, 2000);
    }

    function fetchUserInfo() {
        $('#accountCenter').hide();
        $('#loadingDiv').html(loading);
        $.get(`${prefix}/public/api/account.php`).done(function (data) {
            const result = JSON.parse(data);
            if (result === 'no login') {
                $('#userLoginModal').modal('show');
                $('#loadingDiv').html('');
                message = generateMessage('warning', '未找到登录用户，请先<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>');
                $('#message').html(message);
                return;
            }
            user = result.user ? result.user : undefined;
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
                        testCategories += `<li class="list-group-item active" id="test_${item.id}">${item.title}</li>`;
                    }
                    else {
                        testCategories += `<li class="list-group-item" id="test_${item.id}">${item.title}</li>`;
                    }
                });
                $('#testCategory').html(testCategories);
                //mount first test results
                let resultList = '';
                if (tests[0].results && tests[0].results.length > 0) {
                    tests[0].results.forEach(function (item, index) {
                        resultList += `<tr>
                        <td>${item.create_date}</td>
                        <td class="text-right"><button id="result_${item.id}" class="btn btn-outline-dark check-result-btn">查看</button></td>
                        </tr>`;
                    });
                }
                $('#resultList').html(resultList);
            }
            $('#loadingDiv').html('');
            $('#accountCenter').fadeIn();
            if(window.location.search.indexOf('view=history') > -1){
                $('#userProfile').removeClass('active');
                $('#testResults').addClass('active');
                $('#userProfileSection').hide();
                $('#testResultsSection').fadeIn();
            }
        });
    }
});