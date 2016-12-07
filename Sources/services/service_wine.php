<?php
require_once(__DIR__ . '/../database/modeles/DAO/VinDAO.php');
class VinService
{
    public function __construct()
    {
    }
    public function RecoverNamesWines() {
        $VinDAO = new VinDAO();
		return $VinDAO->getAllNameWines();
    }
    public function AddNewNameWine(string $name) {
    	$VinDAO = new VinDAO();
    	return $VinDAO->insertNewNameOfWine($name);
    }
}
?>