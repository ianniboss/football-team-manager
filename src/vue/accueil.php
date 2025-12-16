<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.html");
    exit;
}

require_once __DIR__ . '/header.php';
?>

<h2>Bienvenue <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>
<p>Vous êtes connecté.</p>

</div>
</body>

</html>