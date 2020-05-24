$(document).ready(function(){
    let messageDiv = '';
    const loading = `<div class="d-flex justify-content-center">
    <div class="spinner-border text-success" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>`;
  fetchMainData();

    $('#loginForm').submit(function(e){
        let isValid = true;
        $('#loginMessage').html(loading);
        e.preventDefault();
        $('.login-required').each(function(){
            if (!$(this).val().replace(/\s/g, '')) {
                isValid = false;
                showMessage('#loginMessage', 'danger', '请填写所有必填项。');
            }
            else if(!validateEmailFormat($('#officeEmail').val())){
                isValid = false;
                showMessage('#loginMessage', 'danger', '输入的邮箱格式错误。');
            }
            else{
                isValid = true;
                return;
            }
        });

        if(isValid){
            const email = $('#officeEmail').val();
            const password = $('#officePwd').val();
            
            $.post('./auth/office-login.php', {email, password}).done(function(data){
                const result = JSON.parse(data);
                console.log(result)
                if(!result){
                    showMessage('#loginMessage', 'danger', '无法连接服务器。');
                }
                const { message } = result;
                if(message){
                    if(message.type === 'danger'){
                        if(message.content === 'required missing'){
                            showMessage('#loginMessage', 'danger', '请填写所有必填项。');
                        }
                        else if(message.content === 'dismatch'){
                            showMessage('#loginMessage', 'danger', '您的用户名或密码错误。');
                        }
                        else{
                            showMessage('#loginMessage', 'danger', '抱歉，无法连接到服务器。请刷新重试。您的用户名或密码错误。');
                        }
                    }
                    else{
                        showMessage('#loginMessage', 'success', message.content);
                        setTimeout(function(){
                            $('#loginModal').modal('hide');
                            $('#message').html('');
                            fetchMainData();
                        }, 2000);
                    }
                }
            });
        }
    });

    $(document).on('click', '.user-names', function(){
        $('#testResultModal').modal('show');
        $('#historyDiv').html(loading);
        const id = $(this).attr('id').replace('userId_', '');
        console.log(id)
        $.get('./api/manager.php?user=' + id).done(function(data){
            if(data){
                const result = JSON.parse(data);
                console.log(result);
                let historyList = `<table id="testHistoryTable" class='table table-striped'><thead class="thead-dark"><tr>
                <th>测试</th>
                <th>创建时间</th>
                <th>状态</th>
                </tr></thead><tbody>`;
                if(Array.isArray(result)){
                    result.forEach(function(item){
                        historyList += `<tr><td><a href="#/" class="test-results" id="testResultId_${item.id}">${item.title}</a></td>
                        <td>${item.create_date}</td>
                        <td>${item.status}</td></tr>`;
                    });
                }
                historyList += `</tbody></table>`;
                $('#historyDiv').html(historyList);
                $('#testHistoryTable').DataTable();
            }
        });
    });

    $(document).on('click', '.test-results', function(){
        $('#testResultDetailsModal').modal('show');
        const id = $(this).attr('id').replace('testResultId_', '');
        console.log(id)
        $.get('./api/manager.php?result=' + id).done(function(data){
            if(data){
                const result = JSON.parse(data);
                console.log(result);
                const dimensionResult = result;
                if(!dimensionResult){
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
                notification = result.saved_notification ? true: false;
                imgSrc = dimensionResult.img;
                //presentation

                $('#resultTitle').html(`${code} ${title}`);
                $('#resultDescription').html(description);
                $('#characterImg').attr('src', encodeURI(imgSrc));
    
                let tagHtml = `<span>你的标签： </span>`;
                tags.forEach(function(tag){
                    tagHtml += `<span class="badge badge-light" style="margin: 0 3px">#${tag.name}</span>`
                });
                $('#resultTags').html(tagHtml);
    
                let dimensionAnalytics = `<ul class="list-group list-group-flush">`;
                majorDimensions.forEach(function(item){
                    dimensionAnalytics += `<li class="list-group-item">${item.code} ${item.title}： ${item.description}</li>`;
                });
                dimensionAnalytics += `</ul>`;
                $('#dimensionAnalytics').html(dimensionAnalytics);
    
                $('#basicAnalytics').html(basicAnalysis);
    
                let advantages = `<ul>`;
                characteristics.forEach(function(item){
                    if(item.type === '优势'){
                        advantages += `<li>${item.description}</li>`;
                    }
                });
                advantages += `</ul>`;
                $('#advantageList').html(advantages);
    
                let disadvantages = `<ul>`;
                characteristics.forEach(function(item){
                    if(item.type === '盲点'){
                        disadvantages += `<li>${item.description}</li>`;
                    }
                });
                disadvantages += `</ul>`;
                $('#disadvantageList').html(disadvantages);
    
                let careerAnalytics = `<p>${career.description}</p>`;
    
                let programList = `<h5>可能适合的专业：</h5><ul>`;
    
                programs.forEach(function(item){
                    programList += `<li><a href="${item.link}" target="_blank">${item.title}</a></li>`;
                });
                programList += `</ul>`;
    
                let jobList = `<h5>你适合的职业：</h5><ul>`;
                jobs.forEach(function(item){
                    jobList += `<li>${item.description}</li>`;
                });
                jobList += `</ul>`;
                weights.forEach(function(weight, index){
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

    function generateMessage(type, content){
        return `<div class="alert alert-${type}" role="alert">
                    <i class="${type === 'success' ? 'fas fa-check' : 'fas fa-exclamation-triangle'}"></i> ${content}
                </div>`;
    }
    
    function validateEmailFormat(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return true;
        }
        return false;
    }

    function showMessage(target, type, content){
        const message = generateMessage(type, content);
        $(target).html(message);
        return;
    }

    function fetchMainData(){
        $.get('./api/manager.php').done(function(data){
            const result = JSON.parse(data);
            const { message, users, staff } = result;
            console.log(result);
            if(!result){
                messageDiv = generateMessage('danger', '抱歉，无法连接到服务器。请刷新重试。');
                return;
            }
            if(message){
                if(message.content === 'no login'){
                    $('#loginModal').modal('show');
                    messageDiv = generateMessage(message.type, '请点击<a href="#/" data-toggle="modal" data-target="#loginModal">这里</a>登录');
                }
                $('#message').html(messageDiv);
            }
            $('#message').html('');
            if(staff){
                $('#staffName').html("欢迎， " + staff.name);
                $('#staffTitle').html(staff.title);
                $('#staffDepartment').html(staff.department);
                $('#staffPermission').html(staff.permission);
                $('#staffStatus').html(staff.status);
            }
            if(users && Array.isArray(users)){
                $('#totalUser').html(users.length);
                let userTable = '';
                users.forEach(function(item){
                    userTable += `<tr><td><a href="#/" class="user-names" id="userId_${item.id}">${item.nick_name ? item.nick_name : ''}</a></td>
                    <td>${item.email ? item.email : ''}</td>
                    <td>${item.phone ? item.phone : ''}</td>
                    <td>${item.sex ? item.sex == 1 ? '男' : '女' : ''}</td>
                    <td>${item.city ? item.city : ''}</td>
                    <td>${item.province ? item.province : ''}</td>
                    <td>${item.country ? item.country : ''}</td></tr>`;
                });
                $('#userTableBody').html(userTable);
                $('#userTable').DataTable();
                
            }
        });
    }
});