<?php

/**
* 
*/
class CustomSearch
{
	private static $baseUrl = "https://www.googleapis.com/customsearch/v1";

	private static $key = "AIzaSyAQ1rgsSDdetI6uhC9egwf_OqdDprHwB-g"; //"AIzaSyBQhROXNGtRsvRaDvDQeax-Q5S33-U2yKQ";

	private static $cx = "016014982774890444637:tirltd59_os";

	//string contenant les mots clés de la recherche
	private $querry;

	private $jsonResult;
	
	function __construct($querry)
	{
		$this->querry = str_replace(" ", "+", $querry);
	}

	function executer() {
		$request = curl_init(self::$baseUrl."?key=".self::$key."&cx=".self::$cx."&q=".$this->querry);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_HTTPHEADER, array(
		    'Accept: application/json'
		));	
		curl_setopt($request, CURLOPT_TIMEOUT, 5);
		curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);

		$resultAsString = curl_exec($request);
		curl_close($request);


		$this->jsonResult = json_decode(utf8_encode($resultAsString),true);
		echo $this->jsonResult;
		foreach ($this->jsonResult["items"] as $key => $value) {
			echo ($value);
		}
	}

}



// $ch = curl_init('http://spotlight.sztaki.hu:2222/rest/annotate?text=' . $textTranform . "&confidence=" . $confidence);
// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// 			    'Accept: application/json'
// 			));
// 			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
// 			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
// 			//execute post
// 			$result = curl_exec($ch);
// 			//close connection
// 			curl_close($ch);
// 			$array = json_decode($result,TRUE);
// 			print_r($array);
// 			if(!empty($array) && array_key_exists("Resources",$array)){
// 				$results = $array["Resources"];
// 				$output = array();
// 				foreach ($results as $r) {
// 				  $output[$r["@surfaceForm"]][] = array("URI"=>$r["@URI"]);
// 				}
// 				return $output;
// 			} else {
// 				return;
// 			}


?>