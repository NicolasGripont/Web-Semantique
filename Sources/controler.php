<?php
//ini_set('display_errors',1);
require_once('services/custom_search.php');
require_once("services/service_wine.php");
require_once("services/connection.php");

$page = $_GET["page"];


if($page == "search") {
	$request = $_GET["request"];

	//echo json_encode($request);
	$json = '{"links" : [{"title" : "Château de Fontcreuse Magnum : Cassis - Blanc 2015", "url" : "http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html", "desc" : "This Cassis Fontcreuse White wine 2015 has a very beautiful robe with small golden reflections, clear and bright ! The nose is powerful, pleasant and delicate. Fruits notes are developping: grapefruit, bitter almond, golden apple, voluptuous attack in the mouth, vivid, full of flesh: all of it makes a nervous wine, well balanced and declicious.", "img" : "http://www.infinivin.com/2999-large/chateau-de-fontcreuse-magnum-cassis-blanc-2015.jpg"}, {"title" : "Domaine du Paternel : Cassis - White wine 2015", "url" : "http://www.infinivin.com/en/domaine-du-paternel-cassis-white-wine-2015-801.html", "desc" : "The nose of this, Domaine du Paternel 2015 is on acidulated aromas (grapefruit), marked by the thyme in finale. In mouth, we find this side white flower (already present in mouth), completed by the peach, with a touch of bitterness in finale. The length is very beautiful, the Domaine du Paternel stays in an air register. ", "img" : "http://www.infinivin.com/2896-large/domaine-du-paternel-cassis-white-wine-2015.jpg"}, {"title" : "Domaine du Paternel : Cassis - Rosé wine 2015", "url" : "http://www.infinivin.com/en/domaine-du-paternel-cassis-rose-wine-2015-796.html", "desc" : "This rosé of the Domaine du Paternel 2015 slightly salmon, in the attractive nose of citrus fruits and fruits days, has the frank, well-balanced mouth, everything in lightness and in liveliness.", "img" : "http://www.infinivin.com/2600-large/domaine-du-paternel-cassis-rose-wine-2015.jpg"}, {"title" : "Château de Fontcreuse : Cassis - White wine 2015", "url" : "http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2015-886.html", "desc" : "This white \'Cassis Fontcreuse\' 2014 possesses a very beautiful colour in the small golden reflections, crystal clear, brilliant! The nose is powerful, pleasant and fine. The fruity notes(marks) develop first and foremost; grapefruit, bitter almond, peach, followed by Golden Delicious apple, more honeyed than the green apple, the sensual attack in mouth, lives, full of flesh, all which gives a nervous, well balanced and delicious wine that this \'Cassis\' of the Domaine Fontcreuse!", "img" : "http://www.infinivin.com/1942-large/chateau-de-fontcreuse-cassis-white-wine-2015.jpg"}, {"title" : "Domaine du Paternel : Cassis - White wine 2013", "url" : "http://www.infinivin.com/en/domaine-du-paternel-cassis-blanc-2013-60.html", "desc" : "The nose of this, Domaine du Paternel 2013 is on acidulated aromas (grapefruit), marked by the thyme in finale. In mouth, we find this side white flower (already present in mouth), completed by the peach, with a touch of bitterness in finale. The length is very beautiful, the Domaine du Paternel stays in an air register. ", "img" : "http://www.infinivin.com/473-large/domaine-du-paternel-cassis-blanc-2013.jpg"}, {"title" : "Château de Fontcreuse : Cassis - Rosé wine 2015", "url" : "http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-rose-wine-2015-888.html", "desc" : "It is the delicate pastel of a vine peach which seduces the eye. The nose of Château de Fontcreuse Cassis 2015, it, shivers in the aromas of white flowers and peach. In mouth, this rosé reveals a beautiful length, of the curvature and the fat. ", "img" : "http://www.infinivin.com/2065-large/chateau-de-fontcreuse-cassis-rose-wine-2015.jpg"}, {"title" : "Château de Fontcreuse magnum : Cassis - Blanc 2013", "url" : "http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2013-493.html", "desc" : "Ce Cassis Fontcreuse blanc 2013 possède une très belle robe aux petits reflets dorés, limpide, brillante ! Le nez est puissant, agréable et fin. Les notes fruitées se développent en priorité ; pamplemousse, amande amère, pêche, suivies de pomme golden, plus miellée que la pomme verte, attaque voluptueuse en bouche, vive, pleine de chair, tout ce qui donne un vin nerveux, bien équilibré et savoureux que ce Cassis du Domaine Fontcreuse !", "img" : "http://www.infinivin.com/663-large/chateau-de-fontcreuse-magnum-cassis-blanc-2013.jpg"}, {"title" : "Magnum Domaine du Paternel : Cassis - White wine 2015", "url" : "http://www.infinivin.com/en/magnum-domaine-du-paternel-cassis-white-wine-2015-917.html", "desc" : "The nose of the Magnum Domaine du Paternel white wine 2015 is on citrus fruits aromas, marked by thyme in the finish. In the mouth, we find  white flower and peach aromas. The lenght is beautiful and this white wine is among the aerial wines.", "img" : "http://www.infinivin.com/1201-large/magnum-domaine-du-paternel-cassis-white-wine-2015.jpg"}, {"title" : "Château de Fontcreuse : Cassis - White wine 2011", "url" : "http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2014-902.html", "desc" : "This white \'Cassis Fontcreuse\' 2011 possesses a very beautiful colour in the small golden reflections, crystal clear, brilliant! The nose is powerful, pleasant and fine. The fruity notes(marks) develop first and foremost; grapefruit, bitter almond, peach, followed by Golden Delicious apple, more honeyed than the green apple, the sensual attack in mouth, lives, full of flesh, all which gives a nervous, well balanced and delicious wine that this \'Cassis\' of the Domaine Fontcreuse!", "img" : "http://www.infinivin.com/1955-large/chateau-de-fontcreuse-cassis-white-wine-2014.jpg"}, {"title" : "Domaine du Paternel : Cassis - Rosé 2013", "url" : "http://www.infinivin.com/en/domaine-du-paternel-cassis-rose-2013-59.html", "desc" : "This rosé of the Domaine du Paternel 2013 slightly salmon, in the attractive nose of citrus fruits and fruits days, has the frank, well-balanced mouth, everything in lightness and in liveliness.", "img" : "http://www.infinivin.com/248-large/domaine-du-paternel-cassis-rose-2013.jpg"}]}';
echo $json;
	return;
} else if($page == "wine") {
	$VinService = new VinService();
	$listNameOfWine = $VinService->RecoverNamesWines();
	if ($listNameOfWine) {
	    echo json_encode($listNameOfWine);
	    return;
	} 
}

//identifiant du moteur de recher : 016014982774890444637:tirltd59_os
//API key: AIzaSyAQ1rgsSDdetI6uhC9egwf_OqdDprHwB-g
//		   AIzaSyBQhROXNGtRsvRaDvDQeax-Q5S33-U2yKQ	
// $ch = curl_init("https://www.googleapis.com/customsearch/v1?key=AIzaSyAQ1rgsSDdetI6uhC9egwf_OqdDprHwB-g&cx=016014982774890444637:tirltd59_os&q=vin");

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, $result);

// curl_exec($ch);

// echo $result;

$c = new CustomSearch("cassis");
$c->execute();
echo $c->get_urls_as_JSON();
echo "<br/><br/>";
echo $c->get_links_as_JSON();

?>