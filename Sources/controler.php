<?php
ini_set('display_errors',1);
require_once(__DIR__ . "/services/custom_search.php");
require_once(__DIR__ . "/services/uri_extractor.php");
require_once(__DIR__ . "/services/service_wine.php");
require_once(__DIR__ . "/services/connection.php");
require_once(__DIR__ . "/services/social_networks.php");
require_once(__DIR__ . "/services/sparql.php");

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

	// 1 - Recherche sur google
	$c = new CustomSearch($_GET["request"]);
	$c->execute();
	// CHRONO
	/*echo("CustomSearch : " . (time() - $t) . "s<br>");
	$t = time();*/

	// 1' - Recherche sur les réseaux sociaux
	$social_networks = new Social_networks();
	$resSN = $social_networks->searchNewsOnSocialNetworks($request, true);
	$response["social"]["twitter"] = $resSN["twitter"]["results"];
	// CHRONO
	/*echo("Recherche sociales : " . (time() - $t) . "s<br>");
	$t = time();*/

	// 2 - Extraction des résultats
	$paragraphes = $c->get_texts();
	$links = $c->get_links();
	$response["articles"] = $links;
	// CHRONO
	/*
	echo("Extraction resultat : " . (time() - $t) . "s<br>");
	$t = time();
	*/

	// 3 - Reconnaissance de mots clés et recherche des URI associé
	$uriExtractor = new URI_Extrator();
	$resURI = $uriExtractor->getURIForTexts($paragraphes, 0.35);
	
	// Si tu veux les 5 premiers mots les plus utilisé (meilleure occurence)
	$words = $uriExtractor->getWordByBestOccurence($resURI, 5);

	// Si tu veux les 5 premiers mots avec le plus d'URI
	$uris = $uriExtractor->getWordByBestURICount($resURI, 5);

	// Si tu veux des stats
	//$uriExtractor->stats($words);
	//$uriExtractor->stats($uris);

	// CHRONO
	/*echo("Reconnaissance de mots : " . (time() - $t) . "s<br>");
	$t = time();*/

	//TEST donnees Quentin
	/*echo("Nb de paragraphes : " . sizeof($paragraphes) . "<br>");
	foreach ($resURI as $i => $r) {
		echo("Nb de URI pour p ". ($i+1) . " : " . sizeof($r) . "<br>");
	}*/

	// 4 - Recherche des informations importante grace au URI SPARQL
	$spq = new Sparql_engine();
	//parcours des labels les plus fréquents
	foreach($uris as $label => $groupURI) {
		//pour chaque label, on parcourt les uris donnees et on recupere les infos
		foreach(array_unique($groupURI[1]) as $URI) {
			$infos = $spq->getDBPediaInfos($URI);
			$response["dbpedia_desc"][$label][] = $infos;
		}
	}
	
	$response["infinivin"] = $spq->getInfinivinRDFInfos($_GET["request"]);
	
	//TEST donnees Nico
	//echo json_encode($response["dbpedia_desc"]);
	//return
	
	// 5 - Recherche de recettes
	
	$responseJSON = json_encode($response);
	flush();
	echo $responseJSON;
	return;
	
	//echo $responseJSON;
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