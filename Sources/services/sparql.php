<?php
require_once( "sparqllib.php" );

$graphs = ['http://dbpedia.org', 'http://wineagent.tw.rpi.edu/data/wine.rdf'];
$prefix_tab = ['foaf' => 'http://xmlns.com/foaf/0.1/',
			   'dbo' => 'http://dbpedia.org/ontology/',
			   'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#'];

if(isset($_GET['query']) && $_GET['query'] != '') {
	 
	$db = sparql_connect( "http://dbpedia.org/sparql" );
	if( !$db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
	foreach($prefix_tab as $key => $prefix) {
		$db->ns($key, $prefix);
	}

	//EXEMPLE DE TEST
	/*$sparql = "select * where {
	{ <http://dbpedia.org/resource/Bordeaux> rdfs:label ?label. 
          FILTER (langMatches (lang (?label) , \"en\")) }
    UNION
	{ <http://dbpedia.org/resource/Bordeaux> dbo:abstract ?text.
          FILTER (langMatches (lang (?text) , \"en\"))}
    UNION
	{ <http://dbpedia.org/resource/Bordeaux> foaf:depiction ?photo.}
    UNION
        {<http://dbpedia.org/resource/Bordeaux> dbo:wikiPageExternalLink ?pages_liees.}
	}	";*/

	$sparql = "select * where {
	{ ".$_GET['query']."> rdfs:label ?label. 
          FILTER (langMatches (lang (?label) , \"en\")) }
    UNION
	{ ".$_GET['query']."> dbo:abstract ?text.
          FILTER (langMatches (lang (?text) , \"en\"))}
    UNION
	{ ".$_GET['query']."> foaf:depiction ?photo.}
    UNION
        {".$_GET['query']."> dbo:wikiPageExternalLink ?pages_liees.}
	}	";

	$result = $db->query( $sparql ); 
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
	 
	$return = $result->fetch_all();
	
	$json_return = json_encode($return);
	echo '<pre>';
	print_r($json_return);
	echo '</pre>';
	 

}
