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
    <title>Page protégée</title>
</head>
<body>
    <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>
    <p>Vous êtes connecté.</p>
    <a href="../controleur/logout.php">Se déconnecter</a>
</body>
</html>
