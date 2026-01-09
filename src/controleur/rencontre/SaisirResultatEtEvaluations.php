<?php
// pour "saisir le résultat qui sera saisi une fois le match terminé" 
// et "évaluer la performance de chaque joueur"
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';

$rencontreDAO = new RencontreDAO();
$participerDAO = new ParticiperDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Mise à jour du résultat du match
    $id_rencontre = $_POST['id_rencontre'];
    $resultat = $_POST['resultat']; // Victoire, Defaite, Nul

    $matchActuel = $rencontreDAO->getRencontreById($id_rencontre);

    if ($matchActuel) {
        $rencontreDAO->modifierRencontre(
            $id_rencontre,
            $matchActuel['date_rencontre'],
            $matchActuel['heure'],
            $matchActuel['adresse'],
            $matchActuel['nom_equipe_adverse'],
            $matchActuel['lieu'],
            $resultat
        );
    }

    // 2. Mise à jour des évaluations des joueurs
    if (isset($_POST['evaluations']) && is_array($_POST['evaluations'])) {
        foreach ($_POST['evaluations'] as $id_participation => $note) {
            if ($note !== "" && is_numeric($note)) {
                $participerDAO->noterJoueur($id_participation, intval($note));
            }
        }
    }

    header("Location: RechercherUneRencontre.php?id=" . $id_rencontre);
    exit;

} elseif (isset($_GET['id'])) {
    // Affichage du formulaire de saisie des résultats
    $id = $_GET['id'];
    $rencontre = $rencontreDAO->getRencontreById($id);

    // On ne note que les joueurs présents sur la feuille de match
    $joueursFeuille = $participerDAO->getFeuilleMatch($id);

    require __DIR__ . '/../../vue/rencontres/formResultat.php';
}
?>