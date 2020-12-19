import { prefix, loadingMessage, showFloatMessage } from '../../../asset/js/common';
import { auth, renderNav } from './global';

$(document).ready(function () {
    $('#message').hide();
    renderNav();
    auth();
    fetchDashboardData();

    $(document).on('click', '.user-names', function () {
        $('#testResultModal').modal('show');
        $('#historyDiv').html(loadingMessage);
        const id = $(this).attr('id').replace('userId_', '');
        $.get(`${prefix}/manager/api/dashboard.php?user=${id}`).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                let historyList = `<table id="testHistoryTable" class='table table-striped'><thead class="thead-dark"><tr>
                <th>测试</th>
                <th>创建时间</th>
                <th>状态</th>
                </tr></thead><tbody>`;
                if (Array.isArray(result)) {
                    result.map(function (item) {
                        let jsDate = undefined;
                        if (item.create_date) {
                            jsDate = moment(item.create_date);
                            if (jsDate < moment('2020-10-20')) {
                                jsDate.add(moment.duration(12, 'hours'));
                            }
                        }
                        historyList += `<tr><td><a href="#/" class="test-results" id="testResultId_${item.id}">${item.title}</a></td>
                        <td>${jsDate ? jsDate.format('YYYY-MM-DD HH:mm') : ''}</td>
                        <td>${item.status}</td></tr>`;
                    });
                }
                historyList += `</tbody></table>`;
                $('#historyDiv').html(historyList);
                $('#testHistoryTable').DataTable();
            }
        });
    });

    $(document).on('click', '.test-results', function () {
        $('#testResultDetailsModal').modal('show');
        const id = $(this).attr('id').replace('testResultId_', '');
        $.get(`${prefix}/manager/api/dashboard.php?result=${id}`).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                const dimensionResult = result;
                if (!dimensionResult) {
                    message = generateMessage('warning', '无法找到该数据。请刷新页面重试。');
                    $('#historyDetailsDiv').html(message);
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
                    programList += `<li><a href="${item.link}" target="_blank">${item.title}</a></li>`;
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
        });
    });

    function generateMessage(type, content) {
        return `<div class="alert alert-${type}" role="alert">
                    <i class="${type === 'success' ? 'fas fa-check' : 'fas fa-exclamation-triangle'}"></i> ${content}
                </div>`;
    }


    $(document).on('click', '.ip-addresses', function () {
        const ip = $(this).attr('id').split('userIp_')[1];
        if (!ip) {
            return;
        }
        checkGeolocationByIp(ip);
    });

    function checkGeolocationByIp(ip) {
        if (ip) {
            $.get(`https://ipapi.co/${ip}/json/`).done(function (data) {
                if (!data) {
                    return;
                }
                $('#userCountry').html(data.country_name);
                $('#userCity').html(data.city);
                $('#userProvince').html(data.region);
                $('#userIpModal').modal('show');
            });
        }
    }

    function fetchDashboardData() {
        $.get(`${prefix}/manager/api/dashboard.php`).done(function (data) {
            if (!data) {
                showFloatMessage('#message', useMessage('danger', '无法连接服务器。'));
                return;
            }
            const result = JSON.parse(data);
            const { users, tests, test_lastWeek, test_lastMonth } = result;
            if (users) {
                let userTable = '';
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
                [...users].map(function (item) {
                    let jsDate = undefined;
                    if (item.date_time) {
                        jsDate = moment(item.date_time);
                        if (jsDate < moment('2020-10-20')) {
                            jsDate.add(moment.duration(12, 'hours'));
                        }
                    }
                    userTable += `<tr>
                    <td>${item.results ? `<a href="#/" class="user-names" id="userId_${item.id}">查看</a>` : '未测试'}</td>
                    <td>${item.nick_name ? item.nick_name : ''}</a></td>
                    <td>${item.email ? item.email : ''}</td>
                    <td>${item.phone ? item.phone : ''}</td>
                    <td>${item.sex ? item.sex == 1 ? '男' : '女' : ''}</td>
                    <td>${item.full_name ? item.full_name : ''}</td>
                    <td>${item.age ? item.age : ''}</td>
                    <td>${item.is_study_aboard ? item.is_study_aboard : ''}</td>
                    <td>${item.how_know ? item.how_know : ''}</td>
                    <td>${item.city ? item.city : ''}</td>
                    <td>${item.province ? item.province : ''}</td>
                    <td>${item.country ? item.country : ''}</td>
                    <td><a href="#/" class="ip-addresses" id="userIp_${item.ip ? item.ip : ''}">${item.ip ? item.ip : ''}</a></td>
                    <td>${jsDate ? jsDate.format('YYYY-MM-DD HH:mm') : ''}</td></tr>`;
                });
                $('#userTableBody').html(userTable);
                $('#userTable').DataTable();
                const usersNoTestResultRate = users.filter(item => item.results === 0).length / users.length * 100;
                $("#testRate").css("color", usersNoTestResultRate > 50 ? "green" : "orange");
                $("#totalResults").text(tests);
                $("#testRate").text(`${usersNoTestResultRate.toFixed(2)}%`);
                $('#totalUser').text(users.length);
                $('#lastWeekTest').text(test_lastWeek);
                $('#lastMonthTest').text(test_lastMonth);
            }
        });
    }
});
