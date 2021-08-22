import { prefix, showFloatMessage, useMessage, hideFloatMessage, submittingMessage } from '../../../asset/js/common.js';
import { auth, renderNav } from './global.js';

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

    fetchRankingData();

    function handleCallback(data) {
        if (data) {
            hideFloatMessage('#message');
            const result = JSON.parse(data);
            console.log(result);
            const { code, message } = result;
            if (code === 200) {
                showFloatMessage('#message', useMessage('success', message), 3000);
            }
            else {
                showFloatMessage('#message', useMessage('warning', message), 3000);
            }
        }
        else {
            showFloatMessage('#message', useMessage('warning', '无法处理。'), 3000);
        }
    }

    $(document).on('submit', '#rankingForm', function (e) {
        e.preventDefault();
        let formFields = $(this)[0];
        let formData = new FormData(formFields);
        showFloatMessage('#message', submittingMessage, 0);
        $.ajax({
            url: `${prefix}/manager/api/ranking.php`,
            type: 'POST',
            data: formData,
            success: function (data) {
                handleCallback(data);
            },
            error: function (e) {
                console.log("ERROR : ", e);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $(document).on('submit', '#rankingTitleForm', function (e) {
        e.preventDefault();
        let formFields = $(this)[0];
        let formData = new FormData(formFields);
        console.log(formData)
        showFloatMessage('#message', submittingMessage, 0);
        $.ajax({
            url: `${prefix}/manager/api/ranking-tracksheet.php`,
            type: 'POST',
            data: formData,
            success: function (data) {
                handleCallback(data);
            },
            error: function (e) {
                console.log("ERROR : ", e);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    function reOrganizeDataList(data, key) {
        let newDataList = [...data];
        newDataList = newDataList.filter((item) => !isNaN(parseInt(item[key])));
        newDataList = newDataList.sort((a, b) => parseInt(a[key]) - parseInt(b[key]));
        return newDataList;
    }

    function fetchRankingData() {
        auth().then(
            (result) => {
                if (result.status === 'success') {
                    $.get(`${prefix}/manager/api/ranking.php`).done(function (data) {
                        const result = JSON.parse(data);
                        console.log(result)
                        const { overallRankings, programRankings, rankingTitles } = result;
                        if (!Array.isArray(overallRankings)) {
                            $('#schoolRankingTableBody').html(noData);
                            return;
                        }
                        if (!Array.isArray(programRankings)) {
                            $('#programRankingTableBody').html(noData);
                            return;
                        }
                        if (!Array.isArray(rankingTitles)) {
                            $('#rankingTitlesTableBody').html(noData);
                            return;
                        }

                        //render program ranking table
                        let programRankingTableBody = '';
                        let localProgramRankingData = reOrganizeDataList(programRankings, 'rank');
                        localProgramRankingData.forEach((item, index) => {
                            programRankingTableBody += `<tr>
                            <td>${item.title}</td>
                            <td>${item.rank}</td>
                            <td><img id="schoolLogo${index}" src="${prefix}/asset/image/logo/${item.logoPath}" /></td>
                            <td>${item.university}</td>
                            <td>${item.country}</td>
                            </tr>`;
                        });
                        $('#programRankingTableBody').html(programRankingTableBody);
                        $('#programRankingTable').DataTable();
                        // render school ranking table
                        let schoolRankingTableBody = '';
                        let localSchoolRankingData = reOrganizeDataList(overallRankings, 'ranking');
                        localSchoolRankingData.forEach((item, index) => {
                            schoolRankingTableBody += `<tr>
                            <td>${item.ranking}</td>
                            <td><img src="${prefix}/asset/image/logo/${item.logoPath}" /></td>
                            <td>${item.name}</td>
                            <td>${item.region}</td>
                            </tr>`;
                        });
                        $('#schoolRankingTableBody').html(schoolRankingTableBody);
                        $('#schoolRankingTable').DataTable();
                        // render ranking program titles table
                        let rankingProgramTableBody = '';
                        rankingTitles.forEach((item, index) => {
                            rankingProgramTableBody += `<tr>
                            <td>${item.title}</td>
                            <td>${item.nameForRanking}</td>
                            <td>${item.ranking_by}</td>
                            </tr>`;
                        });
                        $('#rankingProgramTableBody').html(rankingProgramTableBody);
                        $('#rankingProgramTable').DataTable();
                    });
                }
            });
    }
});