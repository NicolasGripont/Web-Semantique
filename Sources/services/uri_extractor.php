<?php
	class URI_Extrator {
		private $urlDBPediaSpotlighByKeyWord = "http://lookup.dbpedia.org/api/search.asmx/KeywordSearch?QueryString=";
		private $urlDBPediaSpotlighByText = "http://spotlight.sztaki.hu:2222/rest/annotate";

		public function __construct()
    	{
    	}
		
		public function getURIForKeyword($word) {
			$res = file_get_contents ($this->urlDBPediaSpotlighByKeyWord . $word);
			$xml = simplexml_load_string($res);
			$json = json_encode($xml);
			$array = json_decode($json,TRUE);
			if(!empty($array) && array_key_exists("Result",$array)){
				$results = $array["Result"];
				$output = array();
				foreach ($results as $r) {
				  $output[$word][] = array("URI"=>$r["URI"],"Label"=>$r["Label"]);
				}
				return $output;
			} else {
				return;
			}
		}

		public function getURIForText($text,$confidence) {
			if($confidence > 1) 
				$confidence = 0.35;
			$textTranform = str_replace(" ","%20",$text); 
			$ch = curl_init($this->urlDBPediaSpotlighByText .'?text=' . $textTranform . "&confidence=" . $confidence . "&types=");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			    'Accept: application/json'
			));
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$result = curl_exec($ch);
			curl_close($ch);
			$array = json_decode($result,TRUE);
			if(!empty($array) && array_key_exists("Resources",$array)){
				$results = $array["Resources"];
				$output = array();
				foreach ($results as $r) {
				  $output[$r["@surfaceForm"]][] = array("URI"=>$r["@URI"]);
				}
				return $output;
			} else {
				return;
			}
		}
	}
?>