<?php
	require_once( "sparqllib.php" );
	
	class Sparql_engine {
		public function __construct()
    	{
    	}
		
		private $graphs = ['http://dbpedia.org', 'http://wineagent.tw.rpi.edu/data/wine.rdf'];
		private $prefix_tab = ['foaf' => 'http://xmlns.com/foaf/0.1/',
							   'dbo' => 'http://dbpedia.org/ontology/',
							   'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#'];
							   
		private $db = null;
		
		private function connection() {
			if(!$this->db) {
				$this->db = sparql_connect( "http://dbpedia.org/sparql" );
				if( !$this->db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
				foreach($prefix_tab as $key => $prefix) {
					$this->db->ns($key, $prefix);
				}
			}
		}
		
		private function performQuery($sparql) {
			$result = $this->db->query( $sparql ); 
			if( !$result ) { print $this->db->errno() . ": " . $this->db->error(). "\n"; exit; }
			 
			return $result->fetch_all();
		}
		
		public function getDBPediaInfos($URI) {
			$this->connection();
			$sparql = "select * where {
						{ <".$URI."> rdfs:label ?label. 
							  FILTER (langMatches (lang (?label) , \"en\")) }
						UNION
							{ <".$URI."> dbo:abstract ?text.
							  FILTER (langMatches (lang (?text) , \"en\"))}
						UNION
							{ <".$URI."> foaf:depiction ?photo.}
						UNION
							{ <".$URI."> dbo:wikiPageExternalLink ?pages_liees.}
						}	";
						
			return $this->performQuery($sparql);
		}
		
		public function getWineRecettes($URI){
			$sparql = "select * where {
					      ?obj dbo:ingredient ".$URI.".}";
			return performQuery($sparql);
		}
	}
