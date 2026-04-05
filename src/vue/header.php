<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <script>
        // Protection client-side : redirection si pas de token
        if (!localStorage.getItem('token')) {
            window.location.href = "/ftm/vue/index.php";
        }
    </script>
    <title>Football Team Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/ftm/css/global.css">
</head>

<body>
    <nav>
        <a href="/ftm/vue/accueil.php" class="logo">Football Team Manager</a>
        <div class="nav-links">
            <a href="/ftm/vue/accueil.php">Accueil</a>
            <a href="/ftm/vue/joueurs/listeJoueurs.php">Joueurs</a>
            <a href="/ftm/vue/rencontres/listeRencontres.php">Matchs</a>
            <a href="/ftm/vue/stats/index.php">Statistiques</a>
            <a href="#" id="logoutBtn">Déconnexion</a>
        </div>
    </nav>
    <script>
        document.getElementById('logoutBtn')?.addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Voulez-vous vous déconnecter ?')) {
                localStorage.removeItem('token');
                window.location.href = "/ftm/vue/index.php";
            }
        });
    </script>
    <div class="container">