<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Football Team Manager</title>
    <link rel="stylesheet" href="./style/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Football Team Manager</div>
        <ul class="nav-links">
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Joueurs</a></li>
            <li><a href="#">Matchs</a></li>
            <li><a href="#">Statistiques</a></li>
        </ul>
    </nav>
    <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>
    <p>Vous êtes connecté.</p>
    <a href="../controleur/logout.php">Se déconnecter</a>
</body>
</html>
