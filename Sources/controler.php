<?php
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_DEPRECATED);
require_once(__DIR__ . "/services/custom_search.php");
require_once(__DIR__ . "/services/uri_extractor.php");
require_once(__DIR__ . "/services/service_wine.php");
require_once(__DIR__ . "/services/connection.php");
require_once(__DIR__ . "/services/social_networks.php");
require_once(__DIR__ . "/services/sparql.php");
require_once(__DIR__ . "/services/recipe_search.php");

$page = $_GET["page"];

if($page == "search") {
	$request = $_GET["request"];
	$chrono = false;
	//Algorithme de recherche
	// 1 - Recherche sur google
	// 1' - Recherche sur les réseaux sociaux
	// 2 - Extraction des résultats
	// 3 - Reconnaissance de mots clés et recherche des URI associé
	// 4 - Recherche des informations importante grace au URI SPARQL
	// 5 - Recherche de recettes avec les api Wine et Food

	/*//echo json_encode($request);
	$string = file_get_contents("example.json");
	//$json_a = json_encode($string, true);
	echo $string;
	return;*/

	if($chrono) {
		$start = time();
		$t = time();
	}
	
	// 1 - Recherche sur google
	$c = new CustomSearch($_GET["request"]);
	$c->execute();
	if($chrono) {
		echo("1 - CustomSearch : " . (time() - $t) . "s<br>");
		$t = time();
	}

	// 1' - Recherche sur les réseaux sociaux
	$social_networks = new Social_networks();
	$resSN = $social_networks->searchNewsOnSocialNetworks($request, true);
	$response["social"]["twitter"] = $resSN["twitter"]["results"];
	if($chrono) {
		echo("1' - Recherche sociales : " . (time() - $t) . "s<br>");
		$t = time();
	}

	// 1' - Recherche sur l'api Wine

	// 1' - Recherche sur l'api Food
	
	// 2 - Extraction des résultats
	$paragraphes = $c->get_texts();
	$links = $c->get_links();
	$response["articles"] = $links;
	if($chrono) {
		echo("2 - Extraction resultat : " . (time() - $t) . "s<br>");
		$t = time();
	}

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

	if($chrono) {
		echo("3 - Reconnaissance de mots : " . (time() - $t) . "s<br>");
		$t = time();
	}

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
	
	$response["domain"] = $spq->getInfinivinRDFDomain($request);
	$response["infinivin"] = $spq->getInfinivinRDFInfos($request);
	
	if($chrono) {
		echo("4 - Recherche des informations : " . (time() - $t) . "s<br>");
		$t = time();
	}
	//TEST donnees Nico
	//echo json_encode($response["dbpedia_desc"]);
	//return
	
	// 5 - Recherche de recettes
	

	// 6 - Renvoie du resultat en JSON
	$responseJSON = json_encode($response);
	flush();
	echo $responseJSON;
	
	if($chrono) {
		echo("<br>Temps total : " . (time() - $start) . "s<br>");
	}
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
?>