
<?php
require_once __DIR__ . '/connexionBD.php';

class DAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = ConnexionBD::getInstance()->getPDO();
    }

    public function userExists($login)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE login = :login");
        if (!$stmt) {
            throw new Exception("Erreur lors de la préparation de la requête userExists.");
        }
        if (!$stmt->execute(['login' => $login])) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur lors de l'exécution de la requête userExists : " . $errorInfo[2]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function createUser($login, $password, $role = 'user')
    {
        $stmt = $this->pdo->prepare("INSERT INTO user (login, password, role) VALUES (:login, :password, :role)");
        if (!$stmt) {
            throw new Exception("Erreur lors de la préparation de la requête createUser.");
        }
        $execution = $stmt->execute(['login' => $login, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => $role]);
        if (!$execution) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur lors de l'exécution de la requête createUser : " . $errorInfo[2]);
        }
        return $execution;
    }

    public function verifyUser($login, $password)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM user WHERE login = :login");
        if (!$stmt) {
            throw new Exception("Erreur lors de la préparation de la requête verifyUser.");
        }
        if (!$stmt->execute(['login' => $login])) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur lors de l'exécution de la requête verifyUser : " . $errorInfo[2]);
        }
        $hashedPassword = $stmt->fetchColumn();
        return password_verify($password, $hashedPassword);
    }

    public function getUserRole($login)
    {
        $stmt = $this->pdo->prepare("SELECT role FROM user WHERE login = :login");
        if (!$stmt) {
            throw new Exception("Erreur lors de la préparation de la requête getUserRole.");
        }
        if (!$stmt->execute(['login' => $login])) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur lors de l'exécution de la requête getUserRole : " . $errorInfo[2]);
        }
        return $stmt->fetchColumn();
    }
}
