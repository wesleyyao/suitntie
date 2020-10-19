import { generateMessage, prefix } from './common.js';

$(document).ready(function () {

    const noData = `
    <div class="alert alert-light" role="alert">
        未找到任何导师数据。
    </div>`;
    let reference = undefined;
    let consultants = [];
    let filteredData = [];
    fetchConsultData();
    let currentFilters = { region: '0', education: '0', program: '0' };

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

    $(document).on('click', '.show-brief', function(){
        const id = $(this).attr('id').replace('showBrief', '');
        const allBrief = filteredData.find(item => item.id == id)?.introduction;
        $('#briefDiv' + id).html(`${allBrief} <a href="#/" class="hide-brief" id="hideBrief${id}">收回</a>`);
        //$('#briefDiv' + id).addClass('')
    });

    function updateFilters(selected, target, value) {
        selected.removeClass('filter-active').addClass('filter-active');
        selected.siblings().removeClass('filter-active');
        currentFilters[target] = value;
        const foundProgram = reference && reference.programs ? reference.programs.find(item => item.id == currentFilters.program) : undefined;
        let newFilteredData = consultants.filter(
            item => item.programs.find(
                p => (currentFilters.program != 0 ? foundProgram?.details.find(f => f.id == p.program_id) : 1) &&
                    (currentFilters.education != 0 ? p.education == currentFilters.education : 1) &&
                    (currentFilters.region != 0 ? p.region == currentFilters.region : 1)));

        console.log(currentFilters);
        console.log(reference.programs);
        filteredData = newFilteredData;
    }

    function fetchConsultData() {
        $.get(`${prefix}/public/api/consult.php`).done(function (data) {
            const result = JSON.parse(data);
            if (!result || result.length == 0) {
                $('#consultantList').html(noData);
                return;
            }
            console.log(result);
            reference = result.references;
            consultants = result.consultants;
            filteredData = consultants;
            renderFilters(reference, consultants);
            renderList(filteredData);
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
        filteredData.forEach(function (item) {
            let programTitles = `<ul class="list-inline">`;
            new Set(item.programs.map(a => a.title)).forEach(function (p) {
                programTitles += `<li class="list-inline-item">${p}</li>`;
            });
            let schools = `<ul class="list-inline">`;
            new Set(item.programs.map(a => a.school)).forEach(function (p) {
                schools += `<li class="list-inline-item">${p}</li>`;
            });
            const brief = item.introduction ? item.introduction.length > 150 ? `${item.introduction.slice(0, 149)}... <a href="#/" class="show-brief" id="showBrief${item.id}">查看更多</a>` : item.introduction : '';

            content += `
                <div class="card border-light mb-2 consultant-card" style="background:#f9f9f9">
                    <div class="card-body">
                        <div style="display: inline-block; width: 20%;" class="text-center">
                            <div><img style="width: 80px; height: 80px;" src="${prefix}${item.thumbnail}"/>
                            </div>
                            <div><label>${item.nick_name}</label></div>
                        </div>
                        <div style="display: inline-block; width: 70%; vertical-align: top">
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
                </div>            
            `;
        });
        $('#consultantList').html(content);
    }
});