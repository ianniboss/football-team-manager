<?php
require_once __DIR__ . '/../../modele/Connexion.php';
// permettre de gérer les matchs (ajout, modification du résultat, listes, etc.).
class RencontreDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance()->getPDO();
    }

    // Récupérer toutes les rencontres (pour l'historique et l'admin)
    public function getRencontres() {
        $sql = "SELECT * FROM rencontre ORDER BY date_rencontre DESC, heure DESC";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer uniquement les matchs à venir (pour la sélection des feuilles de match)
    public function getRencontresAVenir() {
        // On compare la date du match à la date actuelle (CURDATE)
        $sql = "SELECT * FROM rencontre WHERE date_rencontre >= CURDATE() ORDER BY date_rencontre ASC";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une rencontre par son ID
    public function getRencontreById($id_rencontre) {
        $sql = "SELECT * FROM rencontre WHERE id_rencontre = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id' => $id_rencontre));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter une nouvelle rencontre
    public function ajouterRencontre($date, $heure, $adresse, $equipe_adverse, $lieu) {
        // Note: Le résultat est NULL à la création
        $sql = "INSERT INTO rencontre (date_rencontre, heure, adresse, nom_equipe_adverse, lieu) 
                VALUES (:date, :heure, :adresse, :equipe, :lieu)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':date' => $date,
            ':heure' => $heure,
            ':adresse' => $adresse,
            ':equipe' => $equipe_adverse,
            ':lieu' => $lieu
        ));
    }

    // Modifier une rencontre (y compris saisir le résultat)
    public function modifierRencontre($id_rencontre, $date, $heure, $adresse, $equipe_adverse, $lieu, $resultat) {
        $sql = "UPDATE rencontre 
                SET date_rencontre = :date, heure = :heure, adresse = :adresse, 
                    nom_equipe_adverse = :equipe, lieu = :lieu, resultat = :resultat 
                WHERE id_rencontre = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':date' => $date,
            ':heure' => $heure,
            ':adresse' => $adresse,
            ':equipe' => $equipe_adverse,
            ':lieu' => $lieu,
            ':resultat' => $resultat, // Peut être 'Victoire', 'Defaite', 'Nul' ou NULL
            ':id' => $id_rencontre
        ));
    }

    // Supprimer une rencontre
    public function supprimerRencontre($id_rencontre) {
        // Attention : il faudra peut-être supprimer les participations liées avant (ou utiliser ON DELETE CASCADE en SQL)
        $sql = "DELETE FROM rencontre WHERE id_rencontre = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_rencontre));
    }
}
?>