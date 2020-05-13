$(document).ready(function(){
    $.get('./api/program.php').done(function(data){
        const result = JSON.parse(data);
        console.log(result);
    });
});