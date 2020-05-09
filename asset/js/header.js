$(document).ready(function(){
    let programsDropDown = '';
    $.get('/suitntie/public/api/program.php?type=categories').done(function(data){
        if(data){
            const result = JSON.parse(data);
            console.log(result);
            if(Array.isArray(result) && result.length > 0){
                result.forEach(function(item){
                    programsDropDown += `<a class="dropdown-item" href="/suitntie/programs/explore.php?title=${item.name}">${item.name}</a>`;
                });
            }
            $('#programMenu').append(programsDropDown);
        }
    });

    // $('.navTrigger').click(function () {
    //     $(this).toggleClass('active');
    //     console.log("Clicked menu");
    //     $("#mainListDiv").toggleClass("show_list");
    //     $("#mainListDiv").fadeIn();
    // });
});