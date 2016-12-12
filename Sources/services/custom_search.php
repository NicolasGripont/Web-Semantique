

<?php
require_once("link.php");
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

	private $urls ;
//= ["http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html",
// "http://www.infinivin.com/en/domaine-du-paternel-cassis-white-wine-2015-801.html",
// "http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2015-886.html"];

	private $links;

// = 3;
	
	function __construct($query)
	{
		$this->query = str_replace(" ", "+", $query);
		$urls = array();
		$elements =  array();
		$links = array();
	}

	function execute() {
		$jsonResult = $this->execute_request();
		$this->load_urls($jsonResult);
		$this->get_urls_results();
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

	private function load_urls($jsonResult) {
		foreach ($jsonResult["items"] as $key => $value) {
			$this->urls[] = $value["link"];
		}
	}

	private function get_urls_results() {
		$chs = array();
		$results = array();

		// Création des ressources cURL et definition des options
		for ($i=0; $i < sizeof($this->urls); $i++) { 
			$chs[$i] = curl_init($this->urls[$i]);
			curl_setopt($chs[$i], CURLOPT_RETURNTRANSFER, true);	
			curl_setopt($chs[$i], CURLOPT_TIMEOUT, 5);
			curl_setopt($chs[$i], CURLOPT_CONNECTTIMEOUT, 5);
		}

		// Création du gestionnaire multiple
		$mh = curl_multi_init();

		// Ajout des gestionnaires
		for ($i=0; $i < sizeof($this->urls); $i++) { 
			curl_multi_add_handle($mh,$chs[$i]);
		}

		// Exécute le gestionnaire
		$running = null;
		do {
   			curl_multi_exec($mh, $running);
		} while($running > 0);

		// Recupération des résultats et femeture des gestionnaires
		foreach ($chs as $id => $ch) {
		    $results[$id] = curl_multi_getcontent($ch);
		    curl_multi_remove_handle($mh, $ch);
		}

		// Fermeture des gestionnaires
		for ($i=0; $i < sizeof($this->urls); $i++) { 
			curl_multi_remove_handle($mh, $chs[$i]);
		}
		curl_multi_close($mh);

		foreach ($results as $result) {
			$dom = new DomDocument();
			$dom->loadHTML($result);
			$this->create_links($this->urls[$i],$dom);
		}
	}

	public function create_links($url,$dom) {
		$div = $dom->getElementById('proImg');
		$img = $div->getElementsByTagName('img')[0]->getAttribute('src');

		$title = $dom->getElementById('proTitre')->nodeValue;

		$desc = "";
		$listeDiv = $dom->getElementsByTagName('div');
		foreach ($listeDiv as $div) {
			if($div->getAttribute('class') == "desc simple_wysiwyg") {
				$desc = $div->getElementsByTagName('p')[0]->nodeValue;
				break;
			}
		}
		$link = new Link($url,$title,$img,$desc);
		$this->links[] = $link;
	}

	public function get_links_as_JSON() {
		$jsonStr = "{\"links\" : [";

		for ($i=0; $i < sizeof($this->links) ; $i++) { 
			$jsonStr .= $this->links[$i]->toJSON();
			if($i < (sizeof($this->links) - 1)) {
				$jsonStr .= ", ";
			}
		}	

		$jsonStr .= "]}";
		return $jsonStr;
	}

	public function get_urls_as_JSON() {
		$jsonStr = "{\"urls\" : [";

		for ($i=0; $i < sizeof($this->urls) ; $i++) { 
			$jsonStr .= "\"".$this->urls[$i]."\"";
			if($i < (sizeof($this->urls) - 1)) {
				$jsonStr .= ", ";
			}
		}	

		$jsonStr .= "]}";
		return $jsonStr;
	}
	
}



?>