<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/rencontres.css">
<script src="script.js" defer></script>

<div class="match-detail-container" id="detailContainer" style="display: none;">
    <a href="listeRencontres.php" class="back-link">
        ← Retour au calendrier
    </a>

    <div class="match-header" id="matchHeader">
        <h1 id="matchTitle">Match contre ...</h1>

        <div class="match-meta">
            <div class="meta-item">
                <span class="label">Date</span>
                <span class="value" id="valDate"></span>
            </div>
            <div class="meta-item">
                <span class="label">Heure</span>
                <span class="value" id="valHeure"></span>
            </div>
            <div class="meta-item">
                <span class="label">Lieu</span>
                <span id="venueBadge" class="venue-badge"></span>
            </div>
            <div class="meta-item">
                <span class="label">Adresse</span>
                <span class="value" id="valAdresse"></span>
            </div>
            <div class="meta-item">
                <span class="label">Résultat</span>
                <span id="resultBadge" class="result-badge"></span>
            </div>
        </div>
    </div>

    <div id="lockedNotice" class="match-locked-notice" style="display: none;">
        <span class="icon">🔒</span>
        <span>Ce match est passé. Seule la saisie du résultat et des évaluations est disponible.</span>
    </div>

    <div class="actions-grid">
        <a id="btnModifier" href="#" class="action-card">
            <div class="icon">✏️</div>
            <h4>Modifier infos</h4>
            <p>Éditer les détails du match</p>
        </a>
        <a id="btnResultat" href="#" class="action-card">
            <div class="icon">📊</div>
            <h4>Saisir Résultat</h4>
            <p>Entrer le score et les notes</p>
        </a>
        <a id="btnSelection" href="#" class="action-card">
            <div class="icon">👥</div>
            <h4>Gérer la sélection</h4>
            <p>Convoquer les joueurs</p>
        </a>
    </div>

    <div class="players-section">
        <h3>📋 Feuille de match</h3>
        <div id="playerListContainer">
            <!-- Rempli par JS -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const id = new URLSearchParams(window.location.search).get('id');
        if (!id) return window.location.href = 'listeRencontres.php';

        const data = await getRencontreDetails(id);
        if (!data) return;

        const r = data.rencontre;
        const players = data.feuille_match;

        // Remplissage En-tête
        document.getElementById('matchTitle').textContent = `Match contre ${r.nom_equipe_adverse}`;
        document.getElementById('valDate').textContent = r.date_rencontre;
        document.getElementById('valHeure').textContent = r.heure;
        document.getElementById('valAdresse').textContent = r.adresse;

        const vBadge = document.getElementById('venueBadge');
        vBadge.textContent = r.lieu;
        vBadge.className = `venue-badge ${r.lieu === 'Domicile' ? 'venue-domicile' : 'venue-exterieur'}`;

        const rBadge = document.getElementById('resultBadge');
        const res = r.resultat;
        rBadge.textContent = res ? `✓ ${res}` : '⏱ À jouer';
        rBadge.className = `result-badge ${res ? 'result-' + res.toLowerCase() : 'result-avenir'}`;

        // Image de fond
        if (r.image_stade) {
            document.getElementById('matchHeader').style.backgroundImage = `linear-gradient(135deg, rgba(45, 52, 54, 0.9) 0%, rgba(0, 0, 0, 0.85) 100%), url('/ftm/modele/img/matchs/${r.image_stade}')`;
            document.getElementById('matchHeader').style.backgroundSize = 'cover';
        }

        // Gestion des dates et verrouillage
        const isMatchPast = new Date(r.date_rencontre) < new Date().setHours(0, 0, 0, 0);
        if (isMatchPast) {
            document.getElementById('lockedNotice').style.display = 'flex';
            document.getElementById('btnModifier').classList.add('disabled');
            document.getElementById('btnSelection').classList.add('disabled');
        } else {
            document.getElementById('btnModifier').href = `formRencontre.php?id=${r.id_rencontre}`;
            document.getElementById('btnSelection').href = `../selection/feuilleMatch.php?id_rencontre=${r.id_rencontre}`;
        }
        document.getElementById('btnResultat').href = `formResultat.php?id=${r.id_rencontre}`;

        // Feuille de match
        const container = document.getElementById('playerListContainer');
        if (players.length === 0) {
            container.innerHTML = '<div class="empty-state"><p>Aucun joueur sélectionné.</p></div>';
        } else {
            container.innerHTML = `<ul class="player-list">${players.map(p => `
            <li class="player-item">
                <div class="player-info">
                    ${p.image ? `<img src="/ftm/modele/img/players/${p.image}" class="player-avatar-img">` : `<div class="player-avatar">${(p.prenom[0]+p.nom[0]).toUpperCase()}</div>`}
                    <div>
                        <div class="player-name">${p.prenom} ${p.nom}</div>
                        <div class="player-role">${p.poste}</div>
                    </div>
                </div>
                <div class="player-stats">
                    <span class="titulaire-badge ${p.titulaire ? 'titular' : 'sub'}">${p.titulaire ? 'Titulaire' : 'Remplaçant'}</span>
                    ${p.evaluation ? `<div class="rating">⭐ ${p.evaluation}/10</div>` : ''}
                </div>
            </li>
        `).join('')}</ul>`;
        }

        document.getElementById('detailContainer').style.display = 'block';
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>