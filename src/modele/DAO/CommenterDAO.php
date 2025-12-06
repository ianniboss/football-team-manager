<?php
require_once __DIR__ . '/../../modele/Connexion.php';

class CommentaireDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance()->getPDO();
    }

    // Récupérer tous les commentaires d'un joueur spécifique
    public function getCommentairesByJoueur($id_joueur) {
        $sql = "SELECT * FROM commentaire WHERE id_joueur = :id_joueur ORDER BY date_commentaire DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_joueur' => $id_joueur));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un commentaire
    public function ajouterCommentaire($id_joueur, $contenu, $date) {
        $sql = "INSERT INTO commentaire (id_joueur, commentaire, date_commentaire) 
                VALUES (:id_joueur, :contenu, :date)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':id_joueur' => $id_joueur,
            ':contenu' => $contenu,
            ':date' => $date
        ));
    }

    // Supprimer un commentaire
    public function supprimerCommentaire($id_commentaire) {
        $sql = "DELETE FROM commentaire WHERE id_commentaire = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_commentaire));
    }
}
?>