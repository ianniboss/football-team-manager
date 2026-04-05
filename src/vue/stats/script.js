const statsApiUrl = '/ftm/api/stats/index.php';

/**
 * Récupère les en-têtes d'authentification
 */
function getAuthHeaders() {
    const token = localStorage.getItem('token');
    return {
        'Authorization': token ? `Bearer ${token}` : ''
    };
}

/**
 * Gère les erreurs 401 (Unauthorized) globalement
 */
function handleUnauthorized(response) {
    if (response.status === 401) {
        localStorage.removeItem('token');
        window.location.href = '/ftm/vue/index.php';
        return true;
    }
    return false;
}

/**
 * Récupère toutes les statistiques globales et par joueur
 */
async function fetchAndDisplayStats() {
    try {
        const response = await fetch(statsApiUrl, { headers: getAuthHeaders() });
        if (handleUnauthorized(response)) return;

        if (!response.ok) {
            const errorBody = await response.text();
            console.error('Erreur serveur (Text):', errorBody);
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const result = await response.json();

        // Assurez-vous que l'API renvoie bien un objet avec 'club_stats' et 'player_stats'
        if (result && result.club_stats && result.player_stats) {
            displayGlobalStats(result.club_stats);
            displayPlayerStats(result.player_stats);
        } else {
            console.error("Format de données inattendu de l'API des stats:", result);
        }
    } catch (error) {
        console.error('Erreur lors de la récupération des statistiques:', error);
    }
}

/**
 * Affiche les statistiques globales de l'équipe
 */
function displayGlobalStats(stats) {
    document.getElementById('statVictoires').textContent = stats.victoires;
    document.getElementById('pctVictoires').textContent = `${stats.pct_victoires}%`;
    document.getElementById('statDefaites').textContent = stats.defaites;
    document.getElementById('pctDefaites').textContent = `${stats.pct_defaites}%`;
    document.getElementById('statNuls').textContent = stats.nuls;
    document.getElementById('pctNuls').textContent = `${stats.pct_nuls}%`;
    document.getElementById('statTotalMatchs').textContent = stats.total_joues;
}

/**
 * Affiche les statistiques détaillées par joueur dans le tableau
 */
function displayPlayerStats(players) {
    const tableBody = document.querySelector('.stats-table tbody');
    if (!tableBody) return;

    tableBody.innerHTML = ''; // Nettoyer le tableau

    if (players.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8" class="empty-state-cell">Aucune statistique de joueur disponible.</td></tr>';
        return;
    }

    players.forEach(j => {
        const row = tableBody.insertRow();

        // Player Info
        const playerCell = row.insertCell(0);
        const avatar = j.image
            ? `<img src="/ftm/modele/img/players/${j.image}" alt="Photo" class="player-avatar-img" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e0e0e0;">`
            : `<div class="player-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">${(j.prenom[0] + j.nom[0]).toUpperCase()}</div>`;

        playerCell.innerHTML = `<div class="player-cell" style="display: flex; align-items: center; gap: 10px;">${avatar}<span class="player-name">${j.prenom} ${j.nom}</span></div>`;

        // Statut
        const statusClass = j.statut.toLowerCase().replace('é', 'e');
        row.insertCell(1).innerHTML = `<span class="status-badge status-${statusClass}">${j.statut}</span>`;

        // Poste préféré
        row.insertCell(2).innerHTML = j.poste_prefere ? `<span class="position-badge">${j.poste_prefere}</span>` : '<span style="color: #888;">-</span>';

        // Titularisations, Remplacements, Note Moy.
        row.insertCell(3).innerHTML = `<span class="num-highlight">${j.titularisations}</span>`;
        row.insertCell(4).innerHTML = `<span class="num-highlight">${j.remplacements}</span>`;
        row.insertCell(5).innerHTML = j.moyenne_notes && j.moyenne_notes > 0 ? `<span class="rating">⭐ ${j.moyenne_notes}</span>` : '<span style="color: #888;">-</span>';

        // % Victoires
        row.insertCell(6).innerHTML = `<div class="pct-cell"><span class="pct-value">${j.pct_gagne}%</span><div class="pct-bar"><div class="pct-bar-fill" style="width: ${j.pct_gagne}%;"></div></div></div>`;

        // Série
        // Sécurité : on force la conversion en string pour éviter l'erreur sur .includes()
        const serieText = (j.serie_cours || "0").toString();
        const serieClass = serieText.includes('V') ? 'serie-w' : (serieText.includes('D') ? 'serie-l' : 'serie-d');
        row.insertCell(7).innerHTML = `<span class="serie-badge ${serieClass}">${serieText}</span>`;
    });
}

document.addEventListener('DOMContentLoaded', fetchAndDisplayStats);