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
        //remonte le logo
        $('div#logo').removeClass('logoCenter');
        $('div#logo').addClass('logoHaut');
        
        //instancie la structure des onglet
        if(loadStructure ==false)
        {
            addStructureMenu();
            addStructureContentMenu();
            //saddStructurePage2();
            loadStructure =true;
        }


        console.log($('#search_bar').val());
        //recupére text premier onglet
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
                 for (var i = 0; i < 10; i++) {
                    console.log(data.links[i]);
                    addContentArticles(data.links[i].title, data.links[i].url, data.links[i].desc, data.links[i].img, i);
                }
            }
        });
        //addContentPage();
        addContentPage2();
        addContentPage2();
        addContentPage2();
        addContentPage2();
        addContentPage3();
        addContentPage3();
       // addContentArticles("Château de Fontcreuse Magnum : Cassis - Blanc 2015", "http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html", "This Cassis Fontcreuse White wine 2015 has a very …kes a nervous wine, well balanced and declicious.", "http://www.infinivin.com/2999-large/chateau-de-fontcreuse-magnum-cassis-blanc-2015.jpg", 1);
        //addContentArticles("Château de Fontcreuse Magnum : Cassis - Blanc 2015", "http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html", "This Cassis Fontcreuse White wine 2015 has a very …kes a nervous wine, well balanced and declicious.", "http://www.infinivin.com/2999-large/chateau-de-fontcreuse-magnum-cassis-blanc-2015.jpg", 1);
        //addContentArticles("Château de Fontcreuse Magnum : Cassis - Blanc 2015", "http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html", "This Cassis Fontcreuse White wine 2015 has a very …kes a nervous wine, well balanced and declicious.", "http://www.infinivin.com/2999-large/chateau-de-fontcreuse-magnum-cassis-blanc-2015.jpg", 1);

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

function addStructurePage2() 
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

function addStructureMenu() 
{


        //ul nav 
        var ulNav = document.createElement("ul");
        ulNav.setAttribute('id', "myTab");
        ulNav.setAttribute('class', "nav nav-tabs");

        //li **** 1er Menu **** 
        var liNav1 = document.createElement("li");
        liNav1.setAttribute('class', "active");

        //a
        var liLink1 = document.createElement('a');
        liLink1.setAttribute( 'href' , "#menu1");
        liLink1.setAttribute( 'data-toggle' , "tab");
        var liLink1Text = document.createTextNode("Descriptif");
        liLink1.appendChild(liLink1Text);

        liNav1.appendChild(liLink1);
        ulNav.appendChild(liNav1);

        //li **** 2er Menu **** 
        var liNav2 = document.createElement("li");

        //a
        var liLink2 = document.createElement('a');
        liLink2.setAttribute( 'href' , "#menu2");
        liLink2.setAttribute( 'data-toggle' , "tab");
        var liLink2Text = document.createTextNode("Articles");
        liLink2.appendChild(liLink2Text);

        liNav2.appendChild(liLink2);
        ulNav.appendChild(liNav2);

        //li **** 3eme Menu **** 
        var liNav3 = document.createElement("li");

        //a
        var liLink3 = document.createElement('a');
        liLink3.setAttribute( 'href' , "#menu3");
        liLink3.setAttribute( 'data-toggle' , "tab");
        var liLink3Text = document.createTextNode("Recette");
        liLink3.appendChild(liLink3Text);

        liNav3.appendChild(liLink3);
        ulNav.appendChild(liNav3);

        //li **** 4eme Menu **** 
        var liNav4 = document.createElement("li");

        //a
        var liLink4 = document.createElement('a');
        liLink4.setAttribute( 'href' , "#menu4");
        liLink4.setAttribute( 'data-toggle' , "tab");
        var liLink4Text = document.createTextNode("Réseau Sociaux");
        liLink4.appendChild(liLink4Text);

        liNav4.appendChild(liLink4);
        ulNav.appendChild(liNav4);

       //li **** 5eme Menu **** 
        var liNav5 = document.createElement("li");
        liNav5.setAttribute('class', "dropdown");

        //a
        var liLink5 = document.createElement('a');
        liLink5.setAttribute( 'id' , "myTabDrop1");
        liLink5.setAttribute( 'class' , "dropdown-toggle");
        liLink5.setAttribute( 'data-toggle' , "dropdown");
        liLink5.setAttribute( 'href' , "#");

        var liLink5Text = document.createTextNode("Autres Recherches");
        liLink5.appendChild(liLink5Text);

        var bCarret = document.createElement('b');
        bCarret.setAttribute( 'class' , "caret");
        liLink5.appendChild(bCarret);

        // ulSubMenu
        var ulSubMenu = document.createElement("ul");
        ulSubMenu.setAttribute('aria-labelledby', "myTabDrop1")
        ulSubMenu.setAttribute('role', "menu")
        ulSubMenu.setAttribute('class', "dropdown-menu")

        //SubMenu 1
        var liSubMenu1 = document.createElement("li");

        var liSubMenuLink1 = document.createElement('a');
        liSubMenuLink1.setAttribute( 'data-toggle' , "tab");
        liSubMenuLink1.setAttribute( 'href' , "#dropdown1");

        var liSubMenuLink1Text = document.createTextNode("Option 1");
        liSubMenuLink1.appendChild(liSubMenuLink1Text);

        liSubMenu1.appendChild(liSubMenuLink1);
        ulSubMenu.appendChild(liSubMenu1);

        //SubMenu 1
        var liSubMenu2 = document.createElement("li");

        var liSubMenuLink2 = document.createElement('a');
        liSubMenuLink2.setAttribute( 'data-toggle' , "tab");
        liSubMenuLink2.setAttribute( 'href' , "#dropdown2");

        var liSubMenuLink2Text = document.createTextNode("Option 2");
        liSubMenuLink2.appendChild(liSubMenuLink2Text);

        liSubMenu2.appendChild(liSubMenuLink2);
        ulSubMenu.appendChild(liSubMenu2);

        liNav5.appendChild(ulSubMenu);



        liNav5.appendChild(liLink5);
        ulNav.appendChild(liNav5);


    $( "div#container").append(ulNav);
return;
}

