<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Football Team Manager</title>
    <link rel="stylesheet" href="/vue/style/styles.css">
    <style>
        nav {
            background-color: #333;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            padding: 5px 10px;
        }

        nav a:hover {
            background-color: #555;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <nav>
        <a href="/vue/accueil.php">Accueil</a>
        <a href="/controleur/joueur/ObtenirTousLesJoueurs.php">Joueurs</a>
        <a href="/controleur/rencontre/ObtenirToutesLesRencontres.php">Matchs</a>
        <a href="/controleur/stats/AfficherStatistiques.php">Statistiques</a>
        <a href="/controleur/logout.php">Déconnexion</a>
    </nav>
    <div class="container">