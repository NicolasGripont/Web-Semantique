<?php

/**
* 
*/
class Link 
{
	public $url;

	public $title;

	public $desc;

	function __construct($url, $title, $desc)
	{
		$this->url = $url;
		$this->title = str_replace("\"", "'", $title);
		$this->desc = str_replace("\"", "'", $desc);
	}

	function toJSON() {
		return "{\"title\" : \"".$this->title."\", \"url\" : \"".$this->url."\", \"desc\" : \"".$this->desc."\"}";
	}
}

?>