$(document).ready(function(){
    $('#test').click(function(){
        $.ajax({
            url: 'http://localhost/uus//',
            type: "format=post",
            data: "json",
            success: function(response){
                alert(response);
            }
        });
    });
});