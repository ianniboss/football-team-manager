<?php
// Ajout de nouveaux joueurs, mise à jour des joueurs existants et suppression de ceux qui n'ont pas été vérifiés
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.html");
    exit;
}

require_once __DIR__ . '/../../modele/ParticiperDAO.php';

// Minimum number of titulaires required (11 for football)
define('MIN_TITULAIRES', 11);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_rencontre'])) {

    $id_rencontre = intval($_POST['id_rencontre']);
    $participerDAO = new ParticiperDAO();

    $submittedData = isset($_POST['joueurs']) ? $_POST['joueurs'] : [];

    // Count the number of titulaires in the submitted data
    $titulairesCount = 0;
    foreach ($submittedData as $id_joueur => $data) {
        if (isset($data['selected']) && isset($data['titulaire'])) {
            $titulairesCount++;
        }
    }

    // Validate minimum titulaires
    if ($titulairesCount < MIN_TITULAIRES) {
        // Store submitted data in session so user changes are preserved
        $_SESSION['pending_selection'] = $submittedData;
        $_SESSION['pending_selection_match'] = $id_rencontre;

        // Redirect back with error
        header("Location: AfficherSelection.php?id_rencontre=" . $id_rencontre . "&error=min_titulaires&count=" . $titulairesCount);
        exit;
    }

    // Clear any pending selection from session (validation passed)
    unset($_SESSION['pending_selection']);
    unset($_SESSION['pending_selection_match']);

    // Get existing selections to compare
    $existingList = $participerDAO->getFeuilleMatch($id_rencontre);
    $existingIds = [];
    foreach ($existingList as $p) {
        $existingIds[$p['id_joueur']] = $p['id_participation'];
    }

    foreach ($submittedData as $id_joueur => $data) {
        // Only process if the player was selected (checkbox checked)
        if (!isset($data['selected'])) {
            // Player was not checked, skip adding but we'll handle removal later
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