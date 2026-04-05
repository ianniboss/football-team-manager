<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/selection.css">
<link rel="stylesheet" href="/ftm/css/rencontres.css">
<link rel="stylesheet" href="/ftm/css/forms.css">
<script src="script.js" defer></script>

<div class="selection-container" id="selectionView" style="display: none;">
    <a id="backBtn" href="#" class="back-link">
        ← Retour aux détails du match
    </a>

    <div class="match-header">
        <h1>📋 Feuille de Match</h1>
        <p id="matchSubTitle">Chargement des données du match...</p>
    </div>

    <div class="instructions">
        💡 <strong>Instructions :</strong> Cochez les joueurs à convoquer, indiquez leur poste, et marquez-les comme titulaires (minimum 11 requis). Consultez leurs statistiques et commentaires pour faire votre choix.
    </div>

    <form id="selectionForm">
        <div class="players-panel">
            <h3>👥 Effectif disponible</h3>
            <div id="playersGrid" class="players-grid">
                <!-- Injecté par JS -->
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit" id="submitBtn">✓ Valider la sélection</button>
            <a id="cancelBtn" href="#" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const idMatch = new URLSearchParams(window.location.search).get('id_rencontre');
        if (!idMatch) return window.location.href = '../rencontres/listeRencontres.php';

        const result = await fetchSelectionData(idMatch);
        if (!result) return;

        const {
            rencontre,
            liste_joueurs,
            selection_actuelle
        } = result;

        document.getElementById('matchSubTitle').textContent = `Sélection pour le match contre ${rencontre.nom_equipe_adverse} - ${rencontre.date_rencontre}`;
        document.getElementById('backBtn').href = `../rencontres/detailRencontre.php?id=${idMatch}`;
        document.getElementById('cancelBtn').href = `../rencontres/detailRencontre.php?id=${idMatch}`;

        const grid = document.getElementById('playersGrid');
        grid.innerHTML = liste_joueurs.map(j => {
            const info = j.infos;
            const stats = j.stats;
            const current = selection_actuelle[info.id_joueur] || null;
            const isSelected = !!current;

            return `
        <div class="player-card ${isSelected ? 'selected' : ''}" data-id="${info.id_joueur}">
            <div class="player-card-header">
                <div class="player-photo">
                    ${info.image ? `<img src="/ftm/modele/img/players/${info.image}">` : `<div class="player-photo-placeholder">${info.prenom[0]}${info.nom[0]}</div>`}
                </div>
                <div class="player-main-info">
                    <div class="player-name">${info.prenom} ${info.nom}</div>
                    <div class="player-physical">📏 <strong>${info.taille}</strong> cm | ⚖️ <strong>${info.poids}</strong> kg</div>
                </div>
            </div>
            <div class="player-stats-row">
                <span class="stat-badge matches">⚽ ${stats.total_matchs} matchs</span>
                <span class="stat-badge rating">⭐ ${stats.moyenne_notes ? parseFloat(stats.moyenne_notes).toFixed(1) : '0'}/10</span>
            </div>
            <div class="player-comments">
                <div class="comments-title">💬 Derniers avis</div>
                ${j.commentaires.length ? j.commentaires.map(c => `<div class="comment-mini"><span class="comment-mini-text">${c.commentaire.substring(0,60)}...</span></div>`).join('') : '<span class="no-comments">Aucun avis</span>'}
            </div>
            <div class="selection-controls">
                <div class="control-group">
                    <input type="checkbox" class="styled-checkbox check-select" id="sel_${info.id_joueur}" ${isSelected ? 'checked' : ''}>
                    <label for="sel_${info.id_joueur}">Convoquer</label>
                </div>
                <div class="control-group">
                    <input type="checkbox" class="styled-checkbox check-titulaire" id="tit_${info.id_joueur}" ${isSelected && current.titulaire == 1 ? 'checked' : ''}>
                    <label for="tit_${info.id_joueur}">Titulaire</label>
                </div>
                <select class="position-input">
                    <option value="Remplaçant">-- Poste --</option>
                    <option value="Gardien" ${isSelected && current.poste === 'Gardien' ? 'selected' : ''}>Gardien</option>
                    <option value="Défenseur" ${isSelected && current.poste === 'Défenseur' ? 'selected' : ''}>Défenseur</option>
                    <option value="Milieu" ${isSelected && current.poste === 'Milieu' ? 'selected' : ''}>Milieu</option>
                    <option value="Attaquant" ${isSelected && current.poste === 'Attaquant' ? 'selected' : ''}>Attaquant</option>
                </select>
            </div>
        </div>`;
        }).join('');

        document.getElementById('selectionView').style.display = 'block';
    });

    document.getElementById('selectionForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const idMatch = new URLSearchParams(window.location.search).get('id_rencontre');
        const joueurs = [];

        document.querySelectorAll('.player-card').forEach(card => {
            const isSelected = card.querySelector('.check-select').checked;
            if (isSelected) {
                joueurs.push({
                    id_joueur: card.dataset.id,
                    selected: true,
                    titulaire: card.querySelector('.check-titulaire').checked,
                    poste: card.querySelector('.position-input').value
                });
            }
        });

        await saveSelection(idMatch, joueurs);
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>