

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

	private $urls = ["http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2015-918.html",
						"http://www.infinivin.com/en/domaine-du-paternel-cassis-white-wine-2015-801.html",
						"http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2015-886.html"];
// http://www.infinivin.com/en/domaine-du-paternel-cassis-rose-wine-2015-796.html
// http://www.infinivin.com/en/domaine-du-paternel-cassis-blanc-2013-60.html
// http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-rose-wine-2015-888.html
// http://www.infinivin.com/en/chateau-de-fontcreuse-magnum-cassis-blanc-2013-493.html
// http://www.infinivin.com/en/magnum-domaine-du-paternel-cassis-white-wine-2015-917.html
// http://www.infinivin.com/en/chateau-de-fontcreuse-cassis-white-wine-2014-902.html
// http://www.infinivin.com/en/domaine-du-paternel-cassis-rose-2013-59.html;

	private $links;

	private $nbUrls = 3;

	public $elements;
	
	function __construct($query)
	{
		$this->query = str_replace(" ", "+", $query);
		$urls = array();
		$elements =  array();
		$links = array();
	}

	function execute() {
		// $jsonResult = $this->execute_request();
		// $this->load_urls($jsonResult);
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
		$nbUrls = 0;
		foreach ($jsonResult["items"] as $key => $value) {
			$this->urls[$nbUrls] = $value["link"];
			$nbUrls = $nbUrls+1;
		}
		$this->nbUrls = $nbUrls;
		for ($i=0; $i < $this->nbUrls; $i++) { 
			echo $this->urls[$i]."<br/>";
		}
	}

	private function get_urls_results() {
		$arrayP = array();
		$arrayStrong = array();

		for ($i=0; $i < $this->nbUrls; $i++) { 
			$request = curl_init($this->urls[$i]);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);	
			curl_setopt($request, CURLOPT_TIMEOUT, 5);
			curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
			$resultAsString = curl_exec($request);
			curl_close($request);

			$dom = new DomDocument();
			$dom->loadHTML($resultAsString);
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
}



?>