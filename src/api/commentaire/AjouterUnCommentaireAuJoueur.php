<?php
// récupère le texte envoyé depuis la page "Détail Joueur" et l'enregistre via le DAO
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/CommentaireDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_joueur']) && !empty($_POST['commentaire'])) {

        $id_joueur = intval($_POST['id_joueur']);
        $contenu = htmlspecialchars($_POST['commentaire']);
        $date = date('Y-m-d'); // Date du jour

        $dao = new CommentaireDAO();
        $dao->ajouterCommentaire($id_joueur, $contenu, $date);


        header("Location: ../joueur/ObtenirUnJoueur.php?id=" . $id_joueur);
        exit;
    } else {
        echo "Erreur : Le commentaire ne peut pas être vide.";
    }
} else {
    header("Location: ../joueur/ObtenirTousLesJoueurs.php");
    exit;
}
?>