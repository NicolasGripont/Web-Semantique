$(document).ready(function(){
    $( "#search_bar" ).keypress(function( event ) {
        if ( event.which == 13 ) {
            $('#search_button').trigger("click");
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

        var opts = {
            lines : 10,
            color: '#FFFFFF' // #rgb or #rrggbb or array of colors
        }
        var target = document.getElementById('container');
        var spinner = new Spinner(opts).spin(target);

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
                $( ".spinner" ).remove();
				$('div[id^=menu]').empty();
				var divRowTwitt= document.createElement('div');
				divRowTwitt.setAttribute('id','twitter-content');
				divRowTwitt.setAttribute('class', "row");
				$('#menu5').append(divRowTwitt);

				for (var key in data.dbpedia_desc) {
						if( data.dbpedia_desc[key][0] !== undefined && data.dbpedia_desc[key][0].label !== undefined &&
								data.dbpedia_desc[key][1] !== undefined && data.dbpedia_desc[key][1].text !== undefined &&
								data.dbpedia_desc[key][2] !== undefined && data.dbpedia_desc[key][2].photo !== undefined &&
								data.dbpedia_desc[key][3] !== undefined && data.dbpedia_desc[key][3].wiki !== undefined)
						addContentDescriptif(data.dbpedia_desc[key][0].label, data.dbpedia_desc[key][1].text, data.dbpedia_desc[key][2].photo, data.dbpedia_desc[key][3].wiki, i);
                }
                if(data.infinivin != null) {
                    for (var i = 0; i < data.infinivin.length; i++) {
                        if(data.infinivin[i].desc != "null")
                            addContentArticles(data.infinivin[i].label, data.infinivin[i].uri, data.infinivin[i].desc, data.infinivin[i].picture, i);
                        else
                            addContentArticles(data.infinivin[i].label, data.infinivin[i].uri, "Aucune description", data.infinivin[i].picture, i);
                    }
                }
                if(data.articles != null) {
                    for (var i = 0; i < data.articles.length; i++) {
                        addContentResultats(data.articles[i].title, data.articles[i].url, data.articles[i].desc, i);
                    }
                }
                if(data.social != null) {
                    if(data.social.twitter != null) {
                        for (var i = 0; i < data.social.twitter.length; i++) {
                            addContentSocialTwitter(data.social.twitter[i].created_at, data.social.twitter[i].text, data.social.twitter[i].username_name, data.social.twitter[i].username_photo_profil, i)
                        }
                    }
                }
                if(data.domain != null) {
                    for (var i = 0; i < data.domain.length; i++) {
                        addDomain(data.domain[i].domain, data.domain[i].domain_info, data.domain[i].domain_picture, i);
                    }
                }
            },
            error : function(request, status, error) {
                $( ".spinner" ).remove();
                alert(request.responseText);
            }
        });
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

    //li **** 6eme Menu **** 
    var liNav6 = document.createElement("li");

    //a
    var liLink6 = document.createElement('a');
    liLink6.setAttribute( 'href' , "#menu6");
    liLink6.setAttribute( 'data-toggle' , "tab");
    var liLink6Text = document.createTextNode("Domaine");
    liLink6.appendChild(liLink6Text);

    liNav6.appendChild(liLink6);
    ulNav.appendChild(liNav6);

    //li **** 3eme Menu **** 
    var liNav3 = document.createElement("li");

    //a
    var liLink3 = document.createElement('a');
    liLink3.setAttribute( 'href' , "#menu3");
    liLink3.setAttribute( 'data-toggle' , "tab");
    var liLink3Text = document.createTextNode("Résultats");
    liLink3.appendChild(liLink3Text);

    liNav3.appendChild(liLink3);
    ulNav.appendChild(liNav3);

    //li **** 4eme Menu **** 
    /*var liNav4 = document.createElement("li");

    //a
    var liLink4 = document.createElement('a');
    liLink4.setAttribute( 'href' , "#menu4");
    liLink4.setAttribute( 'data-toggle' , "tab");
    var liLink4Text = document.createTextNode("Recettes");
    liLink4.appendChild(liLink4Text);

    liNav4.appendChild(liLink4);
    ulNav.appendChild(liNav4);*/

    //li **** 5eme Menu **** 
    var liNav5 = document.createElement("li");

    //a
    var liLink5 = document.createElement('a');
    liLink5.setAttribute( 'href' , "#menu5");
    liLink5.setAttribute( 'data-toggle' , "tab");
    var imgTwitter = document.createElement('img');
    imgTwitter.setAttribute('src','pictures/twitter_logo.png');
    imgTwitter.setAttribute('id', "twitterLogo");
    liLink5.appendChild(imgTwitter);

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

    //div menu5 
    var divMenu5 = document.createElement("div");
    divMenu5.setAttribute('id', "menu5");
    divMenu5.setAttribute('class', "tab-pane fade");

    //div menu5 
    var divMenu6 = document.createElement("div");
    divMenu6.setAttribute('id', "menu6");
    divMenu6.setAttribute('class', "tab-pane fade");

    /*//div subMenu1 
    var divSubMenu1 = document.createElement("div");
    divSubMenu1.setAttribute('id', "dropdown1");
    divSubMenu1.setAttribute('class', "tab-pane fade");

    //div subMenu2 
    var divSubMenu2 = document.createElement("div");
    divSubMenu2.setAttribute('id', "dropdown2");
    divSubMenu2.setAttribute('class', "tab-pane fade");*/

    //title twitter menu4
    //div twitter
    var divContentMenu5= document.createElement('div');
    divContentMenu5.setAttribute('id','twitter');
    divContentMenu5.setAttribute('class', "row");
    divMenu5.appendChild(divContentMenu5);

    //div content
    var divRowTwitt= document.createElement('div');
    divRowTwitt.setAttribute('id','twitter-content');
    divRowTwitt.setAttribute('class', "row");
    divContentMenu5.appendChild(divRowTwitt);


    divContent.appendChild(divMenu1);
    divContent.appendChild(divMenu2);
    divContent.appendChild(divMenu6);
    divContent.appendChild(divMenu3);
    divContent.appendChild(divMenu4);
    divContent.appendChild(divMenu5);
    //divContent.appendChild(divSubMenu1);
    //divContent.appendChild(divSubMenu2);



    $( "div#container" ).append(divContent);
}


//ajout le contenu des articles 
function addContentDescriptif(titre, description, srcImage, srcWiki, numeroDesc)
{
    var menu1 = $('#menu1');
	
    //div de article
    var divContent= document.createElement('div');
    divContent.setAttribute('id', ('desc'+numeroDesc));
    divContent.setAttribute('class', "container top-buffer divContent");
    divContent.setAttribute('margin', "auto");
    menu1.append(divContent);
    
    //la div qui contiendra l'image
    var divImgArt= document.createElement('div');
    divImgArt.setAttribute('id', ('imgArticle'+numeroDesc));
    divImgArt.setAttribute('class', "col-md-3");
    divContent.appendChild(divImgArt);

    //ajout de l'image
    var img= document.createElement('img');
    img.setAttribute('id', ('im'+numeroDesc));
    img.setAttribute('class', 'imageDescriptif');
    img.setAttribute('src', srcImage);
    divImgArt.appendChild(img);  
    
    
    //ajoute la div du texte
    var divArticleContent= document.createElement('div');
    divArticleContent.setAttribute('id', ('divArticleContent'+numeroDesc));
    divArticleContent.setAttribute('class', "col-md-9");
    divContent.appendChild(divArticleContent);
    
    //on ajoute le titre
    var h3Title= document.createElement('h3');
    divArticleContent.appendChild(h3Title);
    var aTitle = document.createElement('a');
    aTitle.setAttribute( 'href' , srcWiki);
    var aText = document.createTextNode(titre);
    h3Title.appendChild(aTitle);
    aTitle.appendChild(aText);

    //ajout de la description

    var h3Description = document.createElement("p");
    divArticleContent.appendChild(h3Description);
    var h3Text = document.createTextNode(description);
    h3Description.appendChild(h3Text);

}

function addContentResultats(titre, lien, description, numeroResultat)
{
    var menu3 = document.getElementById('menu3');

    //div de article
    var divContent= document.createElement('div');
    divContent.setAttribute('id', ('resultat'+numeroResultat));
    divContent.setAttribute('class', "container top-buffer resultat divContent");
    divContent.setAttribute('margin', "auto");
    menu3.appendChild(divContent);
    
    //ajoute la div du texte
    var divResultatContent= document.createElement('div');
    divResultatContent.setAttribute('id', ('divResultatContent'+numeroResultat));
    divResultatContent.setAttribute('class', "col-md-9");
    divContent.appendChild(divResultatContent);
    
    //on ajoute le titre
    var h3Title= document.createElement('h3');
    divResultatContent.appendChild(h3Title);
    var aTitle = document.createElement('a');
    aTitle.setAttribute( 'href' , lien);
    var aText = document.createTextNode(titre);
    h3Title.appendChild(aTitle);
    aTitle.appendChild(aText);

    //ajout de la description

    var h3Description = document.createElement("p");
    divResultatContent.appendChild(h3Description);
    var h3Text = document.createTextNode(description);
    h3Description.appendChild(h3Text);
}

//ajout le contenu des articles 
function addContentArticles(titre, lien, description, srcImage, numeroArticles)
{
    var menu2 = document.getElementById('menu2');

    //div de article
    var divContent= document.createElement('div');
    divContent.setAttribute('id', ('article'+numeroArticles));
    divContent.setAttribute('class', "container top-buffer divContent");
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

    var h3Description = document.createElement("p");
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
    divContent.setAttribute('class', "tweet");
    divContent.setAttribute('margin', "auto");
    twitterContent.appendChild(divContent);

    //la div qui contiendra l'image
    var divImgTwitt= document.createElement('div');
    divImgTwitt.setAttribute('id', ('imagetwitt'+numeroTwitt));
    divImgTwitt.setAttribute('class', "tweetImage");
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
    divTwittContent.setAttribute('class', "tweetInfo");
    divContent.appendChild(divTwittContent);
    
    //on ajoute le username
    var h3Title= document.createElement('h3');
    divTwittContent.appendChild(h3Title);
    var h3Text = document.createTextNode(username);
    h3Title.appendChild(h3Text);

    //on ajoute la date
    var pDate= document.createElement('p');
    divTwittContent.appendChild(pDate);
    var arr = created_at.split(" ");
    var pText = document.createTextNode(arr[0] + " " + arr[1] + " " + arr[2] + " " + arr[3]);
    pDate.appendChild(pText);

    //ajout de la description du twitt
    var pDescription = document.createElement("p");
    divTwittContent.appendChild(pDescription);
    var pText = document.createTextNode(text);
    pDescription.appendChild(pText);

}

function addDomain(name, info, picture, numeroDomain) {
    var menu6 = document.getElementById('menu6');

    //div de article
    var domainContent= document.createElement('div');
    domainContent.setAttribute('id', ('domain'+numeroDomain));
    domainContent.setAttribute('class', "container top-buffer divContent");
    domainContent.setAttribute('margin', "auto");
    menu6.appendChild(divContent);

    //la div qui contiendra l'image
    var divImgDomain= document.createElement('div');
    divImgDomain.setAttribute('class', 'divImgDomain');
    domainContent.appendChild(divImgDomain);

    //ajout de l'image
    var img= document.createElement('img');
    img.setAttribute('class', 'imageDomain');
    img.setAttribute('src', picture);
    divImgDomain.appendChild(img);  


    //ajoute la div des infos du domaine
    var divDomainContent= document.createElement('div');
    divDomainContent.setAttribute('class', 'divDomainContent');
    domainContent.appendChild(divDomainContent);
    
    //on ajoute le titre
    var h3Title= document.createElement('h3');
    divDomainContent.appendChild(h3Title);
    var h3Text = document.createTextNode(name);
    h3Title.appendChild(h3Text);

    //ajout de la description du domain
    var pDescription = document.createElement("p");
    divDomainContent.appendChild(pDescription);
    var pText = document.createTextNode(info);
    pDescription.appendChild(pText);
}


