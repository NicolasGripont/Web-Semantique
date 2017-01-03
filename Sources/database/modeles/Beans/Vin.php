<?php

class Vin 
{
	public $_id;
    public $_name;

    public function __construct()
    {
    }

    /*
    * Getter the $_name container
    * @return name of wine
    */
    public function getName(){
    	return $this->_name;
    }

    /*
    * Setter the $_name container
    * @param string name of wine
    */
    public function setName($name)
    {
    	$this->_name = $name;
    	return $this;
    }
}