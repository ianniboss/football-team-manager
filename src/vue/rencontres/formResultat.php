<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/rencontres.css">
<script src="script.js" defer></script>

<div class="result-form-container" id="formContainer" style="display: none;">
    <a id="backLink" href="#" class="back-link">
        ← Retour aux détails
    </a>

    <div class="match-info-header">
        <h1>📊 Résultat & Évaluations</h1>
        <p id="matchSub">Match contre ...</p>
    </div>

    <form id="resultForm">
        <input type="hidden" name="id_rencontre" id="id_rencontre">

        <div class="result-card">
            <h3>🏆 Résultat du match</h3>
            <div class="result-options">
                <div class="result-option victoire">
                    <input type="radio" name="resultat" id="victoire" value="Victoire">
                    <label for="victoire">
                        <span class="icon">🎉</span>
                        <span class="text">Victoire</span>
                    </label>
                </div>
                <div class="result-option nul">
                    <input type="radio" name="resultat" id="nul" value="Nul">
                    <label for="nul">
                        <span class="icon">🤝</span>
                        <span class="text">Match Nul</span>
                    </label>
                </div>
                <div class="result-option defaite">
                    <input type="radio" name="resultat" id="defaite" value="Defaite">
                    <label for="defaite">
                        <span class="icon">😔</span>
                        <span class="text">Défaite</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="evaluations-card">
            <h3>⭐ Évaluer les joueurs</h3>
            <div id="playerEvalList" class="player-eval-list">
                <!-- Rempli par le script JS -->
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                ✓ Enregistrer le résultat
            </button>
            <a id="cancelLink" href="#" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const id = new URLSearchParams(window.location.search).get('id');
        if (!id) return window.location.href = 'listeRencontres.php';

        const data = await getRencontreDetails(id);
        if (!data) return;

        const r = data.rencontre;
        const players = data.feuille_match;

        document.getElementById('id_rencontre').value = r.id_rencontre;
        document.getElementById('matchSub').textContent = `Match contre ${r.nom_equipe_adverse} - ${r.date_rencontre}`;
        document.getElementById('backLink').href = `detailRencontre.php?id=${id}`;
        document.getElementById('cancelLink').href = `detailRencontre.php?id=${id}`;

        if (r.resultat) {
            const radio = document.querySelector(`input[name="resultat"][value="${r.resultat}"]`);
            if (radio) radio.checked = true;
        }

        const list = document.getElementById('playerEvalList');
        if (players.length === 0) {
            list.innerHTML = '<div class="empty-state"><p>Aucun joueur convoqué.</p></div>';
        } else {
            list.innerHTML = players.map(p => `
                <div class="player-eval-row">
                    <div class="player-info">
                        ${p.image ? `<img src="/ftm/modele/img/players/${p.image}" class="player-avatar-img" style="width:45px; height:45px; border-radius:50%;">` : `<div class="player-avatar">${(p.prenom[0]+p.nom[0]).toUpperCase()}</div>`}
                        <div>
                            <div class="player-name">${p.prenom} ${p.nom}</div>
                            <div class="player-role">${p.titulaire ? 'Titulaire' : 'Remplaçant'}</div>
                        </div>
                    </div>
                    <div class="star-rating">
                        <input type="number" class="eval-input" data-pid="${p.id_participation}" value="${p.evaluation || ''}" min="1" max="10" placeholder="-">
                        <span>/ 10 ⭐</span>
                    </div>
                </div>
            `).join('');
        }
        document.getElementById('formContainer').style.display = 'block';
    });

    document.getElementById('resultForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('id_rencontre').value;
        const evaluations = {};
        document.querySelectorAll('.eval-input').forEach(input => {
            if (input.value) evaluations[input.dataset.pid] = input.value;
        });

        const body = {
            resultat: document.querySelector('input[name="resultat"]:checked')?.value,
            evaluations: evaluations
        };

        const result = await saveResultat(id, body);
        if (result && !result.error) window.location.href = `detailRencontre.php?id=${id}`;
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>