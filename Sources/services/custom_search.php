

<?php
require_once("link.php");
/**
* 
*/
class CustomSearch
{
	private static $baseUrl = "https://www.googleapis.com/customsearch/v1";

	private static $key = "AIzaSyDEOeWub1lWnRmRRNkCKhUhewRsUkI1UiQ";//"AIzaSyBeV2zLuUB_tufpfzL4cPFJl8_P2VL8R5E";//"AIzaSyAQ1rgsSDdetI6uhC9egwf_OqdDprHwB-g"; //"AIzaSyBQhROXNGtRsvRaDvDQeax-Q5S33-U2yKQ";

	private static $cx = "013284499051722939158:hxzrjba3w-c";//"016014982774890444637:tirltd59_os";

	//string contenant les mots clés de la recherche
	private $query;

	private $urls ;
//= ["http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html",
// "http://www.infinivin.com/en/domaine-du-paternel-cassis-white-wine-2015-801.html",
// "http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2015-886.html"];

	private $links;
	
	private $texts;


// = 3;
	
	function __construct($query)
	{
		$this->query = str_replace(" ", "+", $query);
		$urls = array();
		$elements =  array();
		$links = array();
		$texts = array();
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
		if(array_key_exists("items",$jsonResult)) {
			foreach ($jsonResult["items"] as $key => $value) {
				$this->urls[] = $value["link"];
			}
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
		
		$array_text = array();
		for ($i=0; $i < sizeof($results); $i++) {
			$dom = new DomDocument();
			@$dom->loadHTML($results[$i]);
			$this->create_links($this->urls[$i],$dom);
			$array_text2 = $this->load_text($dom);
			$array_text = array_merge($array_text2, $array_text);
		}
		$this->texts = $array_text;
	}
	
	
	/*fonction pour recuperer les noeuds a partir d'un nom de classe */
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
		
		//on récupère la meta description
		$elements = $dom->getElementsByTagName("meta");
		foreach($elements as $element) {
			if($element->getAttribute('name')=="description") {
				$arrayP[] = $element->getAttribute('content');
			}
		}
		
		//on recupere le premier descriptif du vin
		$elem1 = $dom->getElementById('proDesc');
		if(!empty($elem1) && $elem1->hasChildNodes()) {
			foreach ($elem1->childNodes as $text) {
				if(trim($text->nodeValue) !== "") {
					$arrayP[] = $text->nodeValue;
				}
			}
		}
		
		//on recupere le deuxième descriptif du vin
		$elem2 = $dom->getElementById('proCara');
		if(!empty($elem2) && $elem2->hasChildNodes()) {
			foreach ($elem2->childNodes as $text) {
				if(trim($text->nodeValue) !== "") {
					$arrayP[] = $text->nodeValue;
				}
			}
		}

		//on recupere les textes suivants
		$node = $dom->getElementsByTagName("body");
		if($node[0] !== null) {
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
		}
		return $arrayP;
	}
	
	public function create_links($url,$dom) {
		$div = $dom->getElementById('proImg');
	
		$title = $url;
		$elements = $dom->getElementsByTagName("title");
		@$title = $elements[0]->nodeValue;
		if($title == "") {
			$title = $url;
		}
		$desc = "";
		$elements = $dom->getElementsByTagName("meta");
		foreach($elements as $element) {
			if($element->getAttribute('name')=="description") {
				$desc = $element->getAttribute('content');
			}
		}
		$link = new Link($url,$title,$desc);
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
	
	public function get_texts() {
		return $this->texts;
	}
	
	public function get_links() {
		return $this->links;
	}
}
?>