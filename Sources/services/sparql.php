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
		private $sparqlorg = null;
		
		
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
		
		private function connection_dbpedia() {
			if(!$this->db) {
				$this->db = sparql_connect( "http://fr.dbpedia.org/sparql" );
				if( !$this->db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
				foreach($this->prefix_tab as $key => $prefix) {
					$this->db->ns($key, $prefix);
				}
			}
		}
		
		private function connection_sparqlorg() {
			if(!$this->sparqlorg) {
				$this->sparqlorg = sparql_connect( "http://www.sparql.org/sparql" );
				if( !$this->sparqlorg ) { print $sparqlorg->errno() . ": " . $sparqlorg->error(). "\n"; exit; }
				foreach($this->prefix_tab as $key => $prefix) {
					$this->sparqlorg->ns($key, $prefix);
				}
			}
		}
		
		private function performQuery_dbpedia($sparql) {
			$result = $this->db->query( $sparql );
			if( !$result ) { print $this->db->errno() . ": " . $this->db->error(). "\n"; exit; }
			return $result->fetch_all();
		}
		
		private function performQuery_sparqlorg($sparql) {
			$result = $this->sparqlorg->query( $sparql ); 
			if( !$result ) { print $this->sparqlorg->errno() . ": " . $this->sparqlorg->error(). "\n"; exit; }
			 
			return $result->fetch_all();
		}
		
		/*public function getDBPediaInfos($URI) {
			$this->connection_dbpedia();
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
			return $this->performQuery_dbpedia($sparql);
		}*/
		
		public function getInfinivinRDFInfos($key_word) {
			$this->connection_sparqlorg();
			$str_kw = "[" . strtoupper($key_word[0]) . strtolower($key_word[0]) . "]" . substr($key_word, 1, strlen($key_word)-1);
			$sparql = "select *
						FROM <http://divin4if.alwaysdata.net/rdf/infinivin_XML_RDF.rdf>
						where {
						  ?uri nsWine:Label ?label. 
						  ?uri nsWine:KeyWords ?k_w.
						  ?uri nsWine:PictureSrc ?picture.
						  ?uri nsWine:Description ?desc.
							  FILTER (( regex(?label, '.*".$str_kw.".*')) || ( regex(?k_w, '.*".$str_kw.".*')))
					   }";
			//return json_encode($sparql);
			$temp = $this->performQuery_sparqlorg($sparql);
			$return = array();
			foreach($temp as $key => $value) {
				$return[] = $value;
			}
			return $return;
		}
		
		public function getInfinivinRDFDomain($key_word) {
			$this->connection_sparqlorg();
			$str_kw = "[" . strtoupper($key_word[0]) . strtolower($key_word[0]) . "]" . substr($key_word, 1, strlen($key_word)-1);
			$sparql = "select distinct ?domain ?domain_info ?domain_picture
						FROM <http://divin4if.alwaysdata.net/rdf/infinivin_XML_RDF.rdf>
						where {
							?uri nsWine:Label ?label.
							?uri nsWine:Domain ?domain.
							?uri nsWine:InfoDomain ?domain_info.
							?uri nsWine:DomainPicture ?domain_picture
							  FILTER ((( regex(?label, '.*".$str_kw.".*')) || ( regex(?k_w, '.*".$str_kw.".*')))&& ?domain != '' && ?domain_picture != '' && ?domain_info != '')
					   }";
			//return json_encode($sparql);
			$temp = $this->performQuery_sparqlorg($sparql);
			$return = array();
			foreach($temp as $key => $value) {
				$return[] = $value;
			}
			return $return;
		}
	}
