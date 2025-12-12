<?php
session_start();
// pour changer rapidement le statut (Actif/Blessé/etc.), peut-être via un petit formulaire ou un bouton dans la liste.
// Vérification de sécurité
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/JoueurDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_joueur'], $_POST['nouveau_statut'])) {
    $dao = new JoueurDAO();
    $id = $_POST['id_joueur'];

    // On récupère d'abord les anciennes infos car la méthode modifierJoueur demande tout
    // (Alternative : créer une méthode spécifique updateStatut dans le DAO, ce serait mieux)
    $joueur = $dao->getJoueurById($id);

    if ($joueur) {
        $nouveau_statut = $_POST['nouveau_statut'];

        $dao->modifierJoueur(
            $id,
            $joueur['nom'],
            $joueur['prenom'],
            $joueur['num_licence'],
            $joueur['date_naissance'],
            $joueur['taille'],
            $joueur['poids'],
            $nouveau_statut // Seul le statut change
        );
    }

    // Redirection vers la page précédente (referer) ou la liste
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: ObtenirTousLesJoueurs.php");
    }
    exit;
}
?>