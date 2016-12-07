<?php  
//ini_set('display_errors',1);
require_once(__DIR__ . "/../modeles/Services/service_wine.php");
require_once(__DIR__ . "/../modeles/Services/connection.php");

/***** Recupération des informations voulus *****/ 

//récupération du html
$html = file_get_contents('https://fr.wikipedia.org/wiki/Liste_des_vins_AOC_fran%C3%A7ais');

//création du doc dom
$doc=new domDocument;
//charge le fichier html
@$doc->loadHTML($html);
//prends les tables
$tables=$doc->getElementsByTagName('table');

// on récupère les tr de la première table uniquement
$lignes=$tables->item(0)->getElementsByTagName('tr');

foreach ($lignes as $key => $ligne) {
	//pour ne pas prendre les noms des col du tableau
	if ($key>1)
	{
	 $cols=$ligne->childNodes;
	 $listNameOfWine[]=$cols->item(0)->nodeValue;
	}
}
/***** Ajout des informations dans la base *****/
 
$vinService = new VinService();

foreach ($listNameOfWine as $wine) {
	$test = $vinService->AddNewNameWine($wine);
	if ($test)
	{
		echo "Le champ $wine est bien ajouter dans la base de donnée";	
	}
	else
	{
		echo "Le champ $wine est déjà présent dans la base de donnée";	
	}
}

?>