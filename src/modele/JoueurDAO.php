<?php
require_once __DIR__ . '/ConnexionBD.php';

class JoueurDAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = ConnexionBD::getInstance()->getPDO();
    }

    public function getJoueurs()
    {
        $req = $this->pdo->query('SELECT * FROM joueur ORDER BY nom, prenom');
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterJoueur($nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $image = null)
    {
        $sql = "INSERT INTO joueur (nom, prenom, num_licence, date_naissance, taille, poids, statut, image) 
                VALUES (:nom, :prenom, :num_licence, :date_naissance, :taille, :poids, :statut, :image)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':num_licence' => $num_licence,
            ':date_naissance' => $date_naissance,
            ':taille' => $taille,
            ':poids' => $poids,
            ':statut' => $statut,
            ':image' => $image
        ));
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function getJoueurById($id_joueur)
    {
        $sql = "SELECT * FROM joueur WHERE id_joueur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id' => $id_joueur));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierJoueur($id_joueur, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $image = null)
    {
        if ($image !== null) {
            $sql = "UPDATE joueur 
                    SET nom = :nom, prenom = :prenom, num_licence = :licence, 
                        date_naissance = :naissance, taille = :taille, poids = :poids, statut = :statut, image = :image 
                    WHERE id_joueur = :id";
        } else {
            $sql = "UPDATE joueur 
                    SET nom = :nom, prenom = :prenom, num_licence = :licence, 
                        date_naissance = :naissance, taille = :taille, poids = :poids, statut = :statut 
                    WHERE id_joueur = :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':licence' => $num_licence,
            ':naissance' => $date_naissance,
            ':taille' => $taille,
            ':poids' => $poids,
            ':statut' => $statut,
            ':id' => $id_joueur
        );
        
        if ($image !== null) {
            $params[':image'] = $image;
        }
        
        return $stmt->execute($params);
    }

    public function supprimerJoueur($id_joueur)
    {
        $sql = "DELETE FROM joueur WHERE id_joueur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_joueur));
    }

    public function getJoueursActifs()
    {
        $sql = "SELECT * FROM joueur WHERE statut = 'Actif' ORDER BY nom";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>