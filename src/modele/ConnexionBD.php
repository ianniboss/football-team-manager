<?php
class ConnexionBD
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        require_once(__DIR__ . "/../../../config.php");
        $host = HOST;
        $db = DB;
        $user = USER;
        $pass = PASS;

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ConnexionBD();
        }
        return self::$instance;
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
