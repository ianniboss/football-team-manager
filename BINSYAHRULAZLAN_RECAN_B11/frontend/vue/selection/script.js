const selectionApiUrl = '/ftm/api/selection/index.php';

function getAuthHeaders() {
    const token = localStorage.getItem('token');
    return {
        'Content-Type': 'application/json',
        'Authorization': token ? `Bearer ${token}` : ''
    };
}

async function fetchSelectionData(idMatch) {
    try {
        const response = await fetch(`${selectionApiUrl}?id_rencontre=${idMatch}`, {
            headers: getAuthHeaders()
        });

        if (response.status === 401) {
            window.location.href = '../index.php';
            return;
        }

        return await response.json();
    } catch (error) {
        console.error("Erreur chargement sélection:", error);
    }
}

async function saveSelection(idMatch, joueursArray) {
    try {
        const response = await fetch(selectionApiUrl, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify({
                id_rencontre: idMatch,
                joueurs: joueursArray
            })
        });

        const result = await response.json();

        if (response.ok) {
            alert("Sélection enregistrée avec succès !");
            window.location.href = `../rencontres/detailRencontre.php?id=${idMatch}`;
        } else {
            alert("Erreur : " + (result.error || "Impossible d'enregistrer"));
        }
    } catch (error) {
        console.error("Erreur enregistrement:", error);
        alert("Une erreur technique est survenue.");
    }
}