<?php
// récupère le texte envoyé depuis la page "Détail Joueur" et l'enregistre via la DAO
session_start();

// Sécurité : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.html");
    exit;
}

require_once __DIR__ . '/../../modele/CommentaireDAO.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification que les champs requis sont présents
    if (isset($_POST['id_joueur']) && !empty($_POST['commentaire'])) {

        $id_joueur = intval($_POST['id_joueur']);
        // Protection XSS : on neutralise les balises HTML potentielles
        $contenu = htmlspecialchars($_POST['commentaire']);
        $date = date('Y-m-d'); // Date du jour

        $dao = new CommentaireDAO();
        $dao->ajouterCommentaire($id_joueur, $contenu, $date);

        // Redirection vers la page du joueur pour voir le commentaire ajouté
        // On utilise le contrôleur "ObtenirUnJoueur"
        header("Location: ../joueur/ObtenirUnJoueur.php?id=" . $id_joueur);
        exit;
    } else {
        echo "Erreur : Le commentaire ne peut pas être vide.";
    }
} else {
    // Si on essaie d'accéder à cette page sans POST, on renvoie vers la liste des joueurs
    header("Location: ../joueur/ObtenirTousLesJoueurs.php");
    exit;
}
?>