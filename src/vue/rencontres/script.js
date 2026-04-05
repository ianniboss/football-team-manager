// Configuration de l'API
const baseUrl = 'https://ftmanager.alwaysdata.net/api/rencontre/index.php';

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
 * Récupère toutes les rencontres
 */
async function getAllRencontres() {
    try {
        const response = await fetch(baseUrl, { headers: getAuthHeaders() });
        if (handleUnauthorized(response)) return;

        const text = await response.text(); // On récupère le texte BRUT une seule fois

        if (!response.ok) {
            console.error("Le serveur a renvoyé une erreur HTTP:", response.status, text);
            throw new Error(`Erreur serveur ${response.status}`);
        }

        try {
            const result = JSON.parse(text); // On tente de transformer le texte en JSON
            displayRencontresTable(result);
        } catch (parseError) {
            console.error("Réponse corrompue (pas du JSON) :", text);
            throw new Error("La réponse du serveur est illisible.");
        }
    } catch (error) {
        console.error('Erreur lors de la récupération des rencontres:', error);
    }
}

/**
 * Récupère les détails d'une rencontre (infos + feuille de match)
 */
async function getRencontreDetails(id) {
    try {
        const response = await fetch(`${baseUrl}?id=${id}`, { headers: getAuthHeaders() });
        if (handleUnauthorized(response)) return;
        if (!response.ok) throw new Error(`Erreur: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error('Erreur lors de la récupération des détails:', error);
    }
}

/**
 * Enregistre une rencontre (POST avec FormData pour l'image)
 */
async function saveRencontre(formData) {
    try {
        const response = await fetch(baseUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: formData
        });
        if (handleUnauthorized(response)) return;
        return await response.json();
    } catch (error) {
        console.error('Erreur lors de l\'enregistrement:', error);
    }
}

/**
 * Enregistre le résultat et les évaluations (PATCH avec JSON)
 */
async function saveResultat(id, data) {
    try {
        const response = await fetch(`${baseUrl}?id=${id}&action=resultat`, {
            method: 'PATCH',
            headers: getAuthHeaders(),
            body: JSON.stringify(data)
        });
        if (handleUnauthorized(response)) return;
        return await response.json();
    } catch (error) {
        console.error('Erreur lors de l\'enregistrement du résultat:', error);
    }
}

/**
 * Supprime une rencontre
 */
async function deleteMatch(id) {
    if (!confirm('Voulez-vous vraiment supprimer cette rencontre ?')) return;

    try {
        const response = await fetch(`${baseUrl}?id=${id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        });
        if (handleUnauthorized(response)) return;

        if (response.ok) {
            alert("Match supprimé avec succès !");
            // On force le rechargement des données depuis l'API
            getAllRencontres();
        } else {
            const text = await response.text();
            const result = JSON.parse(text || '{}');
            alert(result.error || "Le match n'a pas pu être supprimé (vérifiez s'il y a des joueurs liés).");
        }
    } catch (error) {
        console.error('Erreur Fetch:', error);
    }
}

/**
 * Affiche les rencontres dans le tableau et met à jour les stats
 */
function displayRencontresTable(rencontres) {
    const tableBody = document.querySelector('.matches-table tbody');
    if (!tableBody || !Array.isArray(rencontres)) return;

    // Mise à jour des statistiques
    document.getElementById('statTotal').textContent = rencontres.length;
    document.getElementById('statVictoires').textContent = rencontres.filter(r => r.resultat === 'Victoire').length;
    document.getElementById('statDefaites').textContent = rencontres.filter(r => r.resultat === 'Defaite').length;
    document.getElementById('statNuls').textContent = rencontres.filter(r => r.resultat === 'Nul').length;
    document.getElementById('statAVenir').textContent = rencontres.filter(r => r.resultat === null).length;

    tableBody.innerHTML = '';
    rencontres.forEach(r => {
        const row = tableBody.insertRow();
        const date = new Date(r.date_rencontre);

        row.insertCell(0).innerHTML = `<strong>${date.toLocaleDateString()}</strong><br><small>${r.heure}</small>`;
        row.insertCell(1).textContent = r.nom_equipe_adverse;
        const venueClass = r.lieu === 'Domicile' ? 'venue-domicile' : 'venue-exterieur';
        row.insertCell(2).innerHTML = `<span class="venue-badge ${venueClass}">${r.lieu}</span>`;
        row.insertCell(3).textContent = r.adresse;

        const resClass = r.resultat ? `result-${r.resultat.toLowerCase()}` : 'result-avenir';
        const resText = r.resultat || '⏱ À venir';
        row.insertCell(4).innerHTML = `<span class="result-badge ${resClass}">${resText}</span>`;

        const actions = row.insertCell(5);
        actions.innerHTML = `
            <div class="actions-cell">
                <a href="detailRencontre.php?id=${r.id_rencontre}" class="action-btn action-btn-view">Détails</a>
                <button onclick="deleteMatch(${r.id_rencontre})" class="action-btn action-btn-delete">Supprimer</button>
            </div>
        `;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.matches-table')) getAllRencontres();
});