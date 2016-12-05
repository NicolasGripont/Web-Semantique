<?php
require_once(__DIR__ . '/../../modeles/Beans/Vin.php');
require_once(__DIR__ . '/../../modeles/Services/connexion.php');


class VinDAO
{

	/**
     * @var PDO: Variable de connexion à la base de données.
     */
    private static $connexion;

    public function __construct()
    {
        
    }

    /**
    * @desc: Méthode qui retourne tous les éléments de la table divin
    * @return array|null
    */
    public function getAllNameWines()
    {

    	//On instancie les variables static si ce n'est pas déjà fait
        if (!isset(self::$connexion)) {
            self::$connexion = Connexion::getInstance();
        }

        $sql = "SELECT * FROM Wine";

        $requete = self::$connexion->prepare($sql);

        $requete->execute();
        while ($row = $requete->fetch()) {
        	
        	$vin = new Vin();
        	$vin->setName(utf8_encode($row["name"]));
    		$donnees[]=$vin;
  		}

        return $donnees;
    }

    public function insertNewNameOfWine(string $name)
    {
    	//On instancie les variables static si ce n'est pas déjà fait
        if (!isset(self::$connexion)) {
            self::$connexion = Connexion::getInstance();
        }

        //verifaction si present dans la BDD
         $sql = 'SELECT * FROM Wine where name = "'.$name.'"';

        $requete = self::$connexion->prepare($sql);

        $requete->execute();
        if($requete->fetch()) 
        {
        	return false;
        }
        else {

        	//Ajoute dans la BDD
	        $sql = 'INSERT INTO Wine (name) VALUES ("'.$name.'")';

	        $requete = self::$connexion->prepare($sql);

	        $requete->execute();

        return true;
        }	
    }
}