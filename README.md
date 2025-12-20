# Football Team Manager ⚽

> Application de gestion d'équipe de football pour les entraîneurs

---

## 🌐 Accès à l'Application

### URL du Site
**🔗 [Lien vers l'application](https://VOTRE-URL-ICI.com)**

*(Remplacez cette URL par l'adresse de votre serveur de déploiement)*

### Identifiants de Connexion

| Utilisateur | Mot de passe |
|-------------|--------------|
| `admin`     | `1234`       |
| `lucas`     | `password`   |
| `user`      | `iutinfo`    |

---

## 📋 Fonctionnalités Implémentées

### Gestion des Joueurs
- ✅ Liste complète des joueurs avec photos
- ✅ Ajout d'un nouveau joueur (avec upload de photo)
- ✅ Modification des informations d'un joueur
- ✅ Suppression d'un joueur
- ✅ Gestion du statut (Actif, Blessé, Suspendu, Absent)
- ✅ Commentaires personnels sur chaque joueur

### Gestion des Matchs
- ✅ Calendrier des rencontres (passées et à venir)
- ✅ Ajout d'un nouveau match (avec upload d'image du stade)
- ✅ Modification des informations d'un match
- ✅ Suppression d'un match
- ✅ Saisie du résultat (Victoire, Défaite, Nul)

### Feuilles de Match
- ✅ Sélection des joueurs pour un match (titulaires/remplaçants)
- ✅ Attribution des postes à chaque joueur
- ✅ Affichage des informations joueurs (taille, poids, commentaires, stats)
- ✅ Validation minimum 11 titulaires requis
- ✅ Évaluation des joueurs après le match (notes 1-5)

### Statistiques
- ✅ Nombre et pourcentage de victoires, défaites, nuls
- ✅ Tableau complet par joueur :
  - Statut actuel
  - Poste préféré
  - Nombre de titularisations
  - Nombre de remplacements
  - Moyenne des évaluations
  - Pourcentage de matchs gagnés
  - Série de matchs consécutifs

### Sécurité
- ✅ Page de connexion avec authentification
- ✅ Protection de toutes les pages (redirection si non connecté)
- ✅ Prévention des injections SQL (requêtes préparées PDO)
- ✅ Utilisation de sessions PHP

---

## 🛠️ Technologies Utilisées

| Catégorie | Technologie |
|-----------|-------------|
| **Frontend** | HTML5, CSS3 |
| **Backend** | PHP 8.x |
| **Base de données** | MySQL / MariaDB |
| **Accès BDD** | PDO (PHP Data Objects) |
| **Architecture** | MVC (Modèle-Vue-Contrôleur) |
| **Versioning** | Git |

---

## 📁 Structure du Projet

```
football-team-manager/
├── data/                          # Scripts SQL
│   ├── ftm-projet.sql             # Structure de la base de données
│   └── ftm-projet-avec-data.sql   # Structure + données de test
├── src/
│   ├── controleur/                # Contrôleurs PHP
│   │   ├── joueur/                # Actions sur les joueurs
│   │   ├── rencontre/             # Actions sur les matchs
│   │   ├── selection/             # Actions sur les feuilles de match
│   │   ├── stats/                 # Affichage des statistiques
│   │   └── commentaire/           # Actions sur les commentaires
│   ├── modele/                    # Couche d'accès aux données (DAO)
│   │   ├── ConnexionBD.php        # Connexion PDO singleton
│   │   ├── JoueurDAO.php          # CRUD joueurs
│   │   ├── RencontreDAO.php       # CRUD rencontres
│   │   ├── ParticiperDAO.php      # Feuilles de match + stats
│   │   └── CommentaireDAO.php     # CRUD commentaires
│   └── vue/                       # Fichiers de présentation
│       ├── header.php             # En-tête avec navigation
│       ├── connexion.php          # Page de login
│       ├── accueil.php            # Page d'accueil
│       ├── joueurs/               # Vues joueurs
│       ├── rencontres/            # Vues matchs
│       ├── selection/             # Vue feuille de match
│       └── stats/                 # Vue statistiques
├── MCD.png                        # Modèle Conceptuel de Données
└── README.md                      # Ce fichier
```

---

## 💾 Installation Locale

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur / MariaDB
- Serveur web (Apache, Nginx, ou XAMPP/WAMP/MAMP)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/VOTRE-USERNAME/football-team-manager.git
   cd football-team-manager
   ```

2. **Créer la base de données**
   - Importez le fichier `data/ftm-projet-avec-data.sql` dans votre serveur MySQL
   - Ou utilisez `data/ftm-projet.sql` pour une base vide

3. **Configurer la connexion**
   - Modifiez le fichier `src/modele/ConnexionBD.php`
   - Adaptez les paramètres de connexion (host, dbname, user, password)

4. **Lancer l'application**
   - Placez le projet dans le dossier de votre serveur web (htdocs, www, etc.)
   - Accédez à `http://localhost/football-team-manager/src/vue/connexion.php`

---

## 👥 Auteurs

- **Ian** 
- **Lucas**

---