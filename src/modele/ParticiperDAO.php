<?php
require_once __DIR__ . '/ConnexionBD.php';

class ParticiperDAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connexion::getInstance()->getPDO();
    }

    /**
     * Récupère la feuille de match (SELECT avec jointure)
     */
    public function getFeuilleMatch($id_rencontre)
    {
        $sql = "SELECT p.*, j.nom, j.prenom, j.num_licence, j.poids, j.taille 
                FROM participer p
                JOIN joueur j ON p.id_joueur = j.id_joueur
                WHERE p.id_rencontre = :id_rencontre
                ORDER BY p.titulaire DESC, p.poste ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_rencontre' => $id_rencontre));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un joueur à une feuille de match (INSERT)
     */
    public function ajouterParticipation($id_rencontre, $id_joueur, $poste, $titulaire)
    {
        $sql = "INSERT INTO participer (id_rencontre, id_joueur, poste, titulaire) 
                VALUES (:id_rencontre, :id_joueur, :poste, :titulaire)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(array(
            ':id_rencontre' => $id_rencontre,
            ':id_joueur' => $id_joueur,
            ':poste' => $poste,
            ':titulaire' => $titulaire
        ));
    }

    /**
     * Modifie le poste ou le statut d'un joueur (UPDATE)
     */
    public function modifierParticipation($id_participation, $poste, $titulaire)
    {
        $sql = "UPDATE participer 
                SET poste = :poste, titulaire = :titulaire 
                WHERE id_participation = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':poste' => $poste,
            ':titulaire' => $titulaire,
            ':id' => $id_participation
        ));
    }

    /**
     * Retire un joueur de la feuille de match (DELETE)
     */
    public function supprimerParticipation($id_participation)
    {
        $sql = "DELETE FROM participer WHERE id_participation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_participation));
    }

    /**
     * Enregistre l'évaluation d'un joueur (UPDATE)
     */
    public function noterJoueur($id_participation, $evaluation)
    {
        $sql = "UPDATE participer SET evaluation = :eval WHERE id_participation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':eval' => $evaluation,
            ':id' => $id_participation
        ));
    }

    // -------------------------------------------------------------------------
    // FONCTIONS POUR LES STATISTIQUES (SELECT)
    // -------------------------------------------------------------------------

    /**
     * Récupère les stats globales d'un joueur
     */
    public function getStatsJoueur($id_joueur)
    {
        $sql = "SELECT 
                    COUNT(*) as total_matchs,
                    SUM(CASE WHEN titulaire = 1 THEN 1 ELSE 0 END) as nb_titularisations,
                    SUM(CASE WHEN titulaire = 0 THEN 1 ELSE 0 END) as nb_remplacements,
                    AVG(evaluation) as moyenne_notes
                FROM participer 
                WHERE id_joueur = :id_joueur";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_joueur' => $id_joueur));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le pourcentage de matchs gagnés (SELECT avec jointure)
     */
    public function getPourcentageGagne($id_joueur)
    {
        $sql = "SELECT 
                    COUNT(p.id_rencontre) as total_joues,
                    SUM(CASE WHEN r.resultat = 'Victoire' THEN 1 ELSE 0 END) as nb_victoires
                FROM participer p
                JOIN rencontre r ON p.id_rencontre = r.id_rencontre
                WHERE p.id_joueur = :id_joueur
                AND r.resultat IS NOT NULL";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_joueur' => $id_joueur));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res && $res['total_joues'] > 0) {
            return round(($res['nb_victoires'] / $res['total_joues']) * 100, 2);
        } else {
            return 0;
        }
    }

    /**
     * Trouve le poste préféré du joueur (SELECT)
     */
    public function getPostePrefere($id_joueur)
    {
        $sql = "SELECT poste, COUNT(*) as cnt 
                FROM participer 
                WHERE id_joueur = :id_joueur 
                GROUP BY poste 
                ORDER BY cnt DESC 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_joueur' => $id_joueur));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['poste'] : 'Aucun';
    }

    /**
     * Calcule le nombre de matchs consécutifs joués par le joueur (série en cours).
     * On remonte dans le temps depuis le dernier match joué par l'équipe.
     */
    public function getSerieEnCours($id_joueur) {
        // 1. On récupère TOUS les matchs passés de l'équipe, triés du plus récent au plus ancien
        $sql = "SELECT r.id_rencontre, 
                       (SELECT COUNT(*) FROM participer p WHERE p.id_rencontre = r.id_rencontre AND p.id_joueur = :id_joueur) as a_joue
                FROM rencontre r
                WHERE r.date_rencontre <= CURDATE()
                ORDER BY r.date_rencontre DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_joueur' => $id_joueur));
        $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $serie = 0;
        foreach ($matchs as $match) {
            if ($match['a_joue'] > 0) {
                $serie++;
            } else {
                // Dès qu'on tombe sur un match où il n'a pas joué, la série s'arrête
                break;
            }
        }
        return $serie;
    }
}
?>