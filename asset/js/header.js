$(document).ready(function () {
    let programsDropDown = '';
    $.get('/suitntie/public/api/program.php?type=all').done(function (data) {
        if (data) {
            const result = JSON.parse(data);
            console.log(result)
            if (Array.isArray(result) && result.length > 0) {
                result.forEach(function (item) {
                    programsDropDown += `<li class="dropdown-submenu dropdown-item"><a class="program-category dropdown-submenu-toggle"tabindex="-1" href="#">${item.name} 
                        <i class="fas fa-caret-right pl-1" style="color: #c7c7c7;"></i></a><ul class="dropdown-menu">`;
                    if (item.details && Array.isArray(item.details) && item.details.length > 0) {
                        programsDropDown += `<li>`;
                        item.details.forEach(function (item) {
                            programsDropDown += `<a class="dropdown-item" href="/suitntie/programs/explore.php?title=${item.title}">${item.title}</a>`;
                        });
                        programsDropDown += `</li>`;
                    }
                    programsDropDown += `</ul></li>`;
                });
            }
            $('#programMenu').append(programsDropDown);
        }
    });

    // Make Dropdown Submenus possible
	$('.dropdown-submenu a.dropdown-submenu-toggle').on("click", function(e){
		$('.dropdown-submenu ul').removeAttr('style');
		$(this).next('ul').toggle();
		e.stopPropagation();
		e.preventDefault();
	});
	
	// Clear secondary dropdowns on.Hidden
	$('#bs-navbar-collapse-1').on('hidden.bs.dropdown', function () {
  		$('.navbar-nav .dropdown-submenu ul.dropdown-menu').removeAttr('style');
	});
});