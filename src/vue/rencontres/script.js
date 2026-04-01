const baseUrl = 'https://www.ribou.fr/ftm/api';
const resource = '/joueur';
let allPlayers = []; // On stocke les joueurs ici pour filtrer sans recharger l'API

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('players-tbody')) {
        getAll();
    }

    // --- GESTION DE LA RECHERCHE ---
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Empêche le rechargement de la page

            const nameQuery = searchForm.search.value.toLowerCase();
            const statusQuery = searchForm.statut.value;

            const filtered = allPlayers.filter(j => {
                const fullName = `${j.prenom} ${j.nom} ${j.num_licence}`.toLowerCase();
                const matchesName = fullName.includes(nameQuery);
                const matchesStatus = statusQuery === "" || j.statut === statusQuery;
                return matchesName && matchesStatus;
            });

            displayData(filtered);
        });
    }
});

async function getAll() {
    try {
        const response = await fetch(`${baseUrl}${resource}`);
        if (!response.ok) throw new Error(`Erreur HTTP : ${response.status}`);

        const result = await response.json();

        if (Array.isArray(result)) {
            allPlayers = result; // On sauvegarde la liste complète
            displayData(allPlayers);
        }
    } catch (error) {
        console.error("Erreur :", error.message);
        const tbody = document.getElementById('players-tbody');
        if (tbody) tbody.innerHTML = `<tr><td colspan="6" style="color:red; text-align:center;">Erreur : ${error.message}</td></tr>`;
    }
}

function displayData(joueurs) {
    const tbody = document.getElementById('players-tbody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (joueurs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 20px;">Aucun joueur trouvé.</td></tr>';
        return;
    }

    joueurs.forEach(j => {
        const tr = document.createElement('tr');

        let statusClass = 'status-actif';
        if (j.statut === 'Blessé') statusClass = 'status-blesse';
        if (j.statut === 'Suspendu') statusClass = 'status-suspendu';
        if (j.statut === 'Absent') statusClass = 'status-absent';

        tr.innerHTML = `
            <td><span class="player-name">${j.prenom} ${j.nom}</span></td>
            <td><span class="player-license">${j.num_licence}</span></td>
            <td>${j.taille || '-'} cm</td>
            <td>${j.poids || '-'} kg</td>
            <td><span class="status-badge ${statusClass}">${j.statut}</span></td>
            <td>
                <div class="actions-cell">
                    <a href="/ftm/vue/joueurs/ficheJoueur.php?id=${j.id_joueur}" class="action-btn action-btn-view">Voir</a>
                    <a href="/ftm/vue/joueurs/modifierJoueur.php?id=${j.id_joueur}" class="action-btn action-btn-edit">Modifier</a>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });

    updateStats(joueurs);
}

function updateStats(joueurs) {
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length >= 3) {
        statNumbers[0].textContent = joueurs.length;
        statNumbers[1].textContent = joueurs.filter(j => j.statut === 'Actif').length;
        statNumbers[2].textContent = joueurs.filter(j => j.statut === 'Blessé').length;
    }
}