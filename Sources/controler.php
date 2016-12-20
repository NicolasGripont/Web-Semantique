<?php
//ini_set('display_errors',1);
require_once(__DIR__ . "/services/custom_search.php");
require_once(__DIR__ . "/services/uri_extractor.php");
require_once(__DIR__ . "/services/service_wine.php");
require_once(__DIR__ . "/services/connection.php");
require_once(__DIR__ . "/services/social_networks.php");

$page = $_GET["page"];

if($page == "search") {
	$request = $_GET["request"];
	//Algorithme de recherche
	// 1 - Recherche sur google
	// 1' - Recherche sur les réseaux sociaux
	// 2 - Extraction des résultats
	// 3 - Reconnaissance de mots clés et recherche des URI associé
	// 4 - Recherche des informations importante grace au URI SPARQL

	//echo json_encode($request);
	$string = file_get_contents("example.json");
	$json_a = json_decode($string, true);
	echo $json_a;
	return;

	$social_networks = new Social_networks();
	$resSN = $social_networks->searchNewsOnSocialNetworks("request", true);

	$uriExtractor = new URI_Extrator();
	foreach($paragraphes as $p) {
		$resURI = json_encode($uriExtractor->getURIForText($p, 0.35));
	}

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