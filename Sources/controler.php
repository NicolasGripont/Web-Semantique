<?php
ini_set('display_errors',1);
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
	// 5 - Recherche de recettes

	/*//echo json_encode($request);
	$string = file_get_contents("example.json");
	//$json_a = json_encode($string, true);
	echo $string;
	return;*/
	$start = time();
	$t = time();
	//echo("Start process");

	// 1 - Recherche sur google
	$c = new CustomSearch("cassis");
	$c->execute();
	/*echo("CustomSearch : " . (time() - $t) . "s<br>");
	$t = time();*/

	// 1' - Recherche sur les réseaux sociaux
	$social_networks = new Social_networks();
	$resSN = $social_networks->searchNewsOnSocialNetworks($request, true);
	$response["social"]["twitter"] = $resSN["twitter"]["results"];
	/*echo("Recherche sociales : " . (time() - $t) . "s<br>");
	$t = time();*/

	// 2 - Extraction des résultats
	$paragraphes = $c->get_texts();
	$links = $c->get_links();
	$response["articles"] = $links;
	/*echo("Extraction resultat : " . (time() - $t) . "s<br>");
	$t = time();*/

	// 3 - Reconnaissance de mots clés et recherche des URI associé
	$uriExtractor = new URI_Extrator();
	$resURI = $uriExtractor->getURIForTexts($paragraphes, 0.35);
	/*echo("Reconnaissance de mots : " . (time() - $t) . "s<br>");
	$t = time();
	echo("Nb de paragraphes : " . sizeof($paragraphes) . "<br>");
	foreach ($resURI as $i => $r) {
		echo("Nb de URI pour p ". ($i+1) . " : " . sizeof($r) . "<br>");
	}*/

	// 4 - Recherche des informations importante grace au URI SPARQL

	// 5 - Recherche de recettes

	$responseJSON = json_encode($response);

	echo $responseJSON;
	//echo("Temps total : " . (time() - $start) . "s<br>");
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
?>