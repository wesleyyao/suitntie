import { formValidation, prefix, submittingMessage, generateMessage, fetchAccount, windowSize } from './common.js';

$(document).ready(function () {
    const noData = `
    <div class="alert alert-light" role="alert">
        未找到任何导师数据。
    </div>`;
    const loadingData = `
    <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-12">
            <div class="card border-light">
                <div class="card-body">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span class="h5">正在加载导师数据...</span>
                </div>
            </div>
        </div>
    </div>`;
    let reference = undefined;
    let consultants = [];
    let filteredData = [];
    let currentFilters = { region: '0', education: '0', program: '0' };
    let selectedConsultant = undefined;
    let pages = 0;
    const onPage = 3;
    let currentPage = 1;
    const briefLength = windowSize.width > 1400 ?
        200 : windowSize.width > 1024 ?
            180 : windowSize.width > 768 ?
                160 : windowSize.width > 500 ?
                    130 : windowSize.width > 380 ?
                        80 : 60;

    $('#adsContent').html(windowSize.width > 768 ? `
    加入适途导师团队<br />线上获取优厚报酬<br />分享经验帮助他人<br />传播智慧成就自己<br/>` :
        `加入适途导师团队，线上获取优厚报酬<br/>分享经验帮助他人，传播智慧成就自己<br/>`);

    fetchAccount();
    fetchConsultData();

    $(document).on('click', '.region-option', function () {
        const id = $(this).attr('id').replace('region', '');
        updateFilters($(this), 'region', id);
        renderList(filteredData);
    });

    $(document).on('click', '.program-option', function () {
        const id = $(this).attr('id').replace('program', '');
        updateFilters($(this), 'program', id);
        renderList(filteredData);
    });

    $(document).on('click', '.education-option', function () {
        const id = $(this).attr('id').replace('education', '');
        updateFilters($(this), 'education', id);
        renderList(filteredData);
    });

    $('#resetBtn').click(function () {
        $('#filterInput').val('');
        currentFilters = { region: '0', education: '0', program: '0' };
        updateFilters(null, 'region', 0);
        updateFilters(null, 'program', 0);
        updateFilters(null, 'education', 0);
        $('.filter').each(function () {
            $(this).removeClass('filter-active');
        });
        $('#region0').addClass('filter-active');
        $('#program0').addClass('filter-active');
        $('#education0').addClass('filter-active');
        filteredData = consultants;
        renderList(filteredData);
    });

    $('.close-ads').click(function () {
        $('#adsDiv').fadeOut();
    });

    $(document).on('click', '.show-brief', function () {
        const id = $(this).attr('id').replace('showBrief', '');
        const allBrief = filteredData.find(item => item.id == id)?.introduction;
        $('#briefDiv' + id).html(`<span class="pr-1">${allBrief}<span><a href="#/" class="hide-brief" id="hideBrief${id}"><span class="badge badge-primary" style="background-color: #F19F4D">收回 <i class="fas fa-angle-up"></i></span></a>`);
        $('#briefDiv' + id).addClass('scroll-brief');
        $('#consultant_card_' + id).css('height', 'auto');
        $('.consultant-card').each(function () {
            const cardId = $(this).attr('id').replace('consultant_card_', '');
            if (cardId !== id) {
                const foundData = filteredData.find(item => item.id == cardId);
                const partialBrief = foundData && foundData.introduction ?
                    foundData.introduction.length > briefLength ?
                        `<span class="pr-1">${foundData.introduction.slice(0, briefLength - 1)}...<span>
                    <a href="#/" class="show-brief" id="showBrief${foundData.id}">
                    <span class="badge badge-primary" style="background-color: #F19F4D">
                    查看更多 <i class="fas fa-angle-down"></i>
                    </span>
                    </a>` : foundData.introduction : '';
                $('#briefDiv' + cardId).html(partialBrief);
            }
        });
    });

    $(document).on('click', '.hide-brief', function () {
        const id = $(this).attr('id').replace('hideBrief', '');
        const foundData = filteredData.find(item => item.id == id);
        const partialBrief = foundData && foundData.introduction ?
            foundData.introduction.length > briefLength ?
                `<span class="pr-1">${foundData.introduction.slice(0, briefLength - 1)}...<span>
                <a href="#/" class="show-brief" id="showBrief${foundData.id}">
                <span class="badge badge-primary" style="background-color: #F19F4D">
                查看更多 <i class="fas fa-angle-down"></i>
                </span>
                </a>` : foundData.introduction : '';
        $('#briefDiv' + id).html(partialBrief);
    });

    $('#searchBtn').click(function () {
        const val = $('#filterInput').val().replace(/\s+/g, '');
        let newFilteredData = consultants.filter(
            item => item.programs.find(
                p => p.title.indexOf(val) != -1 ||
                    p.education.indexOf(val) != -1 ||
                    p.region_name.indexOf(val) != -1 ||
                    p.school.indexOf(val) != -1) || item.nick_name.toLowerCase().indexOf(val.toLowerCase()) != -1 ||
                (item.introduction && item.introduction.indexOf(val.toLowerCase()) != -1));
        filteredData = newFilteredData;
        renderList(filteredData);
    });

    $(document).on('click', '.mail-btn', function () {
        const id = $(this).attr('id').replace('contact', '');
        selectedConsultant = consultants.find(item => item.id == id);
        $('#tutorName').text(selectedConsultant.nick_name);
        $('#contactConsultantModal').modal('show');
    });

    $('#submitContactFormBtn').click(function () {
        $('#contactConsultant').prop('disabled', true);
        const email = $('#ccEmail').val().replace(/\s/g, '');
        if (!formValidation('.cc-required', '#ccMessage', email)) {
            $('#ccMessage').html(generateMessage('warning', '请重新核对填写的内容。'));
            $('#ccMessage').fadeIn().delay(3000).fadeOut();
            $('#contactConsultant').prop('disabled', false);
            return;
        }
        const phone = $('#ccPhone').val().replace(/\s/g, '');
        const name = $('#ccName').val().replace(/\s/g, '');
        const wechat = $('#ccWechat').val().replace(/\s/g, '');
        const age = $('#ccAge').val().replace(/\s/g, '');
        const school = $('#ccSchool').val();
        const content = $('#ccContent').val();
        $('#ccMessage').html(submittingMessage);
        $('#ccMessage').fadeIn();
        $.post(`${prefix}/public/api/contact.php?type=consultant`, { email, phone, name, wechat, age, school, content, consultant: selectedConsultant.nick_name }).done(function (data) {
            if (!data) {
                $('#ccMessage').html(generateMessage('warning', '抱歉，无法连接服务器。请稍后重试。'));
            }
            const result = JSON.parse(data);
            if (result) {
                $('#ccMessage').html(generateMessage('success', '提交成功！我们的工作人员会及时回复您。'));
            }
            else {
                $('#ccMessage').html(generateMessage('warning', '抱歉，提交未被处理。请稍后重试。'));
            }
            $('#ccMessage').fadeIn().delay(3000).fadeOut();
            $('.cc-input').each(function () {
                $(this).val('');
            });
            $('#contactConsultant').prop('disabled', false);
        });
    });

    $(document).on('click', '.page-btn', function () {
        const target = $(this).attr('id');
        if (target == 'prevPageBtn') {
            if (currentPage <= pages && currentPage > 1) {
                currentPage--;
            }
        }
        else if (target == 'nextPageBtn') {
            if (currentPage < pages && currentPage >= 1) {
                currentPage++;
            }
        }
        else {
            let pageNumber = $(this).attr('id').replace('consultPageNumber', '');
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
            currentPage = pageNumber++;
        }
        renderList(filteredData);
        backToTop();
    });

    $(document).on('change', '#pageDropDown', function () {
        const page = $(this).val();
        if (!page || !page.replace(/\s/g, '') || isNaN(page) || parseInt(page) > pages) {
            return;
        }
        currentPage = parseInt(page);
        renderList(filteredData);
        backToTop();
    });

    function backToTop() {
        $('html, body').animate({
            scrollTop: $("#consultantDiv").offset().top
        }, 1000);
    }

    function updateFilters(selected, target, value) {
        if (selected) {
            selected.removeClass('filter-active').addClass('filter-active');
            selected.siblings().removeClass('filter-active');
        }
        currentFilters[target] = value;
        currentPage = 1;
        const foundProgram = reference && reference.programs ? reference.programs.find(item => item.id == currentFilters.program) : undefined;
        let newFilteredData = consultants.filter(
            item => item.programs.find(
                p => (currentFilters.program != 0 ? foundProgram?.details.find(f => f.id == p.program_id) : 1) &&
                    (currentFilters.education != 0 ? p.education == currentFilters.education : 1) &&
                    (currentFilters.region != 0 ? p.region == currentFilters.region : 1)));
        filteredData = newFilteredData;
        if (!reference || !reference.educations || !reference.programs || !reference.regions) {
            return;
        }
        if (currentFilters.program == 0 && currentFilters.education == 0 && currentFilters.region == 0) {
            $('#filterTags').html("");
        }
        else {
            renderFilterTags();
        }
    }

    $(document).on('click', '.filter-remove-btn', function () {
        const targetId = $(this).attr('id');
        let newFilteredData = [];
        const foundProgram = reference && reference.programs ? reference.programs.find(item => item.id == currentFilters.program) : undefined;
        if (targetId === 'removeProgramFilter') {
            resetFilterByTarget('program-option');
            currentFilters["program"] = '0';
            newFilteredData = consultants.filter(
                item => item.programs.find(
                    p => (currentFilters.education != 0 ? p.education == currentFilters.education : 1) &&
                        (currentFilters.region != 0 ? p.region == currentFilters.region : 1)));
        }
        else if (targetId === 'removeEducationFilter') {
            resetFilterByTarget('education-option');
            currentFilters["education"] = '0';
            newFilteredData = consultants.filter(
                item => item.programs.find(
                    p => (currentFilters.program != 0 ? foundProgram?.details.find(f => f.id == p.program_id) : 1) &&
                        (currentFilters.region != 0 ? p.region == currentFilters.region : 1)));
        }
        else {
            resetFilterByTarget('region-option');
            currentFilters["region"] = '0';
            newFilteredData = consultants.filter(
                item => item.programs.find(
                    p => (currentFilters.education != 0 ? p.education == currentFilters.education : 1) &&
                        (currentFilters.program != 0 ? foundProgram?.details.find(f => f.id == p.program_id) : 1)));
        }
        filteredData = newFilteredData;
        renderFilterTags();
        renderList(filteredData);
    });

    function resetFilterByTarget(target) {
        $('.' + target).each(function (index) {
            if (index === 0) {
                $(this).addClass('filter-active');
            }
            else {
                $(this).removeClass('filter-active');
            }
        });
    }

    function renderFilterTags() {
        $('#filterTags').html(`
        <div class="row pt-3">
            <div class="col-12">
                ${currentFilters.program != 0 ?
                `<span class="filter-tag">
                    ${reference.programs.find(item => item.id == currentFilters.program)?.name}
                    <a class="filter-remove-btn" id="removeProgramFilter" href="#/"><i class="fas fa-times"></i></a>
                    </span>` : ''
            }
                ${currentFilters.education != 0 ?
                `<span class="filter-tag">
                    ${currentFilters.education}
                    <a class="filter-remove-btn" id="removeEducationFilter" href="#/"><i class="fas fa-times"></i></a>
                    </span>` : ''
            }
                ${currentFilters.region != 0 ?
                `<span class="filter-tag">
                     ${reference.regions.find(item => item.id == currentFilters.region)?.name}
                     <a class="filter-remove-btn" id="removeRegionFilter" href="#/"><i class="fas fa-times"></i></a>
                     </span>` : ''
            }
            </div>
        </div>`);
    }

    function fetchConsultData() {
        $('#consultantList').html(loadingData);
        $.get(`${prefix}/public/api/consult.php`).done(function (data) {
            if (!data) {
                $('#consultantList').html(noData);
                return;
            }
            const result = JSON.parse(data);
            reference = result.references;
            consultants = result.consultants;
            filteredData = consultants;
            renderFilters(reference, consultants);
            renderList(filteredData);
        }).fail(function (e) {
            $('#consultantList').html(`<span>Couldn't connect to the server.</span>`);
        });
    }

    function renderFilters(reference) {
        if (!reference || !reference.educations || !reference.programs || !reference.regions) {
            return;
        }
        let regionOptions = `<a href="#/" id="region0" class="region-option filter filter-active">不限</a>`;
        reference.regions.forEach(function (item) {
            regionOptions += `<a href="#/" id="region${item.id}" class="region-option filter">${item.name}</a>`;
        });
        $('#regionOptions').html(regionOptions);

        let programOptions = `<a href="#/" id="program0" class="program-option filter filter-active">不限</a>`;
        let programs = reference.programs ? reference.programs : [];
        programs.forEach(function (item) {
            programOptions += `<a href="#/" id="program${item.id}" class="program-option filter">${item.name}</a>`;
        });
        $('#programOptions').html(programOptions);

        let educatonOptions = `<a href="#/" id="education0" class="education-option filter filter-active">不限</a>`;
        let educations = reference.educations ? reference.educations : [];
        educations.forEach(function (item) {
            educatonOptions += `<a href="#/" id="education${item}" class="education-option filter">${item}</a>`;
        });
        $('#educationOptions').html(educatonOptions);
    }

    function renderList(filteredData) {
        if (!filteredData) {
            return;
        }
        let content = '';
        filteredData.forEach(function (item, index) {
            if (index > (currentPage * onPage - 1) || index < ((currentPage - 1) * onPage)) {
                return;
            }
            let programTitles = `<ul class="list-inline">`;
            new Set(item.programs.map(a => a.title)).forEach(function (p) {
                programTitles += `<li class="list-inline-item">${p}</li>`;
            });
            let schools = `<ul class="list-inline">`;
            new Set(item.programs.map(a => a.school)).forEach(function (p) {
                schools += `<li class="list-inline-item">${p}</li>`;
            });
            const brief = item.introduction ? item.introduction.length > briefLength ?
                `<span class="pr-1">${item.introduction.slice(0, briefLength - 1)}...</span>
            <a href="#/" class="show-brief" id="showBrief${item.id}">
            <span class="badge badge-primary" style="background-color: #F19F4D">查看更多 <i class="fas fa-angle-down"></i></span>
            </a>` : item.introduction : '';
            content += `<div class="card border-light mb-2 consultant-card" id="consultant_card_${item.id}" style="background:#f9f9f9">
                    <div class="card-body">
                        <button class="mail-btn btn primBtnSM" id="contact${item.id}"><i class="fas fa-envelope"></i></button>
                        <div class="consultant-card-avatar text-center">
                            <div><img style="width: 80px; height: 80px; border-radius: 50%" src="${prefix}${item.thumbnail}"/>
                            </div>
                            <div><label>${item.nick_name}</label></div>
                        </div>
                        <div class="consultant-card-content">
                            <div>
                                <div style="display: inline-block; width: 70px">
                                    <span><strong>专业领域</strong></span>
                                </div>
                                <div style="display: inline-block;">
                                    ${programTitles}
                                </div>
                            </div>
                            <div>
                                <div style="display: inline-block; width: 70px">
                                    <span><strong>毕业院校</strong></span>
                                </div>
                                <div style="display: inline-block;">
                                    ${schools}
                                </div>
                            </div>
                            <div id="briefDiv${item.id}">
                                ${brief}
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        pages = Math.round(filteredData.length / 3);

        let pageItems = '';
        for (let i = 0; i < pages; i++) {
            pageItems += `<option value="${i + 1}" ${currentPage == (i + 1) ? 'selected' : ''}>${i + 1}</option>`;
        }

        const paginationDiv = `<div class="text-center p-2">
            <button id="prevPageBtn" class="page-btn ${pages <= 1 || currentPage <= 1 ? 'disabled' : ''} btn primBtnSM" href="#/">上一页</button>
            <select class="form-control page-input" style="vertical-align: bottom" id="pageDropDown">
            ${pageItems}
            </select>
            <button id="nextPageBtn" class="page-btn ${pages <= 1 || currentPage === pages ? 'disabled' : ''} btn primBtnSM" href="#/">下一页</button>
        </div>`;
        // let pageNumberButtons = '';
        // for (let i = 0; i < pages; i++) {
        //     pageNumberButtons += `<li class="page-item page-btn ${currentPage === i + 1 ? 'active' : ''}" id="consultPageNumber${i + 1}"><a class="page-link" href="#/">${i + 1}</a></li>`;
        // }
        // const paginationEnd = `</ul></nav>`;

        $('#consultantList').html(content + paginationDiv);
    }
});