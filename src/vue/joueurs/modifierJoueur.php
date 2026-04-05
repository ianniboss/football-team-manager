<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/forms.css">
<script src="/ftm/vue/joueurs/script.js"></script>

<div class="form-card">
    <h2>Modifier le Joueur</h2>
    <p class="form-subtitle">Mettre à jour les informations du joueur</p>

    <form id="editPlayerForm">
        <input type="hidden" name="id_joueur" id="id_joueur">

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
                    <input type="text" name="num_licence" id="num_licence" placeholder="Ex: LIC-2024-001" required>
                </div>
            </div>

            <div class="form-section">
                <h3>Attributs physiques</h3>

                <div class="form-group">
                    <label>Taille & Poids</label>
                    <div class="input-row">
                        <div class="input-with-unit">
                            <input type="number" name="taille" id="taille"
                                placeholder="Taille" step="1" min="1" max="250" required>
                            <span class="unit">cm</span>
                        </div>
                        <div class="input-with-unit">
                            <input type="number" name="poids" id="poids"
                                placeholder="Poids" step="0.1" min="1" max="200" required>
                            <span class="unit">kg</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <h3 style="margin-bottom: 12px;">Photo du joueur</h3>
                    <label for="image">Changer l'image (optionnel)</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        style="width: 100%; padding: 12px; border: 1.5px dashed #ccc; border-radius: 10px; background: #fafafa; cursor: pointer;">
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <h3 style="margin-bottom: 12px;">Statut</h3>
                    <label>Statut du joueur</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="statut" value="Actif">
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
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="listeJoueurs.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        if (!id) return window.location.href = 'listeJoueurs.php';

        const data = await getJoueur(id);
        if (data) {
            const j = data.joueur;
            document.getElementById('id_joueur').value = j.id_joueur;
            document.getElementById('prenom').value = j.prenom;
            document.getElementById('nom').value = j.nom;
            document.getElementById('date_naissance').value = j.date_naissance;
            document.getElementById('num_licence').value = j.num_licence;
            document.getElementById('taille').value = j.taille;
            document.getElementById('poids').value = j.poids;

            const radio = document.querySelector(`input[name="statut"][value="${j.statut}"]`);
            if (radio) radio.checked = true;
        }
    });

    document.getElementById('editPlayerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('id_joueur').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        const result = await updateJoueur(id, data);
        if (result && !result.error) {
            window.location.href = 'ficheJoueur.php?id=' + id;
        }
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>