function addStructureContentMenu()
{
    //div content 
    var divContent = document.createElement("div");
    divContent.setAttribute('id', "myTabContent");
    divContent.setAttribute('class', "tab-content");

    //div menu1 
    var divMenu1 = document.createElement("div");
    divMenu1.setAttribute('id', "menu1");
    divMenu1.setAttribute('class', "tab-pane fade active in");

    //div menu2 
    var divMenu2 = document.createElement("div");
    divMenu2.setAttribute('id', "menu2");
    divMenu2.setAttribute('class', "tab-pane fade");

    //div menu3 
    var divMenu3 = document.createElement("div");
    divMenu3.setAttribute('id', "menu3");
    divMenu3.setAttribute('class', "tab-pane fade");

    //div menu4 
    var divMenu4 = document.createElement("div");
    divMenu4.setAttribute('id', "menu4");
    divMenu4.setAttribute('class', "tab-pane fade");

    //div subMenu1 
    var divSubMenu1 = document.createElement("div");
    divSubMenu1.setAttribute('id', "dropdown1");
    divSubMenu1.setAttribute('class', "tab-pane fade");

    //div subMenu2 
    var divSubMenu2 = document.createElement("div");
    divSubMenu2.setAttribute('id', "dropdown2");
    divSubMenu2.setAttribute('class', "tab-pane fade");

    divContent.appendChild(divMenu1);
    divContent.appendChild(divMenu2);
    divContent.appendChild(divMenu3);
    divContent.appendChild(divMenu4);
    divContent.appendChild(divSubMenu1);
    divContent.appendChild(divSubMenu2);


    $( "div#container" ).append(divContent);
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

function addContentPage2()
{

    $htmlContent = "";
   //$htmlContent += '<div class="tab-content" id="myTabContent">';
    $htmlContent += '      <div>';// id="menu3" class="tab-pane fade active in">';
    $htmlContent += '        <p><br>Texte Accueil bis</p>';
    $htmlContent += '      </div>';
    /*$htmlContent += '      <div id="menu4" class="tab-pane fade">';
    $htmlContent += '        <p><br>Texte Messages bis</p>';
    $htmlContent += '      </div>';
    $htmlContent += '      <div id="dropdown3" class="tab-pane fade">';
    $htmlContent += '        <p><br>Texte 1 bis</p>';
    $htmlContent += '      </div>';
    $htmlContent += '      <div id="dropdown4" class="tab-pane fade">';
    $htmlContent += '        <p><br>Texte 2 bis</p>';
    $htmlContent += '      </div>';
    //$htmlContent += '    </div>';
*/
    $( "div#menu1" ).append($htmlContent);

}

function addContentPage3()
{

    $htmlContent = "";
    $htmlContent += '<div class="container top-buffer" id="article1" margin="auto">';
    $htmlContent += '      <div id="imgArticle1" class="col-md-3">';
    $htmlContent += '        <img id="imgSource1" class="imageResize" src="http://www.infinivin.com/2999-large/chateau-de-fontcreuse-magnum-cassis-blanc-2015.jpg"></img>';
    $htmlContent += '      </div>';

    $htmlContent += '      <div id="ContentArticle" class="col-md-9">';
    $htmlContent += '        <h3><a href="https://developer.mozilla.org"><br>Title Articles</a></h3>';
    $htmlContent += '        <h2>Description articles</h2>';
    $htmlContent += '      </div>';
    $htmlContent += '    </div>';

    $( "div#menu3" ).append($htmlContent);

}

function addContentArticles(titre, lien, description, srcImage, numeroArticles)
{
    var menu2 = document.getElementById('menu2');

    //div de article
    var divContent= document.createElement('div');
    divContent.setAttribute('id', ('article'+numeroArticles));
    divContent.setAttribute('class', "container top-buffer");
    divContent.setAttribute('margin', "auto");
    menu2.appendChild(divContent);
    
    //la div qui contiendra l'image
    var divImgArt= document.createElement('div');
    divImgArt.setAttribute('id', ('imgArticle'+numeroArticles));
    divImgArt.setAttribute('class', "col-md-3");
    divContent.appendChild(divImgArt);

    //ajout de l'image
    var img= document.createElement('img');
    img.setAttribute('id', ('im'+numeroArticles));
    img.setAttribute('class', 'imageResize');
    img.setAttribute('src', srcImage);
    divImgArt.appendChild(img);  
    
    
    //ajoute la div du tetxte
    var divArticleContent= document.createElement('div');
    divArticleContent.setAttribute('id', ('divArticleContent'+numeroArticles));
    divArticleContent.setAttribute('class', "col-md-9");
    divContent.appendChild(divArticleContent);
    
    //on ajoute le titre
    var h3Title= document.createElement('h3');
    divContent.appendChild(h3Title);
    var aTitle = document.createElement('a');
    aTitle.setAttribute( 'href' , lien);
    var aText = document.createTextNode(titre);
    h3Title.appendChild(aTitle);
    aTitle.appendChild(aText);

    //ajout de la description

    var h3Description = document.createElement("h3");
    divContent.appendChild(h3Description);
    var h3Text = document.createTextNode(description);
    h3Description.appendChild(h3Text);


}