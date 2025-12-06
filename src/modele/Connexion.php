// la connexion à la base de données
<?php
class Connexion {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Paramètres de connexion
        $host = '127.0.0.1';
        $db   = 'ftm-projet';
        $user = 'root'; 
        $pass = '';     

        try {
            // Création de l'objet PDO 
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Pattern Singleton pour n'avoir qu'une seule connexion active
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Connexion();
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }
}
?>