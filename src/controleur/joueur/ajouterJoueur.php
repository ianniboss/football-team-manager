<?php
session_start();
// afficher le formulaire vide et enregistre le joueur dans le bd
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/JoueurDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Traitement du formulaire ---
    $dao = new JoueurDAO();

    // Nettoyage des entrées
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $licence = htmlspecialchars($_POST['num_licence']);
    $date_naissance = $_POST['date_naissance'];
    $taille = $_POST['taille'];
    $poids = $_POST['poids'];
    $statut = $_POST['statut'];

    // Appel au DAO
    $dao->ajouterJoueur($nom, $prenom, $licence, $date_naissance, $taille, $poids, $statut);

    // Redirection vers la liste
    header("Location: ObtenirTousLesJoueurs.php");
    exit;

} else {
    // --- Affichage du formulaire ---
    require __DIR__ . '/../../vue/joueurs/ajouterJoueur.php';
}
?>