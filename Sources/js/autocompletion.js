
//Initialisation
var listNameOfWine = new Array();
var loadStructure = false;

//Au chargement de la page
$(document).ready(function(){
    //automplete
    $('#search_bar').autocomplete({
        source : function(request, response) {
        var results = $.ui.autocomplete.filter(listNameOfWine, request.term);

        response(results.slice(0, 10));
    }
    });

    //recover list name of wine
    $.ajax({
        type: 'POST',
        url: 'controler.php?page=wine',
        dataType: "json",
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                listNameOfWine.push(data[i]._name);
            }
        }

    });
});