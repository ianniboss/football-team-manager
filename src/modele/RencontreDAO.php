<?php
require_once __DIR__ . '/ConnexionBD.php';

class RencontreDAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = ConnexionBD::getInstance()->getPDO();
    }

    public function getRencontres()
    {
        $sql = "SELECT * FROM rencontre ORDER BY date_rencontre DESC, heure DESC";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRencontresAVenir()
    {
        $sql = "SELECT * FROM rencontre WHERE date_rencontre >= CURDATE() ORDER BY date_rencontre ASC";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRencontreById($id_rencontre)
    {
        $sql = "SELECT * FROM rencontre WHERE id_rencontre = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id' => $id_rencontre));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterRencontre($date, $heure, $adresse, $equipe_adverse, $lieu, $image_stade = null)
    {
        $sql = "INSERT INTO rencontre (date_rencontre, heure, adresse, nom_equipe_adverse, lieu, image_stade) 
                VALUES (:date, :heure, :adresse, :equipe, :lieu, :image_stade)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':date' => $date,
            ':heure' => $heure,
            ':adresse' => $adresse,
            ':equipe' => $equipe_adverse,
            ':lieu' => $lieu,
            ':image_stade' => $image_stade
        ));
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function modifierRencontre($id_rencontre, $date, $heure, $adresse, $equipe_adverse, $lieu, $resultat, $image_stade = null)
    {
        if ($image_stade !== null) {
            $sql = "UPDATE rencontre 
                    SET date_rencontre = :date, heure = :heure, adresse = :adresse, 
                        nom_equipe_adverse = :equipe, lieu = :lieu, resultat = :resultat, image_stade = :image_stade 
                    WHERE id_rencontre = :id";
        } else {
            $sql = "UPDATE rencontre 
                    SET date_rencontre = :date, heure = :heure, adresse = :adresse, 
                        nom_equipe_adverse = :equipe, lieu = :lieu, resultat = :resultat 
                    WHERE id_rencontre = :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = array(
            ':date' => $date,
            ':heure' => $heure,
            ':adresse' => $adresse,
            ':equipe' => $equipe_adverse,
            ':lieu' => $lieu,
            ':resultat' => $resultat,
            ':id' => $id_rencontre
        );

        if ($image_stade !== null) {
            $params[':image_stade'] = $image_stade;
        }

        return $stmt->execute($params);
    }

    public function supprimerRencontre($id_rencontre)
    {
        $sql = "DELETE FROM rencontre WHERE id_rencontre = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_rencontre));
    }
}
?>