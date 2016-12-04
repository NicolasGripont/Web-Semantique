var listNameOfWine = new Array();

$(document).ready(function(){
    //automplete
    $('#search_bar').autocomplete({
    source : listNameOfWine
    });

    //recover list name of wine
    $.ajax({
        type: 'POST',
        url: 'php/controleurs/controleur_vin.php',
        dataType: "json",
        success: function(data) {
            console.log(data);
            for (var i = 0; i < data.length; i++) {
                listNameOfWine.push(data[i]._name);
            }
        }

    });
});