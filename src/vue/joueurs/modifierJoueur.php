<?php
require_once __DIR__ . '/../header.php';
$joueur = $_SESSION['joueur_modify'] ?? null;
if (!$joueur) {
    header("Location: /vue/joueurs/listeJoueurs.php");
    exit;
}
?>
<!-- Formulaire de modification de joueur - Utilisé par ModifierIdentiteDuJoueur.php -->
<link rel="stylesheet" href="/css/forms.css">

<div class="form-card">
    <h2>Modifier le Joueur</h2>
    <p class="form-subtitle">Mettre à jour les informations du joueur</p>

    <form method="POST" action="/controleur/joueur/ModifierIdentiteDuJoueur.php" enctype="multipart/form-data">
        <input type="hidden" name="id_joueur" value="<?php echo $joueur['id_joueur']; ?>">

        <div class="form-grid">
            <div class="form-section">
                <h3>Informations personnelles</h3>
                
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" 
                           value="<?php echo htmlspecialchars($joueur['prenom']); ?>" 
                           placeholder="Entrez le prénom..." required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" 
                           value="<?php echo htmlspecialchars($joueur['nom']); ?>" 
                           placeholder="Entrez le nom..." required>
                </div>

                <div class="form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <div class="input-with-icon">
                        <input type="date" name="date_naissance" id="date_naissance" 
                               value="<?php echo $joueur['date_naissance']; ?>" required>
                        <span class="icon">📅</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="num_licence">Numéro de licence</label>
                    <input type="text" name="num_licence" id="num_licence" 
                           value="<?php echo htmlspecialchars($joueur['num_licence']); ?>" 
                           placeholder="Ex: LIC-2024-001" required>
                </div>
            </div>

            <div class="form-section">
                <h3>Attributs physiques</h3>
                
                <div class="form-group">
                    <label>Taille & Poids</label>
                    <div class="input-row">
                        <div class="input-with-unit">
                            <input type="number" name="taille" id="taille" 
                                   value="<?php echo $joueur['taille']; ?>" 
                                   placeholder="Taille" step="1" min="1" max="250" required>
                            <span class="unit">cm</span>
                        </div>
                        <div class="input-with-unit">
                            <input type="number" name="poids" id="poids" 
                                   value="<?php echo $joueur['poids']; ?>" 
                                   placeholder="Poids" step="0.1" min="1" max="200" required>
                            <span class="unit">kg</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <h3 style="margin-bottom: 12px;">Photo du joueur</h3>
                    <?php if (!empty($joueur['image'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="/modele/img/players/<?php echo htmlspecialchars($joueur['image']); ?>" 
                                 alt="Photo actuelle" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                            <p style="font-size: 0.8rem; color: #888; margin-top: 5px;">Photo actuelle</p>
                        </div>
                    <?php endif; ?>
                    <label for="image">Changer l'image (optionnel)</label>
                    <input type="file" name="image" id="image" accept="image/*" 
                           style="width: 100%; padding: 12px; border: 1.5px dashed #ccc; border-radius: 10px; background: #fafafa; cursor: pointer;">
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <h3 style="margin-bottom: 12px;">Statut</h3>
                    <label>Statut du joueur</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Actif" 
                                   <?php echo ($joueur['statut'] == 'Actif') ? 'checked' : ''; ?>>
                            <span>Actif</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Blessé"
                                   <?php echo ($joueur['statut'] == 'Blessé') ? 'checked' : ''; ?>>
                            <span>Blessé</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Suspendu"
                                   <?php echo ($joueur['statut'] == 'Suspendu') ? 'checked' : ''; ?>>
                            <span>Suspendu</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Absent"
                                   <?php echo ($joueur['statut'] == 'Absent') ? 'checked' : ''; ?>>
                            <span>Absent</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/controleur/joueur/ObtenirUnJoueur.php?id=<?php echo $joueur['id_joueur']; ?>" class="btn btn-secondary" style="text-decoration: none; text-align: center;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
