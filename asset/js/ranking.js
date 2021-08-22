import { formValidation, prefix, submittingMessage, generateMessage, fetchAccount, windowSize } from './common.js';

$(document).ready(function () {
    const noData = `
    <tr>
    <td></td>
    <td>
    <div class="alert alert-light" role="alert">
        未找到数据。
    </div>
    </td>
    <td></td>
    </tr>`;
    const loadingData = `
    <tr>
    <td></td>
    <td>
    <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-12">
            <div class="card" style="border: none">
                <div class="card-body">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span class="h4">正在加载数据...</span>
                </div>
            </div>
        </div>
    </div>
    </td>
    <td></td>
    </tr>`;

    let rankingList = [];
    let rankingData = [];
    let overallData = [];
    const popularRegions = ['美国', '英国', '澳大利亚', '加拿大'];
    let schools = ['综合排名'];
    let currentCountry = "all";
    let currentProgram = "all";
    let currentPage = 1;
    let pageSize = 0;
    const NUMBER_PER_PAGE = 6;

    fetchAccount();
    fetchRankingData();
    fetchPrograms();

    function renderRankingTable(data) {
        let tableBody = '';
        for (let i = 0; i < data.length; i++) {
            if (i >= (currentPage * NUMBER_PER_PAGE)) {
                break;
            }
            tableBody += `<tr>
            <td class="text-center align-middle">${data[i].rank}</td>
            <td><div class="d-inline-flex align-middle">
            <img id="schoolLogo${data[i].$rankingId || data[i].id}" class="ranking-school-logo-sm mr-3 nopadding" src="${prefix}/asset/image/logo/${data[i].logoPath}" alt="${data[i].university}Logo" />
            <label class="pt-3">${data[i].university}</label>
            </div></td>
            <td class="align-middle">${data[i].country}</td></tr>`;
        }
        return tableBody;
    }

    function renderSchoolProgramRankingTable(data) {
        let tableBody = '';
        data.forEach((item, index) => {
            tableBody += `<tr>
            <td>${item.title}</td>
            <td>${item.rank}</td></tr>`;
        });
        return tableBody;
    }

    function convertFilterData(data, filterName) {
        let newData = [];
        if (filterName === 'country') {
            newData = _.uniq(data.map((item, index) => item.country));
            let notPopularRegions = [];
            for (let i = 0; i < newData.length; i++) {
                if (!popularRegions.includes(newData[i])) {
                    notPopularRegions.push({ id: newData[i], text: newData[i] });
                }
            }

            // newData = newData.map((item, index) => { return { id: item, text: item, rIndex: popularRegions.includes(item) ? 0 : index + 4 } });
            // newData = newData.sort((a, b) => a.rIndex - b.rIndex);
            newData = [
                { id: 'all', text: '全球' },
                { id: 'popular', text: '热门地区', children: popularRegions.map(item => { return { id: item, text: item } }) }, { id: 'notPopular', text: '其他地区', children: notPopularRegions }];

        }
        else {
            newData = data.map((item, index) => {
                const details = _.uniqBy(item.details, 'nameForRanking').filter(d=> d.ranking_by);
                return {
                    id: item.id,
                    text: item.name,
                    children: details.map(
                        (d) => {
                            return {
                                text: (d.nameForRanking || d.title) + ' - ' + d.ranking_by,
                                id: d.id
                            }
                        })
                }
            });
            newData.unshift({ id: 'all', text: '综合排名' });
        }
        return newData;
    }

    function reOrganizeData(data) {
        let newData = [...data];
        newData = newData.filter((item) => !isNaN(parseInt(item.rank)));
        newData = newData.sort((a, b) => parseInt(a.rank) - parseInt(b.rank));
        return newData;
    }

    function updatePagination(data) {
        pageSize = Math.round(data.length / 6);
        currentPage = 1;
    }

    function validateLogoImage() {
        rankingList.forEach((item) => {
            $('#schoolLogo' + (item.rankingId || item.id)).on('load', function (e) {

            }).on('error', function (e) {
                $('#schoolLogo' + (item.rankingId || item.id)).attr('src', `${prefix}/asset/image/logo/placeholderImg.png`);
            });
        })
    }

    function fetchRankingData() {
        $('#rankingTableBody').html(loadingData);
        $.get(`${prefix}/public/api/ranking.php`).done(function (data) {
            if (!data) {
                $('#rankingTableBody').html(noData);
                return;
            }
            const result = JSON.parse(data);
            const { rankings, overall } = result;
            if (!Array.isArray(rankings) || !Array.isArray(overall)) {
                $('#rankingTableBody').html(noData);
                return;
            }
            rankingData = [...rankings];
            overallData = [...overall];
            rankingList = reOrganizeData([...overall]);
            updatePagination(rankingList);
            $('#regionFilter').select2({ data: convertFilterData(rankingList, 'country') });
            $('#rankingTableBody').html(renderRankingTable(rankingList));
            validateLogoImage();
            //render school searcher
            const autoCompleteJS = new autoComplete({
                selector: "#autoComplete",
                placeHolder: "输入感兴趣的学校，查看该学校各专业的排名",
                data: {
                    src: overallData.map((item) => item.university),
                    cache: true,
                },
                resultItem: {
                    highlight: true
                },
                events: {
                    input: {
                        selection: (event) => {
                            const selection = event.detail.selection.value;
                            autoCompleteJS.input.value = selection;
                            const foundSchool = overallData.find(item => item.university === selection);
                            if (!foundSchool) {
                                return;
                            }
                            const schoolCard = `<div class="col-12 pt-3 pl-0 pr-0" id="schoolCard">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="row ml-2">
                                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                                            <img id="foundSchoolLogo" style="width: 80px;height: 80px" src="${prefix}/asset/image/logo/${foundSchool.logoPath}" />
                                        </div>
                                        <div class="col-xl-10 col-lg-10 col-md-9 col-sm-8">
                                            <p class="card-title">${foundSchool.university}</p>
                                            <p>综合排名: ${foundSchool.rank} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 国家地区: ${foundSchool.country}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-12 program-list">
                                    <table class="table table-borderless">
                                        <thead class="bg-light rounded">
                                            <tr>
                                                <th scope="col">专业</th>
                                                <th scope="col">排名</th>
                                            </tr>
                                        </thead>
                                        <tbody id="schoolProgramRankingTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div> </div>`;
                            $("#schoolCard").html(schoolCard);
                            $('#foundSchoolLogo').on('load', function (e) {

                            }).on('error', function (e) {
                                $('#foundSchoolLogo').attr('src', `${prefix}/asset/image/logo/placeholderImg.png`);
                            });
                            const foundSchoolProgramRankings = rankingData.filter(item => item.university === foundSchool.university);
                            $("#schoolProgramRankingTableBody").html(renderSchoolProgramRankingTable(foundSchoolProgramRankings));
                        },
                        keyup: (event) => {
                            if (!event.target.value) {
                                $("#schoolCard").html("");
                            }
                        }
                    }
                }
            });
        });
    }

    function fetchPrograms() {
        $.get(`${prefix}/public/api/program.php?type=all`).done(function (data) {
            if (data) {
                const result = JSON.parse(data);
                if (!Array.isArray(result)) {
                    return;
                }
                $('#programFilter').select2({ data: convertFilterData(result, 'program') });
            }
        });
    }

    //region filter on click event
    $('#regionFilter').on('change', function () {
        const value = $(this).val();
        currentCountry = value;
        if (currentProgram === 'all') {
            rankingList = reOrganizeData([...overallData].filter(
                item => item.country === (value !== 'all' ? value : item.country)));
        }
        else {
            rankingList = reOrganizeData([...rankingData].filter(
                (item) => item.country === value && item.pId === parseInt(currentProgram)
            ));
        }
        $('#rankingTableBody').html(renderRankingTable(rankingList));
    });

    //program filter on click event
    $('#programFilter').on('change', function () {
        const value = $(this).val();
        currentProgram = value;
        if (value === 'all') {
            rankingList = reOrganizeData(
                [...overallData].filter(
                    item => item.country === (currentCountry !== 'all' ? currentCountry : item.country)
                ));
        }
        else {
            rankingList = reOrganizeData(
                [...rankingData].filter(
                    item => item.country === (currentCountry !== 'all' ? currentCountry : item.country) && item.pId === parseInt(value)
                ));
        }
        if (rankingList.length === 0) {
            $('#showMore').hide();
            $('#rankingTableBody').html(noData);
        }
        else {
            if ($('#showMore').css('dispaly') != 'block') {
                $('#showMore').show();
            }
            $('#rankingTableBody').html(renderRankingTable(rankingList));
        }
    });

    //show more
    $('#showMore').on('click', function () {
        if (currentPage === pageSize - 1) {
            $('#showMore').text('已经加载全部');
        }
        currentPage += 1;
        $('#rankingTableBody').html(renderRankingTable(rankingList));
        validateLogoImage();
        $('html, body').animate({
            scrollTop: $("#rankingTableBody").offset().top + $('#rankingTableBody').height() - 400
        }, 1000);
    });

    $('#backTopBtn').on('click', function () {
        const btnPosition = $(this).offset().top;
        $('html, body').animate({
            scrollTop: $("#rankingTableBody").offset().top - 220
        })
    });
});