<?php
// affiche les details d'un joueur et ses commentaires
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';

// Vérification qu'un ID est bien passé dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $joueurDAO = new JoueurDAO();
    $commentaireDAO = new CommentaireDAO();

    $joueur = $joueurDAO->getJoueurById($id);
    $commentaires = $commentaireDAO->getCommentairesByJoueur($id);

    if ($joueur) {
        require __DIR__ . '/../../vue/joueurs/ficheJoueur.php';
    } else {
        echo "Joueur introuvable.";
    }
} else {
    header("Location: ObtenirTousLesJoueurs.php");
    exit;
}
?>