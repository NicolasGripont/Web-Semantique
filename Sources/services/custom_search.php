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
	private $query;

	private $links;

	private $nbLinks;
	
	function __construct($query)
	{
		$this->query = str_replace(" ", "+", $query);
		$links = array();
	}

	function execute() {
		$jsonResult = $this->execute_request();
		echo $jsonResult;
		$this->load_links($jsonResult);
		$this->get_links_results();
	}

	private function execute_request() {
		$request = curl_init(self::$baseUrl."?key=".self::$key."&cx=".self::$cx."&q=".$this->query);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_HTTPHEADER, array(
		    'Accept: application/json'
		));	
		curl_setopt($request, CURLOPT_TIMEOUT, 5);
		curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		$resultAsString = curl_exec($request);
		curl_close($request);
		$jsonResult = json_decode(utf8_encode($resultAsString),true);

		return $jsonResult;
	}

	private function load_links($jsonResult) {
		$nbLinks = 0;
		foreach ($jsonResult["items"] as $key => $value) {
			$this->links[$nbLinks] = $value["link"];
			$nbLinks = $nbLinks+1;
		}
		$this->nbLinks = $nbLinks;
		for ($i=0; $i < $this->nbLinks; $i++) { 
			echo $this->links[$i]."<br/>";
		}
	}

	private function get_links_results() {
		// for ($i=0; $i < $this->nbLinks; $i++) { 
			// $request = curl_init($this->links[$i]);
			echo ">".$this->links[0];
			$request = curl_init($this->links[0]);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);	
			curl_setopt($request, CURLOPT_TIMEOUT, 5);
			curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
			$resultAsString = curl_exec($request);
			curl_close($request);
			// $dom = new DomDocument();
			// $dom->loadXML($resultAsString);


			// print_r($dom);
			// $listeP = $dom->getElementsByTagName('div');
			// print_r($listeP);
			// foreach ($listeP as $p) {
			// 	echo $p->nodeValue."<br/>";
			// }

		// }
		
	}

}

?>