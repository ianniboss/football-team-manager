<?php
// retirer une note
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.php");
    exit;
}

require_once __DIR__ . '/../../modele/CommentaireDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commentaire'])) {

    $id_commentaire = intval($_POST['id_commentaire']);

    $id_joueur = isset($_POST['id_joueur']) ? intval($_POST['id_joueur']) : null;

    $dao = new CommentaireDAO();
    $dao->supprimerCommentaire($id_commentaire);

    if ($id_joueur) {
        header("Location: ../joueur/ObtenirUnJoueur.php?id=" . $id_joueur);
    } else {
        header("Location: ../joueur/ObtenirTousLesJoueurs.php");
    }
    exit;
} else {
    header("Location: ../joueur/ObtenirTousLesJoueurs.php");
    exit;
}
?>