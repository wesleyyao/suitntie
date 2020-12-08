import { generateMessage, prefix, fetchAccountData, showFloatMessage, useMessage, renderUserInNav, hideFloatMessage, currentPage, loadingMessage } from './common.js';

$(document).ready(function () {
    let second = 0;
    let minute = 0;
    const title = '适途16型人格测试';
    let questionsPerPage = 0;
    let questions = [];
    let dimensions = [];
    let questionTypesIndex = 0;
    let page = 1;
    let numberOfAnswers = 0;
    let questionsAndAnswersOnCurrentPage = [];
    let actualQuestionsPerPage = 0;
    let totalQuestions = 0;
    let renderedQuestions = 0;
    let setupClock = undefined;
    let results = [];
    let loginUser = undefined;
    let testInfo = undefined;
    let code = '';
    let expireTime = undefined;
    let loadingTest = `
        <div class="row">
            <div class="col-12 text-center">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">正在加载测试...</span>
                </div>
                <span class="h3">正在加载测试...</span>
            </div>
        </div>`;
    const proceeding = `
        <div class="jumbotron jumbotron-fluid">
            <div class="container text-center">
                <h1 class="display-4">
                <div class="spinner-border text-success" style="width: 3rem; height: 3rem; vertical-align: middle;" role="status">
                    <span class="sr-only">Loading...</span>
                </div> 
                请稍等，正在为您处理...</h1>
                <p class="lead">我们正在为您生成本次测试的结果。处理完成后，会自动为您跳转到测试结果页面。</p>
            </div>
        </div>`;

    const notCompletedMessage = generateMessage('warning', '请先回答完本页的问题。');

    $('#testOrView').hide();
    $('.result-div').hide();
    $('#finishTestForm').hide();
    $('#testTitle').html(title);
    $('#mainContentDiv').hide();
    showFloatMessage('#testMsg', loadingTest, 0);

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
        if (result === 'no login') {
            errorMessage = useMessage('warning', `未找到登录用户，请先<a href="../auth/login.html?redirect=${prefix}/tests/dimension-test.html">登录</a>`);
            showFloatMessage('#testMsg', errorMessage, 0);
            return;
        }
        if (user && !user.phone) {
            errorMessage = useMessage('warning', '在开始测试前，请您点击<a href="#/" data-toggle="modal" data-target="#userProfileCompleteModal">此处</a>完善联系方式。');
            showFloatMessage('#testMsg', errorMessage, 0);
            return;
        }
        hideFloatMessage('#testMsg', 0);
        $('#testOrView').fadeIn();
    });

    $('#startTesting').click(function () {
        $('#mainContentDiv').show();
        fetchTestDetails();
        scrollToTestTop();
    });


    $(window).scroll(function (event) {
        let scroll = $(window).scrollTop();
        if (scroll >= 300) {
            $('#clockAndProgressBarDiv').addClass('stick-at-top');
        }
        else {
            $('#clockAndProgressBarDiv').removeClass('stick-at-top');
        }
    });

    function fetchTestDetails() {
        $('#mainQuestionDiv').html(loadingTest);
        $.get(`${prefix}/public/api/test.php?title=${title}`).done(function (data) {
            if (data) {
                let result = JSON.parse(data);
                const { dimension, test, user } = result;
                dimensions = dimension ? dimension.dimensions.map(item => ({ ...item, times: 0 })) : [];
                results = dimension ? dimension.dimension_combinations : [];
                loginUser = user ? user : undefined;
                questions = test ? test.types : [];
                totalQuestions = test ? test.total : 0;
                testInfo = test ? test.test : undefined;
                questionsPerPage = questions[questionTypesIndex].questions.length >= questions[questionTypesIndex].questions_per_page ?
                    questions[questionTypesIndex].questions_per_page : questions[questionTypesIndex].questions.length;
                actualQuestionsPerPage = questionsPerPage;
                setupClock = setInterval(function () {
                    second++;
                    if (second == 60) {
                        second = 0;
                        minute++;
                    }
                    $('#clock').html(`${minute < 10 ? '0' + minute : minute} : ${second < 10 ? '0' + second : second}`);
                    if (minute >= 20) {
                        $('#clock').addClass('text-danger');
                    }
                }, 1000);
                $('#progress').attr('aria-valuemax', totalQuestions);
            }
            let firstPageQuestions = '';
            let numberOfQuestions = 0;
            if (questions[questionTypesIndex] && questions[questionTypesIndex].questions && questions[questionTypesIndex].questions.length > 0) {
                let result = renderQuestionAndAnswer(0, questionsPerPage, questions[questionTypesIndex], numberOfQuestions);
                renderedQuestions += result.numberOfQuestions;
                firstPageQuestions = result.content;
            }
            $('#testTitle').html(title);
            $('#questionTypeTitle').html(questions[questionTypesIndex].name);
            $('#mainQuestionDiv').html(firstPageQuestions);
            $('#testOrView').hide();
        });
    }

    $('#nextPage').click(function () {
        let content = '';
        let numberOfQuestions = 0;
        if (!questions[questionTypesIndex]) {
            return;
        }

        if (questionsAndAnswersOnCurrentPage.length < actualQuestionsPerPage) {
            let errorMessage = useMessage('warning', '请先回答完本页的问题。');
            showFloatMessage('#testMsg', errorMessage, 3000);
            return;
        }

        questionsAndAnswersOnCurrentPage.forEach(function (item) {
            let foundDimension = dimensions.find(dimension => dimension.id === parseInt(item.answerId));
            foundDimension.times++;
        });

        if (questions[questionTypesIndex].questions[page * questionsPerPage]) {
            let result = renderQuestionAndAnswer((page * questionsPerPage), (page * questionsPerPage + questionsPerPage), questions[questionTypesIndex], numberOfQuestions);
            content = questionTypesIndex === 1 ? `<div class="row">
            <div class="col-lg-2 col-md-1 col-sm-0 col-0"></div>
            <div class="col-lg-8 col-sm-10 col-sm-12 col-12">
                <div class="row">` + result.content + `</div></div></div>` : result.content;
            numberOfQuestions = result.numberOfQuestions;
            renderedQuestions += result.numberOfQuestions;
            page++;
        }
        else {
            questionTypesIndex++;
            page = 0;
            if (questions[questionTypesIndex] && questions[questionTypesIndex].questions && questions[questionTypesIndex].questions.length > 0) {
                questionsPerPage = questions[questionTypesIndex].questions_per_page;
                let result = renderQuestionAndAnswer((page * questionsPerPage), (page * questionsPerPage + questionsPerPage), questions[questionTypesIndex], numberOfQuestions);
                content = questionTypesIndex === 1 ? `<div class="row">
                <div class="col-lg-2 col-md-1 col-sm-0 col-0"></div>
                <div class="col-lg-8 col-sm-10 col-sm-12 col-12">
                    <div class="row">` + result.content + `</div></div></div>` : result.content;
                numberOfQuestions = result.numberOfQuestions;
                renderedQuestions += result.numberOfQuestions;
                page++;
            }
        }

        actualQuestionsPerPage = numberOfQuestions;

        if (numberOfAnswers + actualQuestionsPerPage === totalQuestions) {
            if (loginUser && loginUser.full_name && loginUser.age) {
                $('#nextPage').text('查看结果');
            }
            else {
                $('#nextPage').text('下一步');
            }
        }

        if (numberOfAnswers === totalQuestions) {
            if (loginUser && loginUser.full_name && loginUser.age) {
                submitTest(loginUser.full_name, loginUser.age, loginUser.is_study_aboard, loginUser.how_know);
            }
            else {
                $('#mainContentDiv').fadeOut(function () {
                    $('#finishTestForm').fadeIn();
                });
            }
        }
        $('#questionTypeTitle').html(questions[questionTypesIndex] ? questions[questionTypesIndex].name : '');
        $('#mainQuestionDiv').html(content);
        questionsAndAnswersOnCurrentPage = [];
        scrollToTestTop();
    });

    $('#submitTestFormBtn').click(function () {
        let errorMessage = '';
        if (numberOfAnswers !== totalQuestions) {
            errorMessage = useMessage('warning', '抱歉，测试过程出现了问题，请刷新页面后重试。');
            showFloatMessage('#testMsg', errorMessage, 0);
            return;
        }
        const testUserName = $('#testUserName').val().replace(/\s/g, "");
        const testUserAge = $('#testUserAge').val();
        let purpose = '';
        $('input[name="testUserStudyAboard"]').each(function () {
            if ($(this).prop('checked')) {
                purpose = $(this).val();
            }
        });
        const checked = collectCheckBoxValues('how-you-know');
        if (!testUserName || !testUserAge || !purpose || !checked.replace(/\s/g, "")) {
            errorMessage = useMessage('warning', '在提交前，请填写所有必填项。');
            showFloatMessage('#testMsg', errorMessage, 3000);
            return;
        }
        submitTest(testUserName, testUserAge, purpose, checked);
    });

    function collectCheckBoxValues(target) {
        let checked = '';
        $('.' + target).each(function () {
            const isCheck = $(this).prop('checked');
            if (isCheck) {
                checked += $(this).val() + ' ';
            }
        });
        return checked;
    }

    function renderAnswerWithoutQuestion(numberOfQuestions) {
        let result = renderQuestionAndAnswer((page * questionsPerPage), (page * questionsPerPage + questionsPerPage), questions[questionTypesIndex], numberOfQuestions);
        return `<div class="row">
        <div class="col-lg-2 col-md-1 col-sm-0 col-0"></div>
        <div class="col-lg-8 col-sm-10 col-sm-12 col-12">
            <div class="row">` + result.content + `</div></div></div>`;
    }

    $(document).on('click', '.answer-div', function () {
        const questionId = $(this).find('input').attr('name').split('_')[3];
        const answerId = $(this).find('input').val();
        if (!questionsAndAnswersOnCurrentPage.find(item => item.questionId === questionId)) {
            questionsAndAnswersOnCurrentPage.push({ questionId, answerId });
            numberOfAnswers++;
            $('#progress').attr('aria-valuenow', numberOfAnswers);
            $('#progress').css('width', (parseInt(numberOfAnswers / totalQuestions * 100) + '%'));
            $('#percentage').html(parseInt(numberOfAnswers / totalQuestions * 100) + '%');
        }
        else {
            let position = -1;
            questionsAndAnswersOnCurrentPage.forEach(function (item, index) {
                if (item.questionId === questionId) {
                    position = index;
                }
            });
            if (position > -1) {
                questionsAndAnswersOnCurrentPage[position].answerId = answerId;
            }
        }
        $(this).addClass('answer-selected');
        $(this).siblings().removeClass('answer-selected');
    });

    function submitTest(testUserName, testUserAge, purpose, knownBy) {
        let groups = dimensions.map((item) => item.compare_group);
        groups = groups.filter((item, index) => groups.indexOf(item) === index);
        let resultCode = '';
        let resultDimensions = [];
        groups.forEach(function (group) {
            let compareDimensions = dimensions.filter((item) => item.compare_group === group);
            let sortedDimensions = compareDimensions.sort((a, b) => b.times - a.times);
            resultCode += sortedDimensions[0].code;
            resultDimensions.push(sortedDimensions[0]);
        });
        let formData = {
            result_codes: resultCode,
            test_id: testInfo ? testInfo.id : 0,
            userName: testUserName,
            userAge: testUserAge,
            purpose,
            knownBy
        };
        $('#testId').val(testInfo ? testInfo.id : 0);
        $('#resultCodes').val(resultCode);
        $('#resultTimes').val(resultDimensions.map(item => item.times).toString());
        dimensions.forEach(function (item) {
            formData['dimension_' + item.id] = item.times;
        });

        $('#clockAndProgressBarDiv').hide();
        $('#mainContentDiv').html(proceeding);
        $.post(`${prefix}/public/api/proceed-result.php`, formData).done(function (data) {
            const result = JSON.parse(data);
            let invalidFormMessage = '';
            if (result == 'success') {
                window.location.replace(`${prefix}/tests/dimension-test-result.html`);
            }
            else if (result == 'no login') {
                window.location.replace(`${prefix}/tests/dimension-test`);
            }
            else if (result == 'finished request') {
                invalidFormMessage = `<div class="jumbotron jumbotron-fluid">
                    <div class="container text-center">
                      <h1 class="display-4 text-danger"> 
                      <i class="fas fa-times"></i> 
                     你的测试结果已经被提交，请移步用户中心<a href="${prefix}/account/index.html?view=history">用户中心</a>查看记录。</h1>
                    </div>
                  </div>`;
            }
            else {
                invalidFormMessage = `<div class="jumbotron jumbotron-fluid">
                    <div class="container text-center">
                      <h1 class="display-4 text-danger"> 
                      <i class="fas fa-times"></i> 
                      抱歉，该请求无法被处理。 请刷新页面重新测试。</h1>
                    </div>
                  </div>`;
                $('#mainContentDiv').html(invalidFormMessage);
            }
        });
    }

    function renderQuestionAndAnswer(start, end, data, numberOfQuestions) {
        let content = '';
        for (let i = start; i < end; i++) {
            if (data.questions[i]) {
                if (!data.questions[i].subject) {
                    content += `<div class="col-lg-4 col-md-6 col-sm-6 col-6 mb-3">`;
                    for (let j = 0; j < data.questions[i].answers.length; j++) {
                        content +=
                            `<label class="answer-div" style="padding-bottom: 5px">                    
                                    <input type="radio" name="answer_for_question_${data.questions[i].answers[j].question_id}" 
                                        value="${data.questions[i].answers[j].dimension_id}">
                                    <span class="answer-text">${data.questions[i].answers[j].subject}</span>
                                </label>`;
                    }
                    content += `</div>`;
                }
                else {
                    content +=
                        `<div class="row">
                        <div class="offset-lg-4 col-lg-4 offset-md-2 col-md-8 col-sm-12">
                            <h4 class="">${data.questions[i].subject ? data.questions[i].subject : ''}</h4>
                        </div>
                    </div>
                    <div class="row">`;
                    content += `<div class="offset-lg-4 col-lg-4 offset-md-2 col-md-8 col-sm-12">`;
                    for (let j = 0; j < data.questions[i].answers.length; j++) {
                        content +=
                            `
                                <label class="answer-div" style="padding-bottom: 5px">                    
                                    <input type="radio" name="answer_for_question_${data.questions[i].answers[j].question_id}" 
                                        value="${data.questions[i].answers[j].dimension_id}">
                                    <span class="answer-text">${data.questions[i].answers[j].subject}</span>
                                </label>
                           `;
                    }
                    content += `</div></div>`;
                }
                numberOfQuestions++;
            }
        }
        return { content, numberOfQuestions };
    }

    function scrollToTestTop() {
        $('html, body').animate({
            scrollTop: $("#mainContentDiv").offset().top - 130
        }, 1000);
    }

    $('#sendPhoneVerifyCodeBtn').click(function () {
        const from = $(this).attr('id');
        const buttonId = '#' + from;
        const phone = $('#signupPhone').val().replace(/\s/g, '');
        if (phone.length !== 11 || isNaN(phone) || !phone) {
            $('#profileCompleteMessage').html(generateMessage('warning', '请输入正确的手机号。'));
            return;
        }

        $(buttonId).prop('disabled', true);
        let seconds = 45;
        let cooling = setInterval(function () {
            if (seconds > 0) {
                seconds--;
                $(buttonId).text(`${seconds}秒后重新发送`);
            }
            else {
                $(buttonId).prop('disabled', false);
                $(buttonId).text('获取验证码');
                clearInterval(cooling);
            }
        }, 1000);

        code = Math.floor(1000 + Math.random() * 9000);
        const currentTime = new Date();
        expireTime = new Date(currentTime.getTime() + 1 * 60000);
        $.post(`${prefix}/public/api/phone-verify-local.php`, { phone, code }).done(function (data) {
            if (data) {
                //const result = { SendStatusSet: [{ Code: 'Ok', PhoneNumber: '13141036635' }] };
                const result = JSON.parse(data);
                const sendStatus =
                    result &&
                        result.SendStatusSet &&
                        Array.isArray(result.SendStatusSet) &&
                        result.SendStatusSet.length > 0 ? result.SendStatusSet[0] : undefined;
                if (!sendStatus) {
                    $('#profileCompleteMessage').html(generateMessage('warning', '连接出现异常，请刷新后重试。'));
                    return;
                }
                if (sendStatus.Code === 'Ok' && sendStatus.PhoneNumber === '+86' + phone) {
                    $('#profileCompleteMessage').html(generateMessage('success', '验证码已经发送。'));
                    return;
                }
            }
            else {
                $('#profileCompleteMessage').html(generateMessage('danger', '连接出现异常，请刷新后重试。'));
                return;
            }
        });
    });

    $('#userProfileCompleteForm').submit(function (e) {
        e.preventDefault();
        const phone = $('#signupPhone').val().replace(/\s/g, '');
        const verifyCode = $('#signupVerifyCode').val().replace(/\s/g, '');
        if (!phone || isNaN(phone) || phone.length !== 11) {
            $('#profileCompleteMessage').html(generateMessage('danger', '请填写有效的手机号。'));
            $('#profileCompleteMessage').fadeIn().delay(3000).fadeOut();
            return;
        }
        if (!verifyCode || isNaN(verifyCode) || parseInt(verifyCode) !== parseInt(code)) {
            $('#profileCompleteMessage').html(generateMessage('danger', '无效的验证码。'));
            $('#profileCompleteMessage').fadeIn().delay(3000).fadeOut();
            return;
        }
        $('#profileCompleteMessage').html(loadingMessage);
        $.post(`${prefix}/public/auth/user-signup.php`, { phone, by: 'phone' }).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                $('#profileCompleteMessage').html(generateMessage(result.type, result.content));
                if (result.type === 'success') {
                    setTimeout(function () {
                        window.location.href = currentPage;
                    }, 2000);
                }
            }
            else {
                $('#profileCompleteMessage').html(generateMessage('danger', '请求未被处理，请重试。'));
                return;
            }
        });
    });
});