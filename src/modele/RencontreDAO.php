<?php
require_once __DIR__ . '/ConnexionBD.php';

class RencontreDAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connexion::getInstance()->getPDO();
    }

    // Récupérer toutes les rencontres (SELECT)
    public function getRencontres()
    {
        $sql = "SELECT * FROM rencontre ORDER BY date_rencontre DESC, heure DESC";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les rencontres à venir (SELECT)
    public function getRencontresAVenir()
    {
        $sql = "SELECT * FROM rencontre WHERE date_rencontre >= CURDATE() ORDER BY date_rencontre ASC";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une rencontre par son ID (SELECT)
    public function getRencontreById($id_rencontre)
    {
        $sql = "SELECT * FROM rencontre WHERE id_rencontre = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id' => $id_rencontre));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter une nouvelle rencontre (INSERT)
    public function ajouterRencontre($date, $heure, $adresse, $equipe_adverse, $lieu)
    {
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

    // Modifier une rencontre (UPDATE)
    public function modifierRencontre($id_rencontre, $date, $heure, $adresse, $equipe_adverse, $lieu, $resultat)
    {
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
            ':resultat' => $resultat,
            ':id' => $id_rencontre
        ));
    }

    // Supprimer une rencontre (DELETE)
    public function supprimerRencontre($id_rencontre)
    {
        $sql = "DELETE FROM rencontre WHERE id_rencontre = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_rencontre));
    }
}
?>