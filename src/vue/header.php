<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Football Team Manager</title>
    <?php
    $basePath = '';
    ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/vue/style/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-image: url('/modele/img/stadiumbackground.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-color: #e8ecef;
            min-height: 100vh;
        }

        nav {
            background-color: #2d3436;
            padding: 0 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            font-style: italic;
            color: #1db988;
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a {
            color: #b2bec3;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .nav-links a:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            nav {
                padding: 0 20px;
                height: auto;
                flex-direction: column;
                gap: 15px;
                padding: 15px 20px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-links a {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">FTM</div>
        <div class="nav-links">
            <a href="<?php echo $basePath; ?>/vue/accueil.php">Accueil</a>
            <a href="<?php echo $basePath; ?>/controleur/joueur/ObtenirTousLesJoueurs.php">Joueurs</a>
            <a href="<?php echo $basePath; ?>/controleur/rencontre/ObtenirToutesLesRencontres.php">Rencontres</a>
            <a href="<?php echo $basePath; ?>/controleur/stats/AfficherStatistiques.php">Statistiques</a>
            <a href="<?php echo $basePath; ?>/controleur/logout.php"
                onclick="return confirm('Vous allez vous deconnecter');">Déconnexion</a>
        </div>
    </nav>
    <div class="container">