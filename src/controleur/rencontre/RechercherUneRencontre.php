<?php
// afficher le détail d'un match
// inclus aussi la récupération de la feuille de match
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $rencontreDAO = new RencontreDAO();
    $participerDAO = new ParticiperDAO();

    // Infos du match
    $rencontre = $rencontreDAO->getRencontreById($id);

    // Liste des joueurs convoqués (feuille de match)
    $joueursParticipe = $participerDAO->getFeuilleMatch($id);

    if ($rencontre) {
        $_SESSION['rencontre_detail'] = $rencontre;
        $_SESSION['joueurs_participe'] = $joueursParticipe;
        header("Location: ../../vue/rencontres/detailRencontre.php");
        exit;
    } else {
        echo "Rencontre introuvable.";
    }
} else {
    header("Location: ObtenirToutesLesRencontres.php");
    exit;
}
?>