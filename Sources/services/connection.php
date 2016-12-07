<?php

class Connexion
{
    private static $_instance;

    private function __construct()
    {
    }

    /* Singleton */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            try {
                $db_config = array();
                $db_config['SGBD'] = 'mysql';
                $db_config['HOST'] = 'localhost';
                $db_config['PORT'] = '';
                $db_config['DB_NAME'] = 'divin';
                $db_config['USER'] = 'root';
                $db_config['PASSWORD'] = 'qvecchio';
                $db_config['OPTIONS'] =
                    array(
                        // Activation des exceptions PDO :
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // Change le fetch mode par défaut sur FETCH_ASSOC ( fetch() retournera un tableau associatif ):
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    );

                self::$_instance = new PDO($db_config['SGBD'] . ':host=' . $db_config['HOST'] . ';port=' . $db_config['PORT'] . ';dbname=' . $db_config['DB_NAME'],
                    $db_config['USER'],
                    $db_config['PASSWORD'],
                    $db_config['OPTIONS']);
                unset($db_config);
            } catch (Exception $e) {
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }
        return self::$_instance;
    }
}