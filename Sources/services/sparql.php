<?php
	require_once( "sparqllib.php" );
	
	public class Sparql {
		private $graphs = ['http://dbpedia.org', 'http://wineagent.tw.rpi.edu/data/wine.rdf'];
		private $prefix_tab = ['foaf' => 'http://xmlns.com/foaf/0.1/',
							   'dbo' => 'http://dbpedia.org/ontology/',
							   'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#'];
		
		private function connection() {
			$db = sparql_connect( "http://dbpedia.org/sparql" );
			if( !$db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
			foreach($prefix_tab as $key => $prefix) {
				$db->ns($key, $prefix);
			}
		}
		
		private function performQuery($sparql) {
			$result = $db->query( $sparql ); 
			if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
			 
			return $result->fetch_all();
		}
		
		public function getDBPediaInfos($URI) {
			$sparql = "select * where {
						{ ".$URI."> rdfs:label ?label. 
							  FILTER (langMatches (lang (?label) , \"en\")) }
						UNION
						{ ".$URI."> dbo:abstract ?text.
							  FILTER (langMatches (lang (?text) , \"en\"))}
						UNION
						{ ".$URI."> foaf:depiction ?photo.}
						UNION
							{".$URI."> dbo:wikiPageExternalLink ?pages_liees.}
						}	";
						
			return json_encode(performQuery($sparql));
		}
		
		public function getWineRecettes($URI){
			$sparql = "select * where {
					      ?obj dbo:ingredient ".$URI.".
					  }"
			return json_encode(performQuery($sparql));
		}
	}
