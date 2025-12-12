<?php
// Ajout de nouveaux joueurs, mise à jour des joueurs existants et suppression de ceux qui n'ont pas été vérifiés
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.html");
    exit;
}

require_once __DIR__ . '/../../modele/ParticiperDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_rencontre'])) {
    
    $id_rencontre = intval($_POST['id_rencontre']);
    $participerDAO = new ParticiperDAO();

    $existingList = $participerDAO->getFeuilleMatch($id_rencontre);
    $existingIds = [];
    foreach ($existingList as $p) {
        $existingIds[$p['id_joueur']] = $p['id_participation'];
    }

    $submittedData = isset($_POST['joueurs']) ? $_POST['joueurs'] : [];

    foreach ($submittedData as $id_joueur => $data) {
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

    header("Location: ../rencontre/RechercherUneRencontre.php?id=" . $id_rencontre);
    exit;

} else {
    header("Location: ../rencontre/ObtenirToutesLesRencontres.php");
    exit;
}
?>