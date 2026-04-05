<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/forms.css">
<script src="script.js" defer></script>

<div class="form-card">
    <h2 id="formTitle">Nouvelle Rencontre</h2>
    <p class="form-subtitle">Informations générales</p>

    <form id="matchForm">
        <input type="hidden" name="id_rencontre" id="id_rencontre">

        <div class="form-grid">
            <div class="form-section">
                <h3>Informations générales</h3>

                <div class="form-group">
                    <label for="nom_equipe_adverse">Équipe adverse</label>
                    <div class="input-with-icon">
                        <input type="text" name="nom_equipe_adverse" id="nom_equipe_adverse"
                            placeholder="Ex: FC Paris" required>
                        <span class="icon">⚽</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date_rencontre">Date</label>
                    <div class="input-with-icon">
                        <input type="date" name="date_rencontre" id="date_rencontre"
                            min="<?php echo date('Y-m-d'); ?>" required>
                        <span class="icon">📅</span>
                    </div>
                    <small style="color: #888; font-size: 0.8rem;">La date doit être aujourd'hui ou dans le futur</small>
                </div>

                <div class="form-group">
                    <label for="heure">Heure</label>
                    <div class="input-with-icon">
                        <input type="time" name="heure" id="heure" required>
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
                            <input type="radio" name="lieu" value="Domicile" checked>
                            <span>Domicile</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="lieu" value="Exterieur">
                            <span>Extérieur</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse du stade</label>
                    <input type="text" name="adresse" id="adresse"
                        placeholder="Entrez l'adresse complète du stade..." required>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <h3 style="margin-bottom: 12px;">Image du stade</h3>
                    <div id="imagePreview"></div>
                    <label for="image_stade">Sélectionner une image (optionnel)</label>
                    <input type="file" name="image_stade" id="image_stade" accept="image/*"
                        style="width: 100%; padding: 12px; border: 1.5px dashed #ccc; border-radius: 10px; background: #fafafa; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">Créer la rencontre</button>
            <a href="listeRencontres.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const id = new URLSearchParams(window.location.search).get('id');
        if (id) {
            const data = await getRencontreDetails(id);
            if (data) {
                const r = data.rencontre;
                document.getElementById('formTitle').textContent = "Modifier la Rencontre";
                document.getElementById('submitBtn').textContent = "Modifier";
                document.getElementById('id_rencontre').value = r.id_rencontre;
                document.getElementById('nom_equipe_adverse').value = r.nom_equipe_adverse;
                document.getElementById('date_rencontre').value = r.date_rencontre;
                document.getElementById('heure').value = r.heure;
                document.getElementById('adresse').value = r.adresse;
                document.querySelector(`input[name="lieu"][value="${r.lieu}"]`).checked = true;

                if (r.image_stade) {
                    document.getElementById('imagePreview').innerHTML = `<img src="https://ftmanager.alwaysdata.net/modele/img/matchs/${r.image_stade}" style="width: 120px; border-radius: 8px; margin-bottom: 10px;">`;
                }
            }
        }
    });

    document.getElementById('matchForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const id = document.getElementById('id_rencontre').value;

        // Si ID présent, on utilise PATCH ou PUT. Ici l'API gère le POST pour création/upload.
        const result = await saveRencontre(formData);
        if (result && !result.error) {
            window.location.href = 'listeRencontres.php';
        }
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>