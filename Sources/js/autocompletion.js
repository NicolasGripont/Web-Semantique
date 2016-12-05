var listNameOfWine = new Array();

$(document).ready(function(){
    //automplete
    $('#search_bar').autocomplete({
        source : function(request, response) {
        var results = $.ui.autocomplete.filter(listNameOfWine, request.term);

        response(results.slice(0, 10));
    }
    });

    $('#search_bar').keyup(function(){

    });

    $('#search_button').click(function() {
        $('div#logo').removeClass('logoCenter');
        $('div#logo').addClass('logoHaut');

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