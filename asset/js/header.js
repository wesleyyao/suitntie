$(document).ready(function () {
    let programsDropDown = '';
    $.get('/suitntie/public/api/program.php?type=all').done(function (data) {
        if (data) {
            const result = JSON.parse(data);
            console.log(result)
            if (Array.isArray(result) && result.length > 0) {
                result.forEach(function (item) {
                    programsDropDown += `<div class="program-category-cell"><a class="program-category" href="#/">${item.name} 
                        <i class="fas fa-caret-right" style="position: absolute; top: 4px; right: 5px; color: #c7c7c7;"></i></a>`;
                    if (item.details && Array.isArray(item.details) && item.details.length > 0) {
                        programsDropDown += `<div class="second-level-dropdown">`;
                        item.details.forEach(function (item) {
                            programsDropDown += `<a class="dropdown-item" href="/suitntie/programs/explore.php?title=${item.title}">${item.title}</a>`;
                        });
                        programsDropDown += `</div>`;
                    }
                    programsDropDown += `</div>`;
                });
            }
            $('#programMenu').append(programsDropDown);
        }
    });
});