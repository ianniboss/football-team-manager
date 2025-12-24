<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Football Team Manager</title>
    <meta name="description" content="Connectez-vous à Football Team Manager pour gérer votre équipe de football.">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background-image: url('../modele/img/stadiumbackground.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 45px 40px;
            backdrop-filter: blur(10px);
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 35px;
        }

        .logo-container img {
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
        }

        .app-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            text-align: center;
        }

        .app-subtitle {
            font-size: 0.9rem;
            color: #888;
            margin-top: 5px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .input-group {
            display: flex;
            width: 100%;
        }

        .input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            min-width: 50px;
            background-color: #2d3436;
            border: 2px solid #2d3436;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #ffffff;
            font-size: 14px;
        }

        .input-group input {
            flex: 1;
            padding: 14px 18px;
            font-size: 15px;
            font-family: inherit;
            color: #333;
            background-color: #fff;
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-group input::placeholder {
            color: #adb5bd;
        }

        .input-group:focus-within .input-icon {
            background-color: #1db988;
            border-color: #1db988;
        }

        .input-group:focus-within input {
            border-color: #1db988;
            box-shadow: 0 0 0 3px rgba(29, 185, 136, 0.15);
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .btn-connect {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            color: #ffffff;
            background: linear-gradient(135deg, #1db988 0%, #17a077 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-connect:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(29, 185, 136, 0.4);
        }

        .btn-connect:active {
            transform: translateY(0);
        }

        .btn-connect i {
            font-size: 14px;
        }

        .error-message {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            padding: 12px 16px;
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #dc2626;
            font-size: 14px;
            font-weight: 500;
        }

        .error-message i {
            font-size: 16px;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .login-footer p {
            font-size: 0.8rem;
            color: #888;
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .login-card {
                padding: 30px 25px;
            }

            .app-title {
                font-size: 1.3rem;
            }
        }
    </style>
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