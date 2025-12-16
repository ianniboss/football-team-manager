<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.html");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';

if (isset($_GET['id_rencontre'])) {
    $id_rencontre = intval($_GET['id_rencontre']);

    // Instantiate DAOs
    $rencontreDAO = new RencontreDAO();
    $joueurDAO = new JoueurDAO();
    $participerDAO = new ParticiperDAO();

    // 2. Get Match Details
    $rencontre = $rencontreDAO->getRencontreById($id_rencontre);
    
    if (!$rencontre) {
        die("Erreur : Match introuvable.");
    }

    // 3. Get all Active Players (Candidates for the team)
    $tousLesJoueurs = $joueurDAO->getJoueursActifs();

    // 4. Get Current Selection (Players already in the match sheet)
    // We re-index this array by 'id_joueur' to make it easier to check in the View
    $feuilleMatchRaw = $participerDAO->getFeuilleMatch($id_rencontre);
    $selectionActuelle = [];
    foreach ($feuilleMatchRaw as $participation) {
        $selectionActuelle[$participation['id_joueur']] = $participation;
    }

    require __DIR__ . '/../../vue/selection/feuilleMatch.php';

} else {
    header("Location: ../rencontre/ObtenirToutesLesRencontres.php");
    exit;
}
?>