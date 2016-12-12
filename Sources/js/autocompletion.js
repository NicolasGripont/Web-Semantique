var listNameOfWine = new Array();
var loadStructure = false;

$(document).ready(function(){
    //automplete
    $('#search_bar').autocomplete({
        source : function(request, response) {
        var results = $.ui.autocomplete.filter(listNameOfWine, request.term);

        response(results.slice(0, 10));
    }
    });

    $('#search_button').click(function() {
        $('div#logo').removeClass('logoCenter');
        $('div#logo').addClass('logoHaut');

        console.log($('#search_bar').val());
         $.ajax({
            type: 'GET',
            url: 'controler.php?',
            data: { 
                page:"search",
                request: $('#search_bar').val()
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
            }
        });
        if(loadStructure ==false)
        {
            addStructurePage();
            loadStructure =true;
        }
        addContentPage();
    });

    //recover list name of wine
    $.ajax({
        type: 'POST',
        url: 'controler.php?page=wine',
        dataType: "json",
        success: function(data) {
            console.log(data);
            for (var i = 0; i < data.length; i++) {
                listNameOfWine.push(data[i]._name);
            }
        }

    });
});

function addStructurePage() 
{
    $htmlStructure ="";
    $htmlStructure += '<ul class="nav nav-tabs" id="myTab">';
    $htmlStructure += '  <li class="active"><a data-toggle="tab" href="#menu1">Descriptif</a></li>';
    $htmlStructure += '  <li><a data-toggle="tab" href="#menu2">Articles</a></li>';
    $htmlStructure += '  <li><a data-toggle="tab" href="#menu3">Recette</a></li>';
    $htmlStructure += '  <li><a data-toggle="tab" href="#menu4">Reseaux Sociaux</a></li>';
    $htmlStructure += '  <li class="dropdown">';
    $htmlStructure += '    <a data-toggle="dropdown" class="dropdown-toggle" id="myTabDrop1" href="#">';
    $htmlStructure += '          Liste <b class="caret"></b>';
    $htmlStructure += '    </a>';
    $htmlStructure += '    <ul aria-labelledby="myTabDrop1" role="menu" class="dropdown-menu">';
    $htmlStructure += '      <li><a data-toggle="tab" href="#dropdown1">Texte 1</a></li>';
    $htmlStructure += '      <li><a data-toggle="tab" href="#dropdown2">Texte 2</a></li>';
    $htmlStructure += '    </ul>';
    $htmlStructure += '  </li>';
    $htmlStructure += '</ul>';

    $( "div#container" ).append($htmlStructure);
return;
}

function addContentPage(){
    $htmlContent = "";
    $htmlContent += '<div class="tab-content" id="myTabContent">';
    $htmlContent += '      <div id="menu1" class="tab-pane fade active in">';
    $htmlContent += '        <p><br>Texte Accueil</p>';
    $htmlContent += '      </div>';
    $htmlContent += '      <div id="menu2" class="tab-pane fade">';
    $htmlContent += '        <p><br>Texte Messages</p>';
    $htmlContent += '      </div>';
    $htmlContent += '      <div id="dropdown1" class="tab-pane fade">';
    $htmlContent += '        <p><br>Texte 1</p>';
    $htmlContent += '      </div>';
    $htmlContent += '      <div id="dropdown2" class="tab-pane fade">';
    $htmlContent += '        <p><br>Texte 2</p>';
    $htmlContent += '      </div>';
    $htmlContent += '    </div>';

    $( "div#container" ).append($htmlContent);
return;
}