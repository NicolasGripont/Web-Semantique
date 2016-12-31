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

		public function getURIForTexts($texts, $confidence) {
			if($confidence > 1) 
				$confidence = 0.35;
			$chs = array();
			$results = array();
			
			for ($i=0; $i < sizeof($texts); $i++) { 
				$textTranform = str_replace(" ","%20",$texts[$i]); 
				$chs[$i] = curl_init($this->urlDBPediaSpotlighByText .'?text=' . $textTranform . "&confidence=" . $confidence . "&types=");
				curl_setopt($chs[$i], CURLOPT_RETURNTRANSFER, true);	
				curl_setopt($chs[$i], CURLOPT_HTTPHEADER, array(
				    'Accept: application/json'
				));
				curl_setopt($chs[$i], CURLOPT_TIMEOUT, 5);
				curl_setopt($chs[$i], CURLOPT_CONNECTTIMEOUT, 5);
			}

			$mh = curl_multi_init();
			for ($i=0; $i < sizeof($texts); $i++) {
				curl_multi_add_handle($mh,$chs[$i]);
			}

			$running = null;
			do {
				curl_multi_exec($mh, $running);
			} while($running > 0);

			foreach ($chs as $id => $ch) {
				$results[$id] = curl_multi_getcontent($ch);
				curl_multi_remove_handle($mh, $ch);
			}

			for ($i=0; $i < sizeof($texts); $i++) {
				curl_multi_remove_handle($mh, $chs[$i]);
			}
			curl_multi_close($mh);

			$res = array();
			foreach ($results as $result) {
				$ar = json_decode($result,TRUE);
				if(!empty($ar) && array_key_exists("Resources",$ar)){
					$results = $ar["Resources"];
					$output = array();
					foreach ($results as $r) {
					  $output[$r["@surfaceForm"]][] = array("URI"=>$r["@URI"]);
					}
					$res[] = $output;
				}
			}
			return $res;
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

		private function buildArrayWordApparitionURI($tab) {
			$mots = array();
			foreach ($tab as $i => $t) {
				foreach ($t as $j => $word) {
					if(array_key_exists($j,$mots)) {
						$mots[$j][0]++;
					} else {
						$mots[$j][0] = 1;
					}
					foreach ($word as $uri) {
						$mots[$j][1][] = $uri;
					}
				}
			}
			return $mots;
		}

		public function getWordByBestOccurence($tab, $nbMots) {
			$res = $this->buildArrayWordApparitionURI($tab);
			$resSort = array();
			while(sizeof($res) > 0) {
				$best = null;
				foreach ($res as $i => $r) {
					if($best == null || $res[$best][0] < $res[$i][0])
						$best = $i;
				}
				$resSort[$best] = $res[$best];
				unset($res[$best]);
			}
			return array_slice($resSort,0,$nbMots);
		}

		public function getWordByBestURICount($tab, $nbMots) {
			$res = $this->buildArrayWordApparitionURI($tab);
			$resSort = array();
			while(sizeof($res) > 0) {
				$best = null;
				foreach ($res as $i => $r) {
					if($best == null || sizeof($res[$best][1]) < sizeof($res[$i][1]))
						$best = $i;
				}
				$resSort[$best] = $res[$best];
				unset($res[$best]);
			}
			return array_slice($resSort,0,$nbMots);
		}

		public function stats($tab) {
			foreach ($tab as $i => $r) {
				echo("Mot " . $i . " : apparu " . $r[0]. " fois, " . sizeof($r[1]) . " URI<br>");
			}
		}
	}

	/*$ex = new URI_Extrator();
	$t[] = "First documented in the 13th century, Berlin was the capital of the Kingdom of Prussia (1701–1918), the German Empire (1871–1918), the Weimar Republic (1919–33) and the Third Reich (1933–45). Berlin in the 1920s was the third largest municipality in the world. After World War II, the city became divided into East Berlin -- the capital of East Germany -- and West Berlin, a West German exclave surrounded by the Berlin Wall from 1961–89. Following German reunification in 1990, the city regained its status as the capital of Germany, hosting 147 foreign embassies.";
	$t[] = "First documented in the 13th century, was the capital of the Kingdom of Prussia (1701–1918), the German Empire (1871–1918), the Weimar Republic (1919–33) and the Third Reich (1933–45). Berlin in the 1920s was the third largest municipality in the world. After World War II, the city became divided into East Berlin -- the capital of East Germany -- and West Berlin, a West German exclave surrounded by the Berlin Wall from 1961–89. Following German reunification in 1990, the city regained its status as the capital of Germany, hosting 147 foreign embassies.";
	$res = $ex->getURIForTexts($t, 0.35);
	$r1 = $ex->getWordByBestOccurence($res,5);
	$r2 = $ex->getWordByBestURICount($res,5);
	$ex->stats($r1);
	echo "<br>";
	$ex->stats($r2);*/
?>