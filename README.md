# football-team-manager



Votre équipe favorite a besoin de vous !

L'entraîneur vous demande de réaliser une application qui l'aidera à sélectionner les joueurs pour les matchs de SON équipe.

Il souhaite pouvoir administrer la liste de ses joueurs (avec leurs noms et prénoms, leur numéro de licence, leur date de naissance, leur taille et leur poids) ainsi que celle des matchs (avec la date et l'heure, le nom de l'équipe adverse, le lieu de rencontre - domicile ou extérieur -, et le résultat qui sera saisi une fois le match terminé).

Il souhaite également pouvoir ajouter des notes personnelles (commentaires) sur chaque joueur et préciser leur statut : Actif, Blessé, Suspendu, ou Absent.

Avant chaque match il veut pouvoir choisir la liste des joueurs qui participeront, en précisant qui sera titulaire et qui sera remplaçant, et le poste occupé par chaque participant. Pour constituer ces feuilles de matchs, il ne faudra proposer à l'entraîneur que les matchs à venir, et seulement les joueurs actifs.

Après chaque match, il souhaite pouvoir évaluer la performance de chaque joueur ayant participé au match ; l'évaluation peut être mise en oeuvre par un système de notation (de 1 à 5 par exemple) ou un système d'étoiles par exemple.

Enfin, il souhaite avoir des statistiques qui l'aideront dans sa prise de décision. Ces statistiques devront donner :

  Le nombre et le pourcentage de victoires, de défaites et de matchs nuls.
    Un tableau donnant, pour chaque joueur, son statut actuel, le poste sur lequel il est le plus performant en moyenne, son nombre de titularisations, son nombre de « remplacements » (nombre de match où le joueur est positionné en tant que remplacant), la moyenne de ses évaluations de l’entraîneur, le nombre de matchs consécutifs auxquels il a participé, et le pourcentage de matchs gagnés parmi ceux auxquels il a participé).

CONSIGNES

Ce projet doit être réalisé en binôme.

Vous devez impérativement respecter les contraintes suivantes :

  Le code SQL doit être séparé de votre code HTML et CSS, par exemple sous la forme d'une librairie contenant différentes fonctions permettant d'interagir avec la base de données.
    Les accès à la base de données doivent être faits via PDO (toutes les fonctions mySQL et mySQLi sont interdites).
    Votre application doit être accessible seulement après authentification de l'utilisateur (i.e., de l'entraîneur). Le(s) mot(s) de passe ne devront pas être stocké(s) en clair dans la base.
    Vous devez utiliser Git pour la gestion du code source de votre projet.
    Vous devrez prendre en compte la prévention aux injections SQL.

Vous pouvez choisir le sport d'équipe de votre choix (Football, Rugby, Basketball, Volleyball, etc.), vous ferez les adaptations nécessaires à chaque sport (nombre de titulaires par match par exemple).

