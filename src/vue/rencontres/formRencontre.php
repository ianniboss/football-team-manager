<?php
require_once __DIR__ . '/../header.php';
$rencontre = $_SESSION['rencontre_modify'] ?? null;
unset($_SESSION['rencontre_modify']);
?>
<!-- utilise ajouterRencontre.php et ModifierUneRencontre.php -->
<link rel="stylesheet" href="/ftm/css/forms.css">

<div class="form-card">
    <h2><?php echo isset($rencontre) ? 'Modifier la Rencontre' : 'Nouvelle Rencontre'; ?></h2>
    <p class="form-subtitle">Informations générales</p>

    <form method="POST" action="<?php echo isset($rencontre) ? '/api/rencontre/ModifierUneRencontre.php' : '/api/rencontre/ajouterRencontre.php'; ?>" enctype="multipart/form-data">
        <?php if (isset($rencontre)): ?>
            <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-section">
                <h3>Informations générales</h3>

                <div class="form-group">
                    <label for="nom_equipe_adverse">Équipe adverse</label>
                    <div class="input-with-icon">
                        <input type="text" name="nom_equipe_adverse" id="nom_equipe_adverse"
                            value="<?php echo $rencontre['nom_equipe_adverse'] ?? ''; ?>"
                            placeholder="Ex: FC Paris" required>
                        <span class="icon">⚽</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date_rencontre">Date</label>
                    <div class="input-with-icon">
                        <input type="date" name="date_rencontre" id="date_rencontre"
                            value="<?php echo $rencontre['date_rencontre'] ?? ''; ?>"
                            min="<?php echo date('Y-m-d'); ?>" required>
                        <span class="icon">📅</span>
                    </div>
                    <small style="color: #888; font-size: 0.8rem;">La date doit être aujourd'hui ou dans le futur</small>
                </div>

                <div class="form-group">
                    <label for="heure">Heure</label>
                    <div class="input-with-icon">
                        <input type="time" name="heure" id="heure"
                            value="<?php echo $rencontre['heure'] ?? ''; ?>" required>
                        <span class="icon">🕐</span>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Lieu</h3>

                <div class="form-group">
                    <label>Type de rencontre</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="lieu" value="Domicile"
                                <?php echo (!isset($rencontre) || $rencontre['lieu'] == 'Domicile') ? 'checked' : ''; ?>>
                            <span>Domicile</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="lieu" value="Exterieur"
                                <?php echo (isset($rencontre) && $rencontre['lieu'] == 'Exterieur') ? 'checked' : ''; ?>>
                            <span>Extérieur</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse du stade</label>
                    <input type="text" name="adresse" id="adresse"
                        value="<?php echo $rencontre['adresse'] ?? ''; ?>"
                        placeholder="Entrez l'adresse complète du stade..." required>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <h3 style="margin-bottom: 12px;">Image du stade</h3>
                    <?php if (isset($rencontre) && !empty($rencontre['image_stade'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="/modele/img/matchs/<?php echo htmlspecialchars($rencontre['image_stade']); ?>"
                                alt="Photo du stade"
                                style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                            <p style="font-size: 0.8rem; color: #888; margin-top: 5px;">Image actuelle</p>
                        </div>
                    <?php endif; ?>
                    <label for="image_stade">Sélectionner une image (optionnel)</label>
                    <input type="file" name="image_stade" id="image_stade" accept="image/*"
                        style="width: 100%; padding: 12px; border: 1.5px dashed #ccc; border-radius: 10px; background: #fafafa; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo isset($rencontre) ? 'Modifier' : 'Créer la rencontre'; ?>
            </button>
            <a href="/api/rencontre/ObtenirToutesLesRencontres.php" class="btn btn-secondary" style="text-decoration: none; text-align: center;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>