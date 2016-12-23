

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

	private $links = ["http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html",
						"http://www.infinivin.com/en/domaine-du-paternel-cassis-white-wine-2015-801.html",
						"http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2015-886.html"];
// http://www.infinivin.com/en/domaine-du-paternel-cassis-rose-wine-2015-796.html
// http://www.infinivin.com/en/domaine-du-paternel-cassis-blanc-2013-60.html
// http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-rose-wine-2015-888.html
// http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2013-493.html
// http://www.infinivin.com/en/magnum-domaine-du-paternel-cassis-white-wine-2015-917.html
// http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2014-902.html
// http://www.infinivin.com/en/domaine-du-paternel-cassis-rose-2013-59.html;

	private $nbLinks = 3;

	public $elements;
	
	function __construct($query)
	{
		$this->query = str_replace(" ", "+", $query);
		$links = array();
		$elements =  array();
	}

	function execute() {
		// $jsonResult = $this->execute_request();
		// $this->load_links($jsonResult);
		$this->get_links_results();
	}

	private function execute_request() {
		$request = curl_init(self::$baseUrl."?key=".self::$key."&cx=".self::$cx."&q=".$this->query."&fileType=html");
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
		$array_text = array();
		for ($i=0; $i < $this->nbLinks; $i++) { 
			$request = curl_init($this->links[$i]);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);	
			curl_setopt($request, CURLOPT_TIMEOUT, 5);
			curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
			$resultAsString = curl_exec($request);
			curl_close($request);

			$dom = new DomDocument();
			$dom->loadHTML($resultAsString);
			$array_text2 = $this->load_text($dom);
			$array_text = array_merge($array_text, $array_text2);
		}
		print_r($array_text);
		echo "fin";
	}
	
	private function getElementsByClass(&$parentNode, $tagName, $className) {
		$nodes=array();
	
		$childNodeList = $parentNode->getElementsByTagName($tagName);
		for ($i = 0; $i < $childNodeList->length; $i++) {
			$temp = $childNodeList->item($i);
			if (stripos($temp->getAttribute('class'), $className) !== false) {
				$nodes[]=$temp;
			}
		}
	
		return $nodes;
	}
	
	private function load_text($dom) {
		$arrayP = array();
		//on recupere le premier descriptif du vin
		$elem1 = $dom->getElementById('proDesc');
		if($elem1->hasChildNodes()) {
			foreach ($elem1->childNodes as $text) {
				if(trim($text->nodeValue) !== "") {
					$arrayP[] = $text->nodeValue;
				}
			}
		}
		//on recupere le deuxième descriptif du vin
		$elem2 = $dom->getElementById('proCara');
		if($elem2->hasChildNodes()) {
			foreach ($elem2->childNodes as $text) {
				if(trim($text->nodeValue) !== "") {
					$arrayP[] = $text->nodeValue;
				}
			}
		}
		//on recupere les textes suivants
		$node = $dom->getElementsByTagName("body");
		$elem3 = $this->getElementsByClass($node[0],"div","simple_wysiwyg");
		foreach($elem3 as $noeud) {
			if($noeud->hasChildNodes()) {
				foreach ($noeud->childNodes as $text) {
					if(trim($text->nodeValue) !== "") {
						$arrayP[] = $text->nodeValue;
					}
				}
			}
		}
		return $arrayP;
	}

}

?>