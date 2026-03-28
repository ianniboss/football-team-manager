// L'URL de base de l'API
const baseUrl = ' https://ribou.fr/ftm/api/';
const resource = '/joueur/index.php'

async function getAll() {
    try {
        const response = await fetch(`${baseUrl}${resource}`);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const result = await response.json();
        console.log(result);
        displayData(result.data)
    } catch (error) {
        console.error(error.message);
    }
}

function getJoueur() {
    var valeurDeLaBalise = document.getElementById('phraseID').value;

    fetch(`${baseUrl}${resource}/${valeurDeLaBalise}`)
        .then(response => response.json()) // Convertir la réponse JSON en objet Javascript
        .then(result => {
            console.log(result); //Afficher en console les données récupérées
            if (result.status_code == 200) {
                displayData(result.data);
                alert('Phrase obtenue : ' + result.data[0].phrase + '\n(Voir le tableau plus bas pour plus de données)')
            } else if (result.status_code == 404) {
                alert('Erreur 404 : pas de données trouvées à l\'id ' + valeurDeLaBalise + '.')
            }
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs

    // d'une autre façon pour tester :
    // try {
    //     const response = await fetch(`${baseUrl}${resource}/${valeurDeLaBalise}`);
    //     if (!response.ok) {
    //         throw new Error(`Response status: ${response.status}`);
    //     }
    //     const result = await response.json();
    //     console.log(result);
    //     displayData(result.data)
    // } catch (error) {
    //     console.error(error.message);
    // }
}

// Méthode pour créer une nouvelle phrase
function addPhrase() {
    /*TODO : Remplacer/Adapter le code ci-dessous par votre code d'envoi d'une phrase avec la méthode POST*/
    // Récupérer la valeur d'une balise <input> identifiée avec l'id 'newPhrase' : <input type="text" id="newPhrase">
    var valeurDeLaBalise = document.getElementById('newPhrase').value;
    // Données à envoyer
    const phraseData = {
        phrase: valeurDeLaBalise
    };
    // Options de requête pour un envoi en méthode POST d’une donnée JSON
    const requestOptions = {
        method: 'POST', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
        body: JSON.stringify(phraseData) // Corps de la requête
    };
    // Effectuer une requête POST pour envoyer des données JSON
    fetch(`${baseUrl}${resource}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(result => {
            console.log(result); //Afficher en console les données récupérées
            alert('J\'envoie une phrase pour être créée dans la base de données, id : ' + result.data[0].id);
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs           
}

// Méthode pour mettre à jour une phrase
function updatePhrase() {
    /*TODO : Remplacer/Adapter le code ci-dessous par votre code de mise à jour d'une phrase avec la méthode PATCH puis PUT*/
    var valeurId = document.getElementById('updatePhraseID').value;
    var valeurPhrase = document.getElementById('updateContent').value;
    var valeurVote = document.getElementById('updateVote').value;
    var valeurFaute = document.getElementById('updateFaute').value;
    var valeurSignalement = document.getElementById('updateSignalement').value;
    var valeurMethod = document.getElementById('updateMethod').value;
    // Données à envoyer
    const phraseData = {
        id: valeurId,
        phrase: valeurPhrase,
        vote: valeurVote,
        faute: valeurFaute,
        signalement: valeurSignalement
    };
    const requestOptions = {
        method: valeurMethod, // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
        body: JSON.stringify(phraseData) // Corps de la requête
    };
    fetch(`${baseUrl}${resource}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(result => {
            console.log(result); //Afficher en console les données récupérées
            alert('J\'envoie une phrase pour être créée dans la base de données, id : ' + result.data[0].id);
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs   
}

// Méthode pour supprimer une phrase
function deletePhrase() {
    /*TODO : Remplacer/Adapter le code ci-dessous par votre code de suppression d'une phrase avec la méthode DELETE*/

    var valeurDeLaBalise = document.getElementById('deletePhraseID').value;

    // Options de requête pour un envoi en méthode POST d’une donnée JSON
    const requestOptions = {
        method: 'DELETE', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
    };
    // Effectuer une requête POST pour envoyer des données JSON
    fetch(`${baseUrl}${resource}/${valeurDeLaBalise}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(result => {
            console.log(result); //Afficher en console les données récupérées
            alert('Phrase supprimée (id : ' + valeurDeLaBalise + ')');
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs  
}

// Méthode pour afficher les données dans le tableau HTML
function displayData(phrases) {
    const tableBody = document.getElementById('responseTableBody');
    tableBody.innerHTML = ''; // nettoie le tableau avant de le remplir
    const apiResponse = document.getElementById('apiResponse');
    apiResponse.style.display = phrases.length > 0 ? 'block' : 'none';

    phrases.forEach(phrase => {
        const row = tableBody.insertRow();
        row.insertCell(0).textContent = phrase.id;
        row.insertCell(1).textContent = phrase.phrase;
        row.insertCell(2).textContent = phrase.date_ajout;
        row.insertCell(3).textContent = phrase.date_modif;
        row.insertCell(4).textContent = phrase.vote;
        row.insertCell(5).textContent = phrase.faute;
        row.insertCell(6).textContent = phrase.signalement;
    });
}

// Mise à jour de la fonction pour afficher les informations de réponse
function displayInfoResponse(baliseInfo, info) {
    if (info) {
        baliseInfo.textContent = `Statut: ${info.status}, Code: ${info.status_code}, Message: ${info.status_message}`;
        baliseInfo.style.display = 'block';
    } else {
        baliseInfo.style.display = 'none';
    }
}

// Attacher les événements aux boutons
document.getElementById('getAllPhrases').addEventListener('click', getAllPhrases);
document.getElementById('getPhrase').addEventListener('click', getPhrase);
document.getElementById('addPhrase').addEventListener('click', addPhrase);
document.getElementById('deletePhrase').addEventListener('click', deletePhrase);
document.getElementById('updatePhrase').addEventListener('click', updatePhrase);
