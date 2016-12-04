<?php
//ini_set('display_errors',1);
require_once(__DIR__ . "/../model/Services/service_vin.php");
require_once(__DIR__ . "/../model/Services/connexion.php");

$VinService = new VinService();
$listNameOfWine = $VinService->RecoverNamesWines();

if ($listNameOfWine) {
    echo json_encode($listNameOfWine);
    return;
} 
else {
    return false;
}