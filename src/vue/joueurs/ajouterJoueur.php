<?php require_once __DIR__ . '/../header.php'; ?>
<!-- Formulaire d'ajout de joueur - Utilisé par AjouterJoueur.php -->

<style>
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px 50px;
        max-width: 750px;
        margin: 40px auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-card h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
    }

    .form-subtitle {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 30px;
        font-weight: 400;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .form-section h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 12px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="date"],
    .form-group select {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        color: #333;
        background-color: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #1db988;
        box-shadow: 0 0 0 3px rgba(29, 185, 136, 0.1);
    }

    .form-group input::placeholder {
        color: #aaa;
    }

    /* Radio buttons styling */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .radio-option input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .radio-option input[type="radio"]:checked {
        border-color: #1db988;
    }

    .radio-option input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background-color: #1db988;
        border-radius: 50%;
    }

    .radio-option span {
        font-size: 0.95rem;
        color: #555;
    }

    /* Input row for height/weight */
    .input-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .input-with-unit {
        position: relative;
    }

    .input-with-unit input {
        padding-right: 50px;
    }

    .input-with-unit .unit {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 0.9rem;
        pointer-events: none;
    }

    /* Icon styling for inputs */
    .input-with-icon {
        position: relative;
    }

    .input-with-icon input {
        padding-right: 45px;
    }

    .input-with-icon .icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 1.1rem;
        pointer-events: none;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 35px;
        padding-top: 20px;
    }

    .btn {
        padding: 14px 40px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: #1db988;
        color: white;
    }

    .btn-primary:hover {
        background-color: #17a077;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 185, 136, 0.3);
    }

    .btn-secondary {
        background-color: #f5f5f5;
        color: #333;
        border: 1.5px solid #ddd;
    }

    .btn-secondary:hover {
        background-color: #eee;
        border-color: #ccc;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-card {
            margin: 20px;
            padding: 30px 25px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .input-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-card">
    <h2>Nouveau Joueur</h2>
    <p class="form-subtitle">Ajouter un nouveau joueur à l'équipe</p>

    <form method="POST" action="/controleur/joueur/AjouterJoueur.php">
        <div class="form-grid">
            <!-- Left Column - Informations personnelles -->
            <div class="form-section">
                <h3>Informations personnelles</h3>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" placeholder="Entrez le prénom..." required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" placeholder="Entrez le nom..." required>
                </div>

                <div class="form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <div class="input-with-icon">
                        <input type="date" name="date_naissance" id="date_naissance" required>
                        <span class="icon">📅</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="num_licence">Numéro de licence</label>
                    <input type="text" name="num_licence" id="num_licence" placeholder="Ex: LIC-2024-001" required>
                </div>
            </div>

            <!-- Right Column - Attributs physiques & Statut -->
            <div class="form-section">
                <h3>Attributs physiques</h3>

                <div class="form-group">
                    <label>Taille & Poids</label>
                    <div class="input-row">
                        <div class="input-with-unit">
                            <input type="number" name="taille" id="taille" placeholder="Taille" step="1" min="100"
                                max="250" required>
                            <span class="unit">cm</span>
                        </div>
                        <div class="input-with-unit">
                            <input type="number" name="poids" id="poids" placeholder="Poids" step="0.1" min="30"
                                max="200" required>
                            <span class="unit">kg</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <h3 style="margin-bottom: 12px;">Statut</h3>
                    <label>Statut du joueur</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Actif" checked>
                            <span>Actif</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Blessé">
                            <span>Blessé</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Suspendu">
                            <span>Suspendu</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Absent">
                            <span>Absent</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer le joueur</button>
            <a href="/controleur/joueur/ObtenirTousLesJoueurs.php" class="btn btn-secondary"
                style="text-decoration: none; text-align: center;">
                Annuler
            </a>
        </div>
    </form>
</div>

</div>
</body>

</html>