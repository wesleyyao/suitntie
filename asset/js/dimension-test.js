$(document).ready(function () {
    const prefix = '/suitntie';
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
    let questionDiv = `
        <div class="row">
            <div class="col-12 text-center">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">正在加载测试...</span>
                </div>
                <div>正在加载测试...</div>
                <br/><br/>
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

    const notCompletedMessage =
        `<div class="alert alert-danger mt-3" role="alert">
         <i class="fas fa-exclamation-circle"></i> 请先回答完本页的问题。
        </div>`;

    $('.result-div').hide();
    $('#mainQuestionDiv').html(questionDiv);

    $(window).scroll(function (event) {
        let scroll = $(window).scrollTop();
        if (scroll >= 300) {
            $('#clockAndProgressBarDiv').addClass('stick-at-top');
        }
        else {
            $('#clockAndProgressBarDiv').removeClass('stick-at-top');
        }
    });

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
    });

    $('#nextPage').click(function () {
        let content = '';
        let numberOfQuestions = 0;
        if (!questions[questionTypesIndex]) {
            return;
        }
        if (questionsAndAnswersOnCurrentPage.length < actualQuestionsPerPage) {
            $('#message').html(notCompletedMessage);
            scrollToTestTop();
            return;
        }
        else {
            scrollToTestTop();
        }

        questionsAndAnswersOnCurrentPage.forEach(function (item) {
            let foundDimension = dimensions.find(dimension => dimension.id === parseInt(item.answerId));
            foundDimension.times++;
        });

        //calculate result
        if (numberOfAnswers === totalQuestions) {
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
                if (result == 'success') {
                    window.location.replace(`${prefix}/tests/dimension-test-result.php`);
                }
                else if (result == 'no login') {
                    window.location.replace(`${prefix}/tests/dimension-test.php`);
                }
                else {
                    const invalidFormMessage = `<div class="jumbotron jumbotron-fluid">
                    <div class="container text-center">
                      <h1 class="display-4 text-danger"> 
                      <i class="fas fa-times"></i> 
                      抱歉，该请求无法被处理。</h1>
                    </div>
                  </div>`;
                    $('#mainContentDiv').html(invalidFormMessage);
                }
            });
            return;
        }

        if (questions[questionTypesIndex].questions[page * questionsPerPage]) {
            let result = renderQuestionAndAnswer((page * questionsPerPage), (page * questionsPerPage + questionsPerPage), questions[questionTypesIndex], numberOfQuestions);
            content = result.content;
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
                content = result.content;
                numberOfQuestions = result.numberOfQuestions;
                renderedQuestions += result.numberOfQuestions;
                page++;
            }
        }
        actualQuestionsPerPage = numberOfQuestions;
        if (renderedQuestions === totalQuestions) {
            $('#nextPage').html('查看结果');
        }

        $('#message').html('');
        $('#questionTypeTitle').html(questions[questionTypesIndex] ? questions[questionTypesIndex].name : '');
        $('#mainQuestionDiv').html(content);
        questionsAndAnswersOnCurrentPage = [];
    });

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


    function renderQuestionAndAnswer(start, end, data, numberOfQuestions) {
        let content = '';
        for (let i = start; i < end; i++) {
            if (data.questions[i]) {
                content +=
                    `<div class="row">
                    <div class="offset-lg-4 col-lg-4 offset-md-2 col-md-8 col-sm-12">
                        <h4 class="">${data.questions[i].subject ? data.questions[i].subject : ''}</h4>
                    </div>
                </div>
                <div class="row">
                <div class="offset-lg-4 col-lg-4 offset-md-2 col-md-8 col-sm-12">`;
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
                content += `</div></div><br/>`;
                numberOfQuestions++;
            }
        }
        return { content, numberOfQuestions };
    }

    function scrollToTestTop() {
        $('html, body').animate({
            scrollTop: $("#message").offset().top
        }, 1000);
    }
});