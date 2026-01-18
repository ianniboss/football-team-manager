<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /vue/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Football Team Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/global.css">
</head>

<body>
    <nav>
        <a href="/vue/accueil.php" class="logo">Football Team Manager</a>
        <div class="nav-links">
            <a href="/vue/accueil.php">Accueil</a>
            <a href="/controleur/joueur/ObtenirTousLesJoueurs.php">Joueurs</a>
            <a href="/controleur/rencontre/ObtenirToutesLesRencontres.php">Matchs</a>
            <a href="/controleur/stats/AfficherStatistiques.php">Statistiques</a>
            <a href="/controleur/logout.php" onclick="return confirm('Vous allez vous deconnecter');">Déconnexion</a>
        </div>
    </nav>
    <div class="container">