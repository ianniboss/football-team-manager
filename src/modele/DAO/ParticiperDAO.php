<?php
require_once __DIR__ . '/../../modele/Connexion.php';

class ParticiperDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance()->getPDO();
    }

    /**
     * Récupère la liste des joueurs participant à un match donné (la feuille de match).
     * Jointure avec la table 'joueur' pour afficher les noms.
     */
    public function getFeuilleMatch($id_rencontre) {
        // On récupère les infos de participation ET les infos du joueur
        $sql = "SELECT p.*, j.nom, j.prenom, j.num_licence, j.poids, j.taille 
                FROM participer p
                JOIN joueur j ON p.id_joueur = j.id_joueur
                WHERE p.id_rencontre = :id_rencontre
                ORDER BY p.titulaire DESC, p.poste ASC"; // Les titulaires en premier
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(':id_rencontre' => $id_rencontre));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un joueur à une feuille de match.
     */
    public function ajouterParticipation($id_rencontre, $id_joueur, $poste, $titulaire) {
        $sql = "INSERT INTO participer (id_rencontre, id_joueur, poste, titulaire) 
                VALUES (:id_rencontre, :id_joueur, :poste, :titulaire)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // titulaire est un booléen ou tinyint (1 ou 0)
        return $stmt->execute(array(
            ':id_rencontre' => $id_rencontre,
            ':id_joueur' => $id_joueur,
            ':poste' => $poste,
            ':titulaire' => $titulaire
        ));
    }

    /**
     * Modifie le poste ou le statut (titulaire/remplaçant) d'un joueur pour un match.
     */
    public function modifierParticipation($id_participation, $poste, $titulaire) {
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
     * Retire un joueur de la feuille de match.
     */
    public function supprimerParticipation($id_participation) {
        $sql = "DELETE FROM participer WHERE id_participation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(':id' => $id_participation));
    }

    /**
     * Enregistre l'évaluation (note de 1 à 5) d'un joueur après le match.
     */
    public function noterJoueur($id_participation, $evaluation) {
        $sql = "UPDATE participer SET evaluation = :eval WHERE id_participation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':eval' => $evaluation,
            ':id' => $id_participation
        ));
    }

    // -------------------------------------------------------------------------
    // FONCTIONS POUR LES STATISTIQUES
    // -------------------------------------------------------------------------

    /**
     * Récupère les stats globales d'un joueur :
     * - Nombre de titularisations
     * - Nombre de remplacements
     * - Moyenne des évaluations
     */
    public function getStatsJoueur($id_joueur) {
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
     * Calcule le pourcentage de matchs gagnés auxquels le joueur a participé.
     * Nécessite une jointure avec la table 'rencontre' pour voir le résultat.
     */
    public function getPourcentageGagne($id_joueur) {
        $sql = "SELECT 
                    COUNT(p.id_rencontre) as total_joues,
                    SUM(CASE WHEN r.resultat = 'Victoire' THEN 1 ELSE 0 END) as nb_victoires
                FROM participer p
                JOIN rencontre r ON p.id_rencontre = r.id_rencontre
                WHERE p.id_joueur = :id_joueur
                AND r.resultat IS NOT NULL"; // On ne compte que les matchs terminés
        
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
     * Trouve le poste préféré du joueur (celui où il a joué le plus souvent).
     */
    public function getPostePrefere($id_joueur) {
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
}
?>