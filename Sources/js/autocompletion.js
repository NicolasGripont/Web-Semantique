
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

    $('#search_button').click(function() {
        //remonte le logo
        $('div#logo').removeClass('logoCenter');
        $('div#logo').addClass('logoHaut');
        
        //instancie la structure des onglet
        if(loadStructure ==false)
        {
            addStructureMenu();
            addStructureContentMenu();
            loadStructure =true;
        }


        console.log($('#search_bar').val());
        //recupére les infos de la requête
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
                for (var i = 0; i < data.articles.length; i++) {
                    addContentArticles(data.articles[i].title, data.articles[i].url, data.articles[i].desc, data.articles[i].img, i);
                }
                for (var i = 0; i < data.social[0].twitter.length; i++) {
                    addContentSocialTwitter(data.social[0].twitter[i].created_at, data.social[0].twitter[i].text, data.social[0].twitter[i].username, data.social[0].twitter[i].username_photo_profil, i)
                }
            }
        });
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

//      #### Functions ####

//function de la structure du menu (onglet)
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

//ajout de la structure du content menu
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

    //title twitter menu4
    //div twitter
    var divContentMenu4= document.createElement('div');
    divContentMenu4.setAttribute('id','twitter');
    divContentMenu4.setAttribute('class', "row");
    divMenu4.appendChild(divContentMenu4);

    //Titre twitter
    var h2Title= document.createElement('h2');
    var h2Text = document.createTextNode('Twitter :');
    h2Title.appendChild(h2Text);
    divContentMenu4.appendChild(h2Title);

    //div content
    var divRowTwitt= document.createElement('div');
    divRowTwitt.setAttribute('id','twitter-content');
    divRowTwitt.setAttribute('class', "row");
    divContentMenu4.appendChild(divRowTwitt);


    divContent.appendChild(divMenu1);
    divContent.appendChild(divMenu2);
    divContent.appendChild(divMenu3);
    divContent.appendChild(divMenu4);
    divContent.appendChild(divSubMenu1);
    divContent.appendChild(divSubMenu2);



    $( "div#container" ).append(divContent);
}

//ajout le contenu des articles 
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
    
    
    //ajoute la div du texte
    var divArticleContent= document.createElement('div');
    divArticleContent.setAttribute('id', ('divArticleContent'+numeroArticles));
    divArticleContent.setAttribute('class', "col-md-9");
    divContent.appendChild(divArticleContent);
    
    //on ajoute le titre
    var h3Title= document.createElement('h3');
    divArticleContent.appendChild(h3Title);
    var aTitle = document.createElement('a');
    aTitle.setAttribute( 'href' , lien);
    var aText = document.createTextNode(titre);
    h3Title.appendChild(aTitle);
    aTitle.appendChild(aText);

    //ajout de la description

    var h3Description = document.createElement("h3");
    divArticleContent.appendChild(h3Description);
    var h3Text = document.createTextNode(description);
    h3Description.appendChild(h3Text);

}

function addContentSocialTwitter(created_at, text, username, username_photo_profil, numeroTwitt)
{

    var twitterContent = document.getElementById('twitter-content');

    //div du twitt
    var divContent= document.createElement('div');
    divContent.setAttribute('id', ('twitt'+numeroTwitt));
    divContent.setAttribute('class', "col-md-4");
    divContent.setAttribute('margin', "auto");
    twitterContent.appendChild(divContent);

    //la div qui contiendra l'image
    var divImgTwitt= document.createElement('div');
    divImgTwitt.setAttribute('id', ('imagetwitt'+numeroTwitt));
    divImgTwitt.setAttribute('class', "col-md-3");
    divContent.appendChild(divImgTwitt);

    //ajout de l'image
    var img= document.createElement('img');
    img.setAttribute('id', ('im'+numeroTwitt));
    img.setAttribute('class', 'imageTwitt');
    img.setAttribute('src', username_photo_profil);
    divImgTwitt.appendChild(img);  


    //ajoute la div des infos du tweet
    var divTwittContent= document.createElement('div');
    divTwittContent.setAttribute('id', ('divTwittContent'+numeroTwitt));
    divTwittContent.setAttribute('class', "col-md-9");
    divContent.appendChild(divTwittContent);
    
    //on ajoute le username
    var h3Title= document.createElement('h3');
    divTwittContent.appendChild(h3Title);
    var h3Text = document.createTextNode(username);
    h3Title.appendChild(h3Text);

    //on ajoute la date
    var pDate= document.createElement('p');
    divTwittContent.appendChild(pDate);
    var pText = document.createTextNode(created_at);
    pDate.appendChild(pText);

    //ajout de la description du twitt
    var pDescription = document.createElement("p");
    divTwittContent.appendChild(pDescription);
    var pText = document.createTextNode(text);
    pDescription.appendChild(pText);

}