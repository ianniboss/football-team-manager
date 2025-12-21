<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';

if (isset($_GET['id_rencontre'])) {
    $id_rencontre = intval($_GET['id_rencontre']);

    // Instantiate DAOs
    $rencontreDAO = new RencontreDAO();
    $joueurDAO = new JoueurDAO();
    $participerDAO = new ParticiperDAO();
    $commentaireDAO = new CommentaireDAO();

    // 2. Get Match Details
    $rencontre = $rencontreDAO->getRencontreById($id_rencontre);

    if (!$rencontre) {
        die("Erreur : Match introuvable.");
    }

    // 3. Get all Active Players (Candidates for the team)
    $tousLesJoueurs = $joueurDAO->getJoueursActifs();

    // 4. Get Current Selection (Players already in the match sheet)
    $feuilleMatchRaw = $participerDAO->getFeuilleMatch($id_rencontre);
    $selectionActuelle = [];
    foreach ($feuilleMatchRaw as $participation) {
        $selectionActuelle[$participation['id_joueur']] = $participation;
    }

    // 5. Check if there's a pending selection from a failed validation
    // If so, use the pending data instead of the database data
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

    // 6. Get comments and stats for each player (for display in selection interface)
    $joueursCommentaires = [];
    $joueursStats = [];
    foreach ($tousLesJoueurs as $joueur) {
        $id = $joueur['id_joueur'];
        // Get last 3 comments for this player
        $allComments = $commentaireDAO->getCommentairesByJoueur($id);
        $joueursCommentaires[$id] = array_slice($allComments, 0, 3);

        // Get stats (evaluations) for this player
        $joueursStats[$id] = $participerDAO->getStatsJoueur($id);
    }

    require __DIR__ . '/../../vue/selection/feuilleMatch.php';

} else {
    header("Location: ../rencontre/ObtenirToutesLesRencontres.php");
    exit;
}
?>