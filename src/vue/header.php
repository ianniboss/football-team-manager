<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Football Team Manager</title>
    <?php
    // Define base path dynamically - works locally and on deployment
    $basePath = '/src';
    ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/vue/style/styles.css">
    <style>
        body {
            background-image: url('<?php echo $basePath; ?>/modele/img/stadiumbackground.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            min-height: 100vh;
        }

        nav {
            background-color: rgba(51, 51, 51, 0.95);
            padding: 15px 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5em;
            font-weight: bold;
            color: #f5a623;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 30px;
            padding: 5px 10px;
        }

        .nav-links a:hover {
            background-color: #555;
            border-radius: 3px;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">FTM</div>
        <div class="nav-links">
            <a href="<?php echo $basePath; ?>/vue/accueil.php">Accueil</a>
            <a href="<?php echo $basePath; ?>/controleur/joueur/ObtenirTousLesJoueurs.php">Joueurs</a>
            <a href="<?php echo $basePath; ?>/controleur/rencontre/ObtenirToutesLesRencontres.php">Matchs</a>
            <a href="<?php echo $basePath; ?>/controleur/stats/AfficherStatistiques.php">Statistiques</a>
            <a href="<?php echo $basePath; ?>/controleur/logout.php"
                onclick="return confirm('Vous êtes sur le point de vous déconnecter');">Déconnexion</a>
        </div>
    </nav>
    <div class="container">