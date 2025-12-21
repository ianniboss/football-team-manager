<?php
// Ajout de nouveaux joueurs, mise à jour des joueurs existants et suppression de ceux qui n'ont pas été vérifiés
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.php");
    exit;
}

require_once __DIR__ . '/../../modele/ParticiperDAO.php';

// titulaires minimum pour un match
define('MIN_TITULAIRES', 11);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_rencontre'])) {

    $id_rencontre = intval($_POST['id_rencontre']);
    $participerDAO = new ParticiperDAO();

    $submittedData = isset($_POST['joueurs']) ? $_POST['joueurs'] : [];

    // nombre de titulaires dans les données soumises
    $titulairesCount = 0;
    foreach ($submittedData as $id_joueur => $data) {
        if (isset($data['selected']) && isset($data['titulaire'])) {
            $titulairesCount++;
        }
    }

    // validation du nombre minimum de titulaires
    if ($titulairesCount < MIN_TITULAIRES) {
        // Stockage des données soumises dans la session pour conserver les modifications
        $_SESSION['pending_selection'] = $submittedData;
        $_SESSION['pending_selection_match'] = $id_rencontre;

        // redirection avec erreur
        header("Location: AfficherSelection.php?id_rencontre=" . $id_rencontre . "&error=min_titulaires&count=" . $titulairesCount);
        exit;
    }

    // suppression des données en attente de validation
    unset($_SESSION['pending_selection']);
    unset($_SESSION['pending_selection_match']);

    // Obtenir les selections existantes pour les comparer
    $existingList = $participerDAO->getFeuilleMatch($id_rencontre);
    $existingIds = [];
    foreach ($existingList as $p) {
        $existingIds[$p['id_joueur']] = $p['id_participation'];
    }

    foreach ($submittedData as $id_joueur => $data) {
        // traitement si le joueur a été sélectionné (case à cocher cochée)
        if (!isset($data['selected'])) {
            // le joueur n'a pas été coché, on passe à la suivante
            continue;
        }

        $poste = htmlspecialchars($data['poste']);
        $est_titulaire = isset($data['titulaire']) ? 1 : 0;

        if (array_key_exists($id_joueur, $existingIds)) {
            $id_participation = $existingIds[$id_joueur];
            $participerDAO->modifierParticipation($id_participation, $poste, $est_titulaire);

            unset($existingIds[$id_joueur]);
        } else {
            $participerDAO->ajouterParticipation($id_rencontre, $id_joueur, $poste, $est_titulaire);
        }
    }

    foreach ($existingIds as $id_joueur => $id_participation) {
        $participerDAO->supprimerParticipation($id_participation);
    }

    header("Location: ../rencontre/RechercherUneRencontre.php?id=" . $id_rencontre . "&success=selection_saved");
    exit;

} else {
    header("Location: ../rencontre/ObtenirToutesLesRencontres.php");
    exit;
}
?>