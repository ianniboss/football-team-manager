<?php
require_once __DIR__ . '/../../modele/Connexion.php'; // Adaptation du chemin selon votre structure

class JoueurDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance()->getPDO();
    }

    // Récupérer tous les joueurs (pour la liste)
    public function getJoueurs() {
        $req = $this->pdo->query('SELECT * FROM joueur ORDER BY nom, prenom');
        return $req->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Ajouter un joueur
    public function ajouterJoueur($nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut) {
        $sql = "INSERT INTO joueur (nom, prenom, num_licence, date_naissance, taille, poids, statut) 
                VALUES (:nom, :prenom, :num_licence, :date_naissance, :taille, :poids, :statut)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Exécution avec le tableau associatif
        return $stmt->execute(array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':num_licence' => $num_licence,
            ':date_naissance' => $date_naissance,
            ':taille' => $taille,
            ':poids' => $poids,
            ':statut' => $statut
        ));
    }

    // Récupérer un joueur par son ID (pour pré-remplir le formulaire de modification)
    public function getJoueurById($id_joueur) {
        $sql = "SELECT * FROM joueur WHERE id_joueur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id' => $id_joueur));
        
        // fetch pour une seule ligne (page 73)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modifier un joueur
    public function modifierJoueur($id_joueur, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut) {
        // Modification d'un enregistrement
        $sql = "UPDATE joueur 
                SET nom = :nom, prenom = :prenom, num_licence = :licence, 
                    date_naissance = :naissance, taille = :taille, poids = :poids, statut = :statut 
                WHERE id_joueur = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':licence' => $num_licence,
            ':naissance' => $date_naissance,
            ':taille' => $taille,
            ':poids' => $poids,
            ':statut' => $statut,
            ':id' => $id_joueur
        ));
    }

    // Supprimer un joueur
    public function supprimerJoueur($id_joueur) {
        // Cf. Page 79 du cours : Suppression
        $sql = "DELETE FROM joueur WHERE id_joueur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_joueur));
    }
    
    // Fonction spécifique pour la liste des joueurs actifs (pour la sélection de match)
    public function getJoueursActifs() {
        $sql = "SELECT * FROM joueur WHERE statut = 'Actif' ORDER BY nom";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>