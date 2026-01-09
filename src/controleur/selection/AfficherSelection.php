<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';

if (isset($_GET['id_rencontre'])) {
    $id_rencontre = intval($_GET['id_rencontre']);

    $rencontreDAO = new RencontreDAO();
    $joueurDAO = new JoueurDAO();
    $participerDAO = new ParticiperDAO();
    $commentaireDAO = new CommentaireDAO();
    $rencontre = $rencontreDAO->getRencontreById($id_rencontre);

    if (!$rencontre) {
        die("Erreur : Match introuvable.");
    }

    $tousLesJoueurs = $joueurDAO->getJoueursActifs();

    // 4. Obtenir la sélection actuelle (joueurs déjà inscrits sur la feuille de match)
    $feuilleMatchRaw = $participerDAO->getFeuilleMatch($id_rencontre);
    $selectionActuelle = [];
    foreach ($feuilleMatchRaw as $participation) {
        $selectionActuelle[$participation['id_joueur']] = $participation;
    }

    // 5. Vérifier si il y a une sélection en attente (échec de validation)
// Si oui, utiliser les données en attente au lieu des données de la base de données
    $pendingSelection = null;
    if (
        isset($_SESSION['pending_selection']) &&
        isset($_SESSION['pending_selection_match']) &&
        $_SESSION['pending_selection_match'] == $id_rencontre
    ) {
        $pendingSelection = $_SESSION['pending_selection'];
        // Clear the session data after using it
        unset($_SESSION['pending_selection']);
        unset($_SESSION['pending_selection_match']);
    }

    // 6. Obtenir les commentaires et les statistiques pour chaque joueur (pour afficher l'interface de sélection)
    $joueursCommentaires = [];
    $joueursStats = [];
    foreach ($tousLesJoueurs as $joueur) {
        $id = $joueur['id_joueur'];
        // Obtenir les 3 derniers commentaires pour ce joueur
        $allComments = $commentaireDAO->getCommentairesByJoueur($id);
        $joueursCommentaires[$id] = array_slice($allComments, 0, 3);

        // Obtenir les statistiques (évaluations) pour ce joueur
        $joueursStats[$id] = $participerDAO->getStatsJoueur($id);
    }

    require __DIR__ . '/../../vue/selection/feuilleMatch.php';

} else {
    header("Location: ../rencontre/ObtenirToutesLesRencontres.php");
    exit;
}
?>