Avant de vous lancer dans le développement prenez le temps de bien réfléchir à votre application dans sa globalité. N'hésitez pas à faire des maquettes des différents écrans et posez-vous des questions d'ordre pratique (par exemple, est-ce qu'il ne serait pas intéressant de mémoriser le statut d'un match pour savoir s'il a déjà été préparé ou pas ?).

Gardez à l'esprit que l'application devra être pratique à utiliser et accessible à des non informaticiens (c'est-à-dire un entraîneur de sport).

INSTRUCTIONS POUR RENDRE VOTRE PROJET

ATTENTION : SI LES CONSIGNES CI-DESSSOUS NE SONT PAS RESPECTÉES, VOTRE PROJET NE SERA PAS CORRIGÉ !!

  Vous devez déposer une archive (c'est-à-dire un fichier .7z, ou .tgz, ou .gz, ou .gzip, ou .rar, ou .tar, ou .zip) contenant l'intégralité de votre code source au plus tard le dimanche 18 janvier à 23h55, à l'aide du devoir ci-dessous. Le nom de ce fichier doit comprendre les 2 noms constituant votre binôme, ainsi que la lettre du groupe du binôme (ou 2 lettres si le binôme est constitué d'étudiants de 2 groupes différents) ; un nom de fichier valide est par exemple “Dupont_Durand_A.zip" ou "Dupont_Durand_A&C.tar".
    Votre application doit être disponible sur un serveur de votre choix accessible en ligne. A la racine de votre archive, vous devez donc créer un fichier nommé “README.md” contenant l’URL de votre site ainsi que toute autre information qui pourrait être nécessaire à l’utilisation de celui-ci (par exemple, des informations d’authentification).

AUCUN RETARD NE SERA TOLÉRÉ !


Modélisation

Avant de démarrer le développement du projet, il vous est demandé de modéliser le sujet à traiter. Selon le choix d'implémentation de votre groupe, réaliser:

  Procédural: un MCD représentant votre future base de données
    POO: un diagramme de classe représentant les classes métiers de votre future application (on ne s'attardera pas sur les classes utilitaires ou techniques du projet)

Sur papier ou à l'aide de l'outil de votre choix, réaliser le modèle de données pour cette application.

APRÈS AVOIR FAIT VALIDER VOTRE MODÈLE par votre enseignant, démarrer le développement du projet.
Gestion des joueurs et des matchs

Créer les pages nécessaires à l'affichage, l'ajout, la modification, et la suppression des joueurs et des matchs.
Saisie des feuilles de match

Créer une page permettant:

  de sélectionner le joueur titulaire pour chaque poste (parmi les joueurs actifs)
    de sélectionner les remplaçant en précisant le poste de chacun (parmi les joueurs actifs)
    Si le nombre minimum de joueurs n'est pas atteint, la sélection ne devra pas pouvoir être validé
    L'interface de sélection devra afficher les informations des joueurs : taille, poids, historique des commentaires et des évaluations

Adapter l'affichage des matchs pour permettre de visualiser et modifier la sélection.
Statistiques

Si ce n'est pas déjà fait, modifier la page de modification d'un match pour permettre la saisie du résultat ainsi que les évaluations de l'entraîneur.

Créer ensuite une page affichant les statistiques suivantes :

  Le nombre total et le pourcentage de matchs gagnés, perdus, ou nuls.
    Un tableau avec pour chaque joueur : son statut actuel, son poste préféré, le nombre total de sélections en tant que titulaire, le nombre total de sélections en tant que remplaçant, la moyenne des évaluations de l'entraîneur, et le pourcentage de matchs gagnés lorsqu'il a participé, et le nombre de sélections consécutives à date.

Authentification

Sécuriser l'application en créant une page d'authentification (à l'aide d'un nom d'utilisateur et d'un mot de passe définis à l'avance). Aucune autre page de l'application ne devra être accessible si l'utilisateur n'est pas authentifié.

Mettre en place un menu qui sera affiché sur chaque page pour permettre à l'utilisateur de naviguer dans l'application. Ajouter tous les liens nécessaires entre les différentes pages.

Mise en forme

Utiliser les feuilles de style (CSS) et les bases d'ergonomie logicielle pour faire en sorte que l'utilisation de l'application soit la plus agréable et intuitive possible.

N.B : La priorité reste le code et les fonctionnalités, attention à ne pas perdre trop de temps sur la forme.



## Routes Cheat Sheet (MVC)

Conventions
- Front controller: `public/index.php` with query params `c` (controller) and `a` (action).
- All routes require authentication (session) except `public/login.php`.
- Use `GET` to display forms/pages and `POST` to submit changes.
- Base URL examples assume `http://localhost:8000/public/`.

Authentication
- Login (GET/POST): `/public/login.php`
- Logout (GET): `/public/logout.php`

Players (`c=players`)
- List (GET): `/public/index.php?c=players&a=index`
- Show (GET): `/public/index.php?c=players&a=show&id=<id_joueur>`
- Create form (GET): `/public/index.php?c=players&a=create`
- Store (POST): `/public/index.php?c=players&a=store`
- Edit form (GET): `/public/index.php?c=players&a=edit&id=<id_joueur>`
- Update (POST): `/public/index.php?c=players&a=update&id=<id_joueur>`
- Delete (POST): `/public/index.php?c=players&a=delete&id=<id_joueur>`
- Comments (GET): `/public/index.php?c=players&a=comments&id=<id_joueur>`
- Add comment (POST): `/public/index.php?c=players&a=addComment&id=<id_joueur>`
- Delete comment (POST): `/public/index.php?c=players&a=deleteComment&id_commentaire=<id_commentaire>`

Matches (`c=matches`)
- List upcoming/past (GET): `/public/index.php?c=matches&a=index`
- Show (GET): `/public/index.php?c=matches&a=show&id=<id_match>`
- Create form (GET): `/public/index.php?c=matches&a=create`
- Store (POST): `/public/index.php?c=matches&a=store`
- Edit form (GET): `/public/index.php?c=matches&a=edit&id=<id_match>`
- Update (POST): `/public/index.php?c=matches&a=update&id=<id_match>`
- Delete (POST): `/public/index.php?c=matches&a=delete&id=<id_match>`
- Edit result + evaluations (GET): `/public/index.php?c=matches&a=editResult&id=<id_match>`
- Update result + evaluations (POST): `/public/index.php?c=matches&a=updateResult&id=<id_match>`

Selection / Match Sheet (`c=selection`)
- Edit selection (GET): `/public/index.php?c=selection&a=edit&id_match=<id_match>`
- Update selection (POST): `/public/index.php?c=selection&a=update&id_match=<id_match>`

Statistics (`c=stats`)
- Global and per-player stats (GET): `/public/index.php?c=stats&a=index`

Notes
- Selection editor enforces minimum starters and only lists active players.
- Matches “Edit Result” page also captures coach evaluations for participants.
- Navigation menu appears on every page and links to Players, Matches, Selection (per match), and Stats.
