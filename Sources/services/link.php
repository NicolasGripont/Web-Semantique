<?php

/**
* 
*/
class Link 
{
	public $url;

	public $title;

	public $img;

	public $desc;

	function __construct($url, $title, $img, $desc	)
	{
		$this->url = $url;
		$this->title = str_replace("\"", "'", $title);
		$this->img = $img;
		$this->desc = str_replace("\"", "'", $desc);
	}

	function toJSON() {
		return "{\"title\" : \"".$this->title."\", \"url\" : \"".$this->url."\", \"desc\" : \"".$this->desc."\", \"img\" : \"".$this->img."\"}";
	}
}

?>