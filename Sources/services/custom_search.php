

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

	public $elements;
	
	function __construct($query)
	{
		$this->query = str_replace(" ", "+", $query);
		$links = array();
		$elements =  array();
	}

	function execute() {
		$jsonResult = $this->execute_request();
		$this->load_links($jsonResult);
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
		$arrayP = array();
		$arrayStrong = array();

		for ($i=0; $i < $this->nbLinks; $i++) { 
			$request = curl_init($this->links[$i]);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);	
			curl_setopt($request, CURLOPT_TIMEOUT, 5);
			curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
			$resultAsString = curl_exec($request);
			curl_close($request);

			$dom = new DomDocument();
			$dom->loadHTML($resultAsString);

			$listeP = $dom->getElementsByTagName('p');
			foreach ($listeP as $p) {
				if(!empty($p->nodeValue)) {
					$arrayP[] = $p->nodeValue;
				}
			}

			$listeStrong = $dom->getElementsByTagName('strong');
			foreach ($listeStrong as $strong) {
				if(!empty($p->nodeValue)) {
					$arrayStrong[] = $strong->nodeValue;
				}
			}
		}
				
		$elements["p"] = $arrayP;
		$elements["strong"] = $arrayStrong;

		echo "P : <br/>";
		foreach ($elements["p"] as $p) {
			echo $p."<br/>";
		}
		echo "<br/><br/><br/>Strong : <br/>";
		foreach ($elements["strong"] as $strong) {
			echo $strong."<br/>";
		}
		echo "fin";
	}

}

?>