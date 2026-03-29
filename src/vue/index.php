<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Football Team Manager</title>
    <meta name="description" content="Connectez-vous à Football Team Manager pour gérer votre équipe de football.">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/ftm/css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="../modele/img/logoFTM.png" alt="Logo Football Team Manager">
                <h1 class="app-title">Football Team Manager</h1>
                <p class="app-subtitle">Gérez votre équipe</p>
            </div>

            <form class="login-form" action="../controleur/login.php" method="POST">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" placeholder="Identifiant" required
                        autocomplete="username">
                </div>

                <div class="input-group">
                    <span class="input-icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required
                        autocomplete="current-password">
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-connect">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </button>
                </div>

                <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        Identifiants invalides
                    </div>
                <?php endif; ?>
            </form>

            <div class="login-footer">
                <p>© Ian et Lucas</p>
            </div>
        </div>
    </div>
</body>

</html>