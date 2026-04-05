# Documentation des API - Football Team Manager

**R4.01 - Architectures logicielles**

---

## Informations generales

**Etudiants :** Ian Bin Syahrul Azlan et Lucas Récan

**Groupe :** B11

### URLs de l'application

| Application | URL |
|---|---|
| Frontend | https://www.ribou.fr/ftm/vue/ |
| API Backend | https://www.ribou.fr/ftm/api/ |
| API Authentification | https://www.ribou.fr/ftm/auth/ |

*(Les URLs sont susceptibles de changer si les API sont hebergees sur des domaines separes.)*

### Identifiants de connexion

| Role | Login | Mot de passe |
|---|---|---|
| Administrateur | *(a completer)* | *(a completer)* |
| Invite | *(a completer)* | *(a completer)* |

L'administrateur peut effectuer toutes les operations (lecture, creation, modification, suppression). L'invite a un acces en lecture seule.

### Structure de l'archive

```
BinSyahrulAzlan_Recan_B11/
    documentation_api.pdf
    frontend/          -- Interface utilisateur (vues, CSS, scripts JS)
    backend/           -- API de gestion de l'equipe (joueur, rencontre, selection, stats, commentaire)
    api-auth/          -- API d'authentification (generation et verification des jetons JWT)
```

### Bases de donnees

Deux bases de donnees distinctes sont utilisees :

- **BDD Authentification** : contient la table `user` (login, password hache, role)
- **BDD Equipe de sport** : contient les tables `joueur`, `rencontre`, `participer`, `commentaire`

---

## Table des matieres

1. [Introduction](#introduction)
2. [Architecture generale](#architecture-generale)
3. [Authentification](#authentification)
4. [Format des reponses](#format-des-reponses)
5. [API Joueur](#api-joueur)
6. [API Rencontre](#api-rencontre)
7. [API Commentaire](#api-commentaire)
8. [API Selection (Feuille de match)](#api-selection)
9. [API Statistiques](#api-statistiques)
10. [Codes d'erreur](#codes-derreur)

---

## Introduction

Football Team Manager est une application de gestion d'equipe de football. Elle expose plusieurs API REST permettant de gerer les joueurs, les rencontres, les feuilles de match, les commentaires et les statistiques.

Toutes les API necessitent une authentification par jeton JWT, a l'exception de l'endpoint d'authentification lui-meme.

**URL de base :** `https://<domaine>/api`

---

## Architecture generale

Le projet est compose de trois applications distinctes :

| Application | Role | Base de donnees |
|---|---|---|
| API d'authentification | Gestion des connexions et delivrance des jetons JWT | BDD Authentification (table `user`) |
| API de gestion (back-end) | CRUD joueurs, rencontres, feuilles de match, statistiques | BDD Equipe de sport (tables `joueur`, `rencontre`, `participer`, `commentaire`) |
| Interface utilisateur (front-end) | Affichage et interactions utilisateur | Aucune (appels aux API) |

### Schema de la base de donnees Equipe

```
joueur (id_joueur, nom, prenom, num_licence, date_naissance, taille, poids, statut, image)
rencontre (id_rencontre, date_rencontre, heure, adresse, nom_equipe_adverse, lieu, resultat, image_stade)
participer (id_participation, id_rencontre, id_joueur, poste, titulaire, evaluation)
commentaire (id_commentaire, id_joueur, commentaire, date_commentaire)
```

### Schema de la base de donnees Authentification

```
user (id, login, password, role)
```

---

## Authentification

L'API d'authentification delivre un jeton JWT necessaire pour acceder a toutes les autres API.

### POST /auth

Authentifie un utilisateur et retourne un jeton JWT.

**Corps de la requete :**

```json
{
    "login": "admin",
    "password": "motdepasse"
}
```

**Reponse (200) :**

```json
{
    "status": "success",
    "status_code": 200,
    "status_message": "[R401 REST AUTH] : Authentification OK",
    "data": "eyJhbGciOiJIUzI1NiJ9..."
}
```

**Reponse (401) :**

```json
{
    "error": "Login ou mot de passe incorrect."
}
```

### Contenu du jeton JWT

Le payload du jeton contient :

| Champ | Type | Description |
|---|---|---|
| `login` | string | Identifiant de l'utilisateur |
| `role` | string | Role de l'utilisateur (`admin` ou `guest`) |
| `exp` | integer | Timestamp d'expiration (15 minutes apres emission) |

### Utilisation du jeton

Pour chaque requete aux API de gestion, le jeton doit etre transmis dans l'en-tete HTTP :

```
Authorization: Bearer <token>
```

### Roles et permissions

| Action | admin | guest |
|---|---|---|
| Lecture (GET) | Oui | Oui |
| Creation (POST) | Oui | Non |
| Modification (PUT/PATCH) | Oui | Non |
| Suppression (DELETE) | Oui | Non |

---

## Format des reponses

### Reponse de succes

```json
{
    "donnees": "..."
}
```

Le contenu varie selon l'endpoint (tableau, objet, ou objet agrege).

### Reponse d'erreur

```json
{
    "error": "Description de l'erreur."
}
```

---

## API Joueur

Gestion des joueurs de l'equipe. Toutes les methodes necessitent un jeton valide. Les operations d'ecriture (POST, PUT, PATCH, DELETE) necessitent le role `admin`.

### GET /api/joueur

Retourne la liste de tous les joueurs. Supporte le filtrage par recherche et par statut.

**Parametres de requete (optionnels) :**

| Parametre | Type | Description |
|---|---|---|
| `search` | string | Recherche par nom, prenom ou numero de licence |
| `statut` | string | Filtrer par statut (`Actif`, `Blesse`, `Suspendu`, `Absent`) |

**Exemple de requete :**

```
GET /api/joueur?search=mbappe&statut=Actif
```

**Reponse (200) :**

```json
[
    {
        "id_joueur": 1,
        "nom": "Mbappe",
        "prenom": "Kylian",
        "num_licence": "FR001",
        "date_naissance": "1998-12-20",
        "taille": 178,
        "poids": 73.50,
        "statut": "Actif",
        "image": "kylian_mbappe_1.jpg"
    }
]
```

### GET /api/joueur/{id}

Retourne les informations d'un joueur et ses commentaires associes (agregation de donnees).

**Reponse (200) :**

```json
{
    "joueur": {
        "id_joueur": 1,
        "nom": "Mbappe",
        "prenom": "Kylian",
        "num_licence": "FR001",
        "date_naissance": "1998-12-20",
        "taille": 178,
        "poids": 73.50,
        "statut": "Actif",
        "image": "kylian_mbappe_1.jpg"
    },
    "commentaires": [
        {
            "id_commentaire": 1,
            "commentaire": "Vitesse exceptionnelle sur les contre-attaques.",
            "date_commentaire": "2025-10-16"
        }
    ]
}
```

### POST /api/joueur

Cree un nouveau joueur. Role `admin` requis.

**Corps de la requete (JSON) :**

```json
{
    "nom": "Dupont",
    "prenom": "Jean",
    "num_licence": "FR050",
    "date_naissance": "2000-05-15",
    "taille": 180,
    "poids": 75,
    "statut": "Actif"
}
```

| Champ | Obligatoire | Type | Description |
|---|---|---|---|
| `nom` | Oui | string | Nom du joueur |
| `prenom` | Oui | string | Prenom du joueur |
| `num_licence` | Non | string | Numero de licence |
| `date_naissance` | Non | string (date) | Format YYYY-MM-DD |
| `taille` | Non | integer | Taille en centimetres |
| `poids` | Non | integer | Poids en kilogrammes |
| `statut` | Non | string | `Actif` (defaut), `Blesse`, `Suspendu`, `Absent` |

**Reponse (201) :** Retourne le joueur cree.

### PUT /api/joueur/{id}

Remplacement complet d'un joueur. Tous les champs obligatoires doivent etre fournis. Les champs absents sont reinitialises a leurs valeurs par defaut. Role `admin` requis.

**Corps de la requete (JSON) :** Meme structure que POST. Les champs `nom` et `prenom` sont obligatoires.

**Reponse (200) :** Retourne le joueur modifie.

### PATCH /api/joueur/{id}

Modification partielle d'un joueur. Seuls les champs fournis sont modifies. Role `admin` requis.

**Corps de la requete (JSON) :** Un ou plusieurs champs du joueur.

```json
{
    "statut": "Blesse"
}
```

**Reponse (200) :** Retourne le joueur modifie.

### PATCH /api/joueur/{id}?statut={statut}

Modification rapide du statut d'un joueur via le parametre d'URL. Role `admin` requis.

**Valeurs autorisees pour `statut` :** `Actif`, `Blesse`, `Suspendu`, `Absent`

**Exemple :**

```
PATCH /api/joueur/1?statut=Blesse
```

**Reponse (200) :** Retourne le joueur modifie.

### DELETE /api/joueur/{id}

Supprime un joueur. Role `admin` requis.

**Reponse (204) :** Aucun contenu.

---

## API Rencontre

Gestion des rencontres (matchs). Toutes les methodes necessitent un jeton valide. Les operations d'ecriture necessitent le role `admin`.

### GET /api/rencontre

Retourne la liste de toutes les rencontres.

**Reponse (200) :**

```json
[
    {
        "id_rencontre": 1,
        "date_rencontre": "2025-10-15",
        "heure": "15:00:00",
        "adresse": "Stade Municipal, Toulouse",
        "nom_equipe_adverse": "FC Paris",
        "lieu": "Domicile",
        "resultat": "Victoire",
        "image_stade": "stade_municipal.avif"
    }
]
```

### GET /api/rencontre/{id}

Retourne une rencontre avec sa feuille de match (joueurs convoques).

**Reponse (200) :**

```json
{
    "rencontre": {
        "id_rencontre": 1,
        "date_rencontre": "2025-10-15",
        "heure": "15:00:00",
        "adresse": "Stade Municipal, Toulouse",
        "nom_equipe_adverse": "FC Paris",
        "lieu": "Domicile",
        "resultat": "Victoire",
        "image_stade": "stade_municipal.avif"
    },
    "feuille_match": [
        {
            "id_participation": 1,
            "id_joueur": 6,
            "nom": "Donnarumma",
            "prenom": "Gianluigi",
            "poste": "Gardien",
            "titulaire": 1,
            "evaluation": 4
        }
    ]
}
```

### POST /api/rencontre

Cree une nouvelle rencontre. Accepte le format `multipart/form-data` pour permettre l'upload d'une image de stade. Role `admin` requis.

**Champs du formulaire :**

| Champ | Obligatoire | Type | Description |
|---|---|---|---|
| `date_rencontre` | Oui | string (date) | Format YYYY-MM-DD |
| `heure` | Non | string (time) | Format HH:MM |
| `adresse` | Non | string | Adresse du stade |
| `nom_equipe_adverse` | Oui | string | Nom de l'adversaire |
| `lieu` | Non | string | `Domicile` ou `Exterieur` |
| `image_stade` | Non | fichier | Image du stade (upload) |

**Reponse (201) :** Retourne la rencontre creee.

### POST /api/rencontre?id={id}

Modification d'une rencontre existante via formulaire multipart (pour supporter l'upload d'image). Role `admin` requis. Les matchs passes ne peuvent pas etre modifies.

**Champs du formulaire :** Memes champs que la creation. L'identifiant peut etre passe dans l'URL (`?id=X`) ou dans le corps (`id_rencontre`).

**Reponse (200) :** Retourne la rencontre modifiee.

### PUT /api/rencontre/{id}

Remplacement complet d'une rencontre (JSON). Tous les champs obligatoires doivent etre presents. Les matchs passes ne peuvent pas etre modifies. Role `admin` requis.

**Corps de la requete (JSON) :**

```json
{
    "date_rencontre": "2026-03-15",
    "heure": "20:00",
    "adresse": "Stade de France, Paris",
    "nom_equipe_adverse": "FC Nantes",
    "lieu": "Exterieur"
}
```

| Champ | Obligatoire |
|---|---|
| `date_rencontre` | Oui |
| `heure` | Oui |
| `adresse` | Oui |
| `nom_equipe_adverse` | Oui |
| `lieu` | Oui |
| `resultat` | Non (reinitialise a `null` si absent) |

**Reponse (200) :** Retourne la rencontre modifiee.

### PATCH /api/rencontre/{id}

Modification partielle d'une rencontre (JSON). Seuls les champs fournis sont modifies. Les matchs passes ne peuvent pas etre modifies. Role `admin` requis.

**Corps de la requete (JSON) :**

```json
{
    "heure": "21:00"
}
```

**Reponse (200) :** Retourne la rencontre modifiee.

### PATCH /api/rencontre/{id}?action=resultat

Saisie du resultat d'un match et des evaluations des joueurs. Role `admin` requis.

**Corps de la requete (JSON) :**

```json
{
    "resultat": "Victoire",
    "evaluations": {
        "1": 8,
        "2": 7,
        "3": 6
    }
}
```

| Champ | Obligatoire | Type | Description |
|---|---|---|---|
| `resultat` | Oui | string | `Victoire`, `Defaite` ou `Nul` |
| `evaluations` | Non | objet | Cles = id_participation, Valeurs = note de 0 a 10 |

**Reponse (200) :**

```json
{
    "rencontre": { ... },
    "evaluations_mises_a_jour": 3
}
```

### DELETE /api/rencontre/{id}

Supprime une rencontre. Role `admin` requis.

**Reponse (204) :** Aucun contenu.

---

## API Commentaire

Gestion des commentaires sur les joueurs. Toutes les methodes necessitent un jeton valide et le role `admin`.

Les commentaires sont retournes au sein de l'API Joueur (GET /api/joueur/{id}) par agregation de donnees. L'API Commentaire ne propose donc que la creation et la suppression.

### POST /api/commentaire

Ajoute un commentaire a un joueur. Role `admin` requis.

**Corps de la requete (JSON) :**

```json
{
    "id_joueur": 1,
    "commentaire": "Bonne performance lors du dernier entrainement."
}
```

| Champ | Obligatoire | Type | Description |
|---|---|---|---|
| `id_joueur` | Oui | integer | Identifiant du joueur |
| `commentaire` | Oui | string | Texte du commentaire |

La date est attribuee automatiquement (date du jour).

**Reponse (201) :**

```json
{
    "message": "Commentaire ajoute avec succes"
}
```

### DELETE /api/commentaire/{id}

Supprime un commentaire. Role `admin` requis.

**Reponse (204) :** Aucun contenu.

---

## API Selection

API d'agregation pour gerer les feuilles de match (selection des joueurs pour une rencontre). Cette API suit le pattern BFF (Backend For Frontend) en aggregeant les donnees de plusieurs ressources.

### GET /api/selection?id_rencontre={id}

Retourne toutes les donnees necessaires a l'interface de selection : informations de la rencontre, liste des joueurs actifs avec leurs statistiques et commentaires, et la selection actuelle.

**Parametres de requete :**

| Parametre | Obligatoire | Type | Description |
|---|---|---|---|
| `id_rencontre` | Oui | integer | Identifiant de la rencontre |

**Reponse (200) :**

```json
{
    "rencontre": {
        "id_rencontre": 4,
        "date_rencontre": "2025-12-20",
        "nom_equipe_adverse": "Olympique Marseille",
        ...
    },
    "liste_joueurs": [
        {
            "infos": {
                "id_joueur": 1,
                "nom": "Mbappe",
                "prenom": "Kylian",
                ...
            },
            "stats": {
                "nb_titularisations": 5,
                "nb_remplacements": 0,
                "moyenne_notes": 4.8
            },
            "commentaires": [
                {
                    "commentaire": "Vitesse exceptionnelle...",
                    "date_commentaire": "2025-10-16"
                }
            ]
        }
    ],
    "selection_actuelle": {
        "1": {
            "id_participation": 45,
            "id_joueur": 1,
            "poste": "Attaquant",
            "titulaire": 1
        }
    }
}
```

### POST /api/selection

Enregistre la selection des joueurs pour une rencontre. Role `admin` requis.

Contrainte metier : un minimum de 11 titulaires est requis.

**Corps de la requete (JSON) :**

```json
{
    "id_rencontre": 4,
    "joueurs": [
        {
            "id_joueur": 1,
            "poste": "Attaquant",
            "titulaire": true,
            "selected": true
        },
        {
            "id_joueur": 8,
            "poste": "Ailier Gauche",
            "titulaire": false,
            "selected": true
        }
    ]
}
```

| Champ | Obligatoire | Type | Description |
|---|---|---|---|
| `id_rencontre` | Oui | integer | Identifiant de la rencontre |
| `joueurs` | Oui | tableau | Liste des joueurs selectionnes |
| `joueurs[].id_joueur` | Oui | integer | Identifiant du joueur |
| `joueurs[].poste` | Non | string | Poste attribue (defaut : `Remplacant`) |
| `joueurs[].titulaire` | Non | boolean | `true` si titulaire, `false` si remplacant |
| `joueurs[].selected` | Oui | boolean | `true` pour inclure le joueur dans la selection |

**Reponse (200) :**

```json
{
    "message": "Selection enregistree avec succes"
}
```

---

## API Statistiques

API d'agregation en lecture seule qui calcule les statistiques globales de l'equipe et les statistiques individuelles des joueurs.

Cette API ne respecte pas le principe CRUD. Elle est traitee comme une ressource a part entiere avec un endpoint dedie.

### GET /api/stats

Retourne les statistiques globales de l'equipe et les statistiques detaillees par joueur.

**Reponse (200) :**

```json
{
    "club_stats": {
        "total_joues": 5,
        "victoires": 2,
        "defaites": 2,
        "nuls": 1,
        "pct_victoires": 40.0,
        "pct_defaites": 40.0,
        "pct_nuls": 20.0
    },
    "player_stats": [
        {
            "id_joueur": 1,
            "nom": "Mbappe",
            "prenom": "Kylian",
            "statut": "Actif",
            "image": "kylian_mbappe_1.jpg",
            "poste_prefere": "Attaquant",
            "titularisations": 5,
            "remplacements": 0,
            "moyenne_notes": 4.2,
            "pct_gagne": 40.0,
            "serie_cours": "2V"
        }
    ]
}
```

**Description des champs de `club_stats` :**

| Champ | Type | Description |
|---|---|---|
| `total_joues` | integer | Nombre total de matchs joues |
| `victoires` | integer | Nombre de victoires |
| `defaites` | integer | Nombre de defaites |
| `nuls` | integer | Nombre de matchs nuls |
| `pct_victoires` | float | Pourcentage de victoires |
| `pct_defaites` | float | Pourcentage de defaites |
| `pct_nuls` | float | Pourcentage de matchs nuls |

**Description des champs de `player_stats` :**

| Champ | Type | Description |
|---|---|---|
| `id_joueur` | integer | Identifiant du joueur |
| `nom`, `prenom` | string | Identite du joueur |
| `statut` | string | Statut actuel |
| `image` | string ou null | Nom du fichier image |
| `poste_prefere` | string ou null | Poste le plus frequemment occupe |
| `titularisations` | integer | Nombre de titularisations |
| `remplacements` | integer | Nombre de fois remplacant |
| `moyenne_notes` | float ou "-" | Moyenne des evaluations |
| `pct_gagne` | float | Pourcentage de matchs gagnes en tant que participant |
| `serie_cours` | string ou null | Serie en cours (ex: "2V" pour 2 victoires consecutives) |

---

## Codes d'erreur

| Code | Signification | Exemple |
|---|---|---|
| 200 | Succes | Requete traitee avec succes |
| 201 | Cree | Ressource creee avec succes |
| 204 | Aucun contenu | Suppression reussie |
| 400 | Requete invalide | Champs manquants, JSON mal forme, ID invalide |
| 401 | Non autorise | Token absent, invalide ou expire |
| 403 | Interdit | Role insuffisant (guest tentant une ecriture) ou modification d'un match passe |
| 404 | Non trouve | Ressource inexistante |
| 405 | Methode non autorisee | Methode HTTP non supportee par l'endpoint |
| 500 | Erreur serveur | Erreur interne lors du traitement |

---

## Requetes preflight (CORS)

Tous les endpoints supportent la methode `OPTIONS` pour les requetes preflight CORS. Les en-tetes de reponse incluent :

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

---

*Projet realise par Ian Bin Syahrul Azlan et Lucas Recan - R4.01 Architectures logicielles - Groupe B11*
