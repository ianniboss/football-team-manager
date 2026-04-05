<?php
require_once __DIR__ . '/header.php';
?>
<link rel="stylesheet" href="/ftm/css/accueil.css">

<div class="dashboard">
    <div class="welcome-section">
        <h1>Bonjour <span id="userName">...</span> 👋</h1>
        <p class="subtitle">Voici un aperçu de votre équipe</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon players">👥</div>
            <div class="stat-info">
                <h3 id="statTotalJoueurs">...</h3>
                <p>Joueurs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">✓</div>
            <div class="stat-info">
                <h3 id="statJoueursActifs">...</h3>
                <p>Actifs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon injured">🏥</div>
            <div class="stat-info">
                <h3 id="statJoueursBlesses">...</h3>
                <p>Blessés</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon matches">⚽</div>
            <div class="stat-info">
                <h3 id="statTotalMatchs">...</h3>
                <p>Matchs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon victories">🏆</div>
            <div class="stat-info">
                <h3 id="statVictoires">...</h3>
                <p>Victoires</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon defeats">📊</div>
            <div class="stat-info">
                <h3 id="statNuls">...</h3>
                <p>Nuls</p>
            </div>
        </div>
    </div>

    <h2 class="section-title">Actions Rapides</h2>
    <div class="quick-actions">
        <a href="/ftm/vue/joueurs/ajouterJoueur.php" class="action-card">
            <div class="action-icon">+</div>
            <div>
                <h4>Ajouter un joueur</h4>
                <p>Enregistrer un nouveau membre</p>
            </div>
        </a>
        <a href="/ftm/vue/rencontres/formRencontre.php" class="action-card">
            <div class="action-icon">📅</div>
            <div>
                <h4>Planifier un match</h4>
                <p>Créer une nouvelle rencontre</p>
            </div>
        </a>
        <a href="/ftm/vue/joueurs/listeJoueurs.php" class="action-card">
            <div class="action-icon">👥</div>
            <div>
                <h4>Voir l'effectif</h4>
                <p>Gérer les joueurs</p>
            </div>
        </a>
        <a href="/ftm/vue/rencontres/listeRencontres.php" class="action-card">
            <div class="action-icon">📋</div>
            <div>
                <h4>Calendrier</h4>
                <p>Voir tous les matchs</p>
            </div>
        </a>
    </div>

    <h2 class="section-title">Aperçu des Matchs</h2>
    <div class="next-match-section">
        <div class="next-match-card">
            <h3>⏱ Prochain Match</h3>
            <div id="prochainMatchContainer">
                <div class="no-match">
                    <p>Chargement...</p>
                </div>
            </div>
        </div>

        <div class="recent-results-card">
            <h3>📊 Derniers Résultats</h3>
            <div id="derniersResultatsContainer">
                <div class="no-match">
                    <p>Chargement...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const token = localStorage.getItem('token');
        if (!token) return;

        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            document.getElementById('userName').textContent = payload.login;
        } catch (e) {
            console.error("Erreur décodage token");
        }

        const headers = {
            'Authorization': 'Bearer ' + token
        };

        const resJoueurs = await fetch('https://ftmanager.alwaysdata.net/api/joueur/index.php', {
            headers
        });
        if (resJoueurs.ok) {
            const joueurs = await resJoueurs.json();
            document.getElementById('statTotalJoueurs').textContent = joueurs.length;
            document.getElementById('statJoueursActifs').textContent = joueurs.filter(j => j.statut === 'Actif').length;
            document.getElementById('statJoueursBlesses').textContent = joueurs.filter(j => j.statut === 'Blessé').length;
        }

        const resMatchs = await fetch('https://ftmanager.alwaysdata.net/api/rencontre/index.php', {
            headers
        });
        if (resMatchs.ok) {
            const result = await resMatchs.json();
            const rencontres = result.data;

            document.getElementById('statTotalMatchs').textContent = rencontres.length;
            document.getElementById('statVictoires').textContent = rencontres.filter(r => r.resultat === 'Victoire').length;
            document.getElementById('statNuls').textContent = rencontres.filter(r => r.resultat === 'Nul').length;

            // Prochain Match
            const prochain = rencontres.find(r => r.resultat === null);
            const prochainContainer = document.getElementById('prochainMatchContainer');
            if (prochain) {
                const date = new Date(prochain.date_rencontre);
                prochainContainer.innerHTML = `
                <div class="match-info">
                    <div class="match-date-box">
                        <div class="day">${date.getDate()}</div>
                        <div class="month">${date.toLocaleString('fr', { month: 'short' })}</div>
                    </div>
                    <div class="match-details">
                        <h4>vs ${prochain.nom_equipe_adverse}</h4>
                        <p>${prochain.heure} - ${prochain.adresse}</p>
                        <span class="venue-badge">${prochain.lieu}</span>
                    </div>
                </div>`;
            } else {
                prochainContainer.innerHTML = '<div class="no-match"><p>Aucun match programmé</p></div>';
            }

            // Derniers Résultats
            const joues = rencontres.filter(r => r.resultat !== null).slice(0, 4);
            const resultatsContainer = document.getElementById('derniersResultatsContainer');
            if (joues.length > 0) {
                resultatsContainer.innerHTML = joues.map(m => `
                <div class="result-item">
                    <span class="result-opponent">vs ${m.nom_equipe_adverse}</span>
                    <span class="result-badge ${m.resultat.toLowerCase()}">${m.resultat}</span>
                </div>
            `).join('');
            } else {
                resultatsContainer.innerHTML = '<div class="no-match"><p>Aucun résultat disponible</p></div>';
            }
        }
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>