// Configuration de l'API (Chemin relatif à la racine du serveur)
const baseUrl = '/ftm/api';
const resource = '/joueur/index.php';

/**
 * Récupère les en-têtes d'authentification incluant le token JWT stocké
 */
function getAuthHeaders() {
    const token = localStorage.getItem('token');
    return {
        'Content-Type': 'application/json',
        'Authorization': token ? `Bearer ${token}` : ''
    };
}

/**
 * Gère les réponses d'erreur globales (notamment la 401)
 */
function handleResponse(response) {
    if (response.status === 401) {
        localStorage.removeItem('token');
        window.location.href = '/ftm/vue/index.php';
        return false;
    }
    return response;
}

/**
 * Récupère tous les joueurs (avec filtres optionnels)
 */
async function getAllJoueurs(search = '', statut = '') {
    try {
        const url = new URL(window.location.origin + baseUrl + resource);
        if (search) url.searchParams.append('search', search);
        if (statut) url.searchParams.append('statut', statut);

        let response = await fetch(url, { headers: getAuthHeaders() });
        response = handleResponse(response);
        if (!response) return;

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const result = await response.json();
        displayJoueursTable(result);
    } catch (error) {
        console.error('Erreur lors de la récupération des joueurs:', error);
    }
}

/**
 * Récupère un joueur spécifique par son ID
 */
async function getJoueur(id) {
    try {
        const response = await fetch(`${baseUrl}${resource}?id=${id}`, { headers: getAuthHeaders() });
        if (response.status === 401) window.location.href = '../index.php';
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Erreur lors de la récupération du joueur:', error);
    }
}

/**
 * Ajoute un nouveau joueur via l'API
 */
async function addJoueur(joueurData) {
    try {
        const response = await fetch(`${baseUrl}${resource}`, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(joueurData)
        });
        if (response.status === 401) window.location.href = '../index.php';
        return await response.json();
    } catch (error) {
        console.error('Erreur lors de l\'ajout du joueur:', error);
    }
}

/**
 * Met à jour un joueur existant
 */
async function updateJoueur(id, joueurData, method = 'PUT') {
    try {
        const response = await fetch(`${baseUrl}${resource}?id=${id}`, {
            method: method,
            headers: getAuthHeaders(),
            body: JSON.stringify(joueurData)
        });
        if (response.status === 401) window.location.href = '../index.php';
        return await response.json();
    } catch (error) {
        console.error('Erreur lors de la mise à jour:', error);
    }
}

/**
 * Supprime un joueur
 */
async function deleteJoueur(id) {
    if (!confirm('Voulez-vous vraiment supprimer ce joueur ?')) return;

    try {
        const response = await fetch(`${baseUrl}${resource}?id=${id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        });
        if (response.status === 401) window.location.href = '../index.php';
        if (response.ok) location.reload();
    } catch (error) {
        console.error('Erreur lors de la suppression:', error);
    }
}

/**
 * Affiche les joueurs dans le tableau HTML
 */
function displayJoueursTable(joueurs) {
    const tableBody = document.querySelector('.players-table tbody');
    if (!tableBody) return;
    // Sécurité : on vérifie que tableBody existe et que joueurs est bien un tableau
    if (!tableBody || !Array.isArray(joueurs)) return;

    // Mise à jour des statistiques en haut de page
    if (document.getElementById('statTotal')) document.getElementById('statTotal').textContent = joueurs.length;
    if (document.getElementById('statActifs')) document.getElementById('statActifs').textContent = joueurs.filter(j => j.statut === 'Actif').length;
    if (document.getElementById('statBlesses')) document.getElementById('statBlesses').textContent = joueurs.filter(j => j.statut === 'Blessé').length;

    tableBody.innerHTML = '';
    joueurs.forEach(j => {
        const row = tableBody.insertRow();
        row.insertCell(0).textContent = `${j.prenom} ${j.nom}`;
        row.insertCell(1).textContent = j.num_licence;
        row.insertCell(2).textContent = `${j.taille} cm`;
        row.insertCell(3).textContent = `${j.poids} kg`;
        // Correction de la classe pour gérer les accents (ex: Blessé -> blesse)
        const statusClass = j.statut.toLowerCase().replace('é', 'e');
        row.insertCell(4).innerHTML = `<span class="status-badge status-${statusClass}">${j.statut}</span>`;
        const actions = row.insertCell(5);
        actions.innerHTML = `
            <div class="actions-cell">
                <a href="ficheJoueur.php?id=${j.id_joueur}" class="action-btn action-btn-view">Voir</a>
                <button onclick="deleteJoueur(${j.id_joueur})" class="action-btn action-btn-delete">Supprimer</button>
            </div>
        `;
    });
}

// Chargement initial de la liste
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.players-table')) getAllJoueurs();
});
