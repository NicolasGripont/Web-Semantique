<?php
	require_once( "sparqllib.php" );
	
	class Sparql_engine {
		public function __construct()
    	{
    	}
		
		private $graphs = ['http://dbpedia.org', 'http://wineagent.tw.rpi.edu/data/wine.rdf'];
		private $prefix_tab = ['foaf' => 'http://xmlns.com/foaf/0.1/',
							   'dbo' => 'http://dbpedia.org/ontology/',
							   'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
							   'nsWine' => 'http://divin.com/wine#'];
							   
		private $db = null;
		
		private function connection() {
			if(!$this->db) {
				$this->db = sparql_connect( "http://dbpedia.org/sparql" );
				if( !$this->db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
				foreach($this->prefix_tab as $key => $prefix) {
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
							{ <".$URI."> rdfs:comment ?text.
							  FILTER (langMatches (lang (?text) , \"en\"))}
						UNION
							{ <".$URI."> foaf:depiction ?photo.}
						UNION
							{ <".$URI."> foaf:isPrimaryTopicOf ?wiki.}
						UNION
							{ <".$URI."> dbo:wikiPageExternalLink ?pages_liees.}
						}	";
						
			return $this->performQuery($sparql);
		}
		
		public function getInfinivinRDFInfos($key_word) {
			$this->connection();
			$sparql = "select *
						FROM <http://divin4if.alwaysdata.net/rdf/test1.ttl>
						where {
						  ?uri nsWine:Label ?label. 
						  ?uri nsWine:KeyWords ?k_w.
						  ?uri nsWine:PictureSrc ?picture.
						  ?uri nsWine:Description ?desc.
							  FILTER (( regex(?label, '.*".$key_word.".*')) || ( regex(?k_w, '.*".$key_word.".*')))
					   }";
			//return json_encode($sparql);
			return $this->performQuery($sparql);
		}
		
		public function getWineRecettes($URI){
			$sparql = "select * where {
					      ?obj dbo:ingredient ".$URI.".}";
			return performQuery($sparql);
		}
	}
