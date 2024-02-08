<?php
namespace combats\classes;

class dbConnect
{
    public $db;
    private static $instance = null;

    public function __construct( $dsn, $login, $password )
    {
        // Connexion à  la base de données
        try
        {
            $this->db = new \PDO($dsn, $login, $password);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING); 
        }
        catch(\Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }   
    }

    public static function getDb($dsn, $login, $password): self
    {
        if( is_null( self::$instance ) ) {
            self::$instance = new dbConnect($dsn, $login, $password);
        }
        return self::$instance;
    }
}
