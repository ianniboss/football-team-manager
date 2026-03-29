<?php require_once __DIR__ . '/../header.php'; ?>
<!-- Formulaire d'ajout de joueur - Utilisé par AjouterJoueur.php -->
<link rel="stylesheet" href="/ftm/css/forms.css">

<div class="form-card">
    <h2>Nouveau Joueur</h2>
    <p class="form-subtitle">Ajouter un nouveau joueur à l'équipe</p>

    <form method="POST" action="/controleur/joueur/AjouterJoueur.php" enctype="multipart/form-data">
        <div class="form-grid">
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
                    <input type="text" name="num_licence" id="num_licence" placeholder="Ex: TC001" required>
                </div>
            </div>

            <div class="form-section">
                <h3>Attributs physiques</h3>

                <div class="form-group">
                    <label>Taille & Poids</label>
                    <div class="input-row">
                        <div class="input-with-unit">
                            <input type="number" name="taille" id="taille" placeholder="Taille" step="1" min="1"
                                max="250" required>
                            <span class="unit">cm</span>
                        </div>
                        <div class="input-with-unit">
                            <input type="number" name="poids" id="poids" placeholder="Poids" step="0.1" min="1"
                                max="200" required>
                            <span class="unit">kg</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <h3 style="margin-bottom: 12px;">Photo du joueur</h3>
                    <label for="image">Sélectionner une image (optionnel)</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        style="width: 100%; padding: 12px; border: 1.5px dashed #ccc; border-radius: 10px; background: #fafafa; cursor: pointer;">
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

<?php require_once __DIR__ . '/../footer.php'; ?>