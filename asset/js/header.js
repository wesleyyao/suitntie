$(document).ready(function () {
    let programsDropDown = '';
    $.get('/suitntie/public/api/program.php?type=all').done(function (data) {
        if (data) {
            const result = JSON.parse(data);
            console.log(result)
            if (Array.isArray(result) && result.length > 0) {
                result.forEach(function (item) {
                    programsDropDown += `
                        <li class="dropdown-submenu">
                            <a class="program-category dropdown-submenu-toggle" id="category${item.id}" tabindex="-1" href="#/">${item.name} 
                            <i class="fas fa-caret-right pl-1" style="color: #c7c7c7;"></i>
                            </a>
                        <ul class="level-3-dropdown">`;
                    if (item.details && Array.isArray(item.details) && item.details.length > 0) {
                        item.details.forEach(function (item) {
                            programsDropDown += `<li><a href="/suitntie/programs/explore.php?title=${item.title}">${item.title}</a></li>`;
                        });
                    }
                    programsDropDown += `</ul></li>`;
                });
            }
            $('#programMenu').append(programsDropDown);
        }
    });

    $(document).on('click', '.program-category', function(e){
        e.stopPropagation();
        $(this).next('.level-3-dropdown').toggle();
    });
});