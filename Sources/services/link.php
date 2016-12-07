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
		$this->title = $title;
		$this->img = $img;
		$this->desc = $desc;
	}

	function toJSON() {
		return "link : {title : \"".$this->title."\", url : \"".$this->url."\", desc : \"".$this->desc."\", img : \"".$this->img."\"}";
	}

}

?>