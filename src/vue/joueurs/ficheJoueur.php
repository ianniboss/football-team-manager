<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/joueurs.css">
<script src="/ftm/vue/joueurs/script.js"></script>

<div class="player-card" id="playerContainer" style="display: none;">
    <div class="player-image" id="playerImage">
        <!-- Injecté par JS -->
    </div>

    <h2 id="playerName"></h2>
    <p class="player-subtitle">Fiche du joueur</p>

    <div class="info-grid">
        <div class="info-section">
            <h3>Informations personnelles</h3>
            <div class="info-item">
                <label>Prénom</label>
                <div class="value" id="valPrenom"></div>
            </div>
            <div class="info-item">
                <label>Nom</label>
                <div class="value" id="valNom"></div>
            </div>
            <div class="info-item">
                <label>Date de naissance</label>
                <div class="value" id="valDateNais"></div>
            </div>
            <div class="info-item">
                <label>Numéro de licence</label>
                <div class="value" id="valLicence"></div>
            </div>
        </div>
        <div class="info-section">
            <h3>Attributs physiques</h3>
            <div class="info-item">
                <label>Taille & Poids</label>
                <div class="info-row">
                    <div class="value"><span id="valTaille"></span> cm</div>
                    <div class="value"><span id="valPoids"></span> kg</div>
                </div>
            </div>
            <div class="info-item" style="margin-top: 30px;">
                <label>Statut</label>
                <div id="statusBadge" class="status-badge"></div>
            </div>
        </div>
    </div>

    <div class="comments-section">
        <h3>Commentaires</h3>
        <div class="comments-list" id="commentsList">
            <!-- Injecté par JS -->
        </div>
    </div>

    <div class="card-actions">
        <a id="linkModifier" href="#" class="btn btn-primary">Modifier</a>
        <a href="listeJoueurs.php" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const params = new URLSearchParams(window.location.search);
        const id = params.get('id');
        if (!id) return window.location.href = 'listeJoueurs.php';

        const data = await getJoueur(id);
        if (!data) return;

        const j = data.joueur;
        document.getElementById('playerName').textContent = j.prenom + ' ' + j.nom;
        document.getElementById('valPrenom').textContent = j.prenom;
        document.getElementById('valNom').textContent = j.nom;
        document.getElementById('valDateNais').textContent = j.date_naissance;
        document.getElementById('valLicence').textContent = j.num_licence;
        document.getElementById('valTaille').textContent = j.taille;
        document.getElementById('valPoids').textContent = j.poids;

        const badge = document.getElementById('statusBadge');
        badge.textContent = j.statut;
        badge.className = 'status-badge status-' + j.statut.toLowerCase().replace('é', 'e');

        const imgDiv = document.getElementById('playerImage');
        if (j.image) {
            imgDiv.innerHTML = `<img src="/ftm/modele/img/players/${j.image}" alt="Photo">`;
        } else {
            const initials = (j.prenom[0] + j.nom[0]).toUpperCase();
            imgDiv.innerHTML = `<div class="player-image-placeholder">${initials}</div>`;
        }

        const commentsList = document.getElementById('commentsList');
        if (data.commentaires && data.commentaires.length > 0) {
            commentsList.innerHTML = data.commentaires.map(c => `
            <div class="comment-item">
                <div class="comment-date">${new Date(c.date_commentaire).toLocaleDateString()}</div>
                <div class="comment-text">${c.commentaire}</div>
            </div>
        `).join('');
        } else {
            commentsList.innerHTML = '<div class="no-comments">Aucun commentaire pour ce joueur.</div>';
        }

        document.getElementById('linkModifier').href = `modifierJoueur.php?id=${j.id_joueur}`;
        document.getElementById('playerContainer').style.display = 'block';
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>