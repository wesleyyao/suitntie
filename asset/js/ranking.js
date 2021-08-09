import { formValidation, prefix, submittingMessage, generateMessage, fetchAccount, windowSize } from './common.js';

$(document).ready(function () {
    const noData = `
    <tr>
    <td></td>
    <td>
    <div class="alert alert-light" role="alert">
        未找到任何导师数据。
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
        for(let i = 0; i < data.length; i++){
            if(i >= (currentPage * NUMBER_PER_PAGE)){
                break;
            }
            tableBody += `<tr>
            <td>${data[i].rank}</td>
            <td><div class="d-inline-flex" style="vertical-align: middle">
            <img class="ranking-school-logo-sm" src="${prefix}/asset/image/logo/${data[i].logoPath}" alt="${data[i].university}Logo" />
            <label class="pt-3">${data[i].university}</label>
            </div></td>
            <td>${data[i].country}</td></tr>`;
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
            newData = newData.map((item, index) => { return { id: item, text: item, rIndex: popularRegions.includes(item) ? 0 : index + 4 } });
            newData = newData.sort((a, b) => a.rIndex - b.rIndex);
            newData.unshift({ id: 'all', text: '全球' });
        }
        else {
            newData = data.map((item, index) => {
                return {
                    id: item.id,
                    text: item.name,
                    children: item.details.map(
                        (d) => {
                            return {
                                text: d.title,
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

    function updatePagination(data){
        pageSize = Math.round(data.length / 6);
        currentPage = 1;
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
                            const schoolCard = `<div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                                            <img style="width: 80px;height: 80px" src="${prefix}/asset/image/logo/${foundSchool.logoPath}" />
                                        </div>
                                        <div class="col-xl-10 col-lg-10 col-md-9 col-sm-8">
                                            <p class="card-title">${foundSchool.university}</p>
                                            <p>综合排名: ${foundSchool.rank} | 国家地区: ${foundSchool.country}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                            $("#schoolCard").html(schoolCard);

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
                (item) => item.country === value && item.p_id === parseInt(currentProgram)
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
                    item => item.country === (currentCountry !== 'all' ? currentCountry : item.country) && item.p_id === parseInt(value)
                ));

        }
        $('#rankingTableBody').html(renderRankingTable(rankingList));
    });

    //show more
    $('#showMore').on('click', function(){
        if(currentPage === pageSize - 1){
            $('#showMore').text('已经加载全部');
        }
        currentPage += 1;
        $('#rankingTableBody').html(renderRankingTable(rankingList));
        $('html, body').animate({
            scrollTop: $("#rankingTableBody").offset().top + $('#rankingTableBody').height() - 400
        }, 1000);
    });
});