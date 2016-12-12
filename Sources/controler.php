<?php

require_once('services/custom_search.php');
require_once("services/service_wine.php");
require_once("services/connection.php");

$page = $_GET["page"];


if($page == "search") {
	$request = $_GET["request"];

	echo json_encode($request);
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