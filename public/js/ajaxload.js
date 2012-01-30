function getList(type){
    // $('#test').load('./index/list/format/html');
    if(type == 'busline'){
        listOfBuslines();
    } else if(type == 'busstop'){
        listOfBusstops();
    }

}

function listOfBuslines(){
    $.ajax({
        type: "POST",
        url: "./index/list/format/html",
        data: "list=busline",
        cache: false,
        dataType: "json",
        error: function(jqXHR,textStatus, thrownError){
            alert("Error: " + thrownError);
        },
        success: function(data){
            for(var i=0; i<data.length;i++){
                $('#buslines').append("<u>" + data[i].name + "</u> ");
            }
        }
    });
    
}
function listOfBusstops(){
    $.ajax({
        type: "POST",
        url: "./index/list/format/html",
        data: "list=busstop",
        cache: false,
        dataType: "json",
        error: function(jqXHR,textStatus, thrownError){
            alert("Error: " + thrownError);
        },
        success: function(data){
            for(var i=0; i<data.length;i++){
                $('#busstops').append("<u>" + data[i].name + "</u> ");
            }
        }
    });
}