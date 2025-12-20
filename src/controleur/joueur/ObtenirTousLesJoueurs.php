<?php
// afficher la liste principale
session_start();

// 1. Vérification de sécurité (Authentification)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Inclusion du DAO
require_once __DIR__ . '/../../modele/JoueurDAO.php';

// 3. Récupération des données
$dao = new JoueurDAO();
$joueurs = $dao->getJoueurs();

// 4. Filtrage par recherche (si un terme de recherche est fourni)
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['statut']) ? $_GET['statut'] : '';

if (!empty($searchQuery) || !empty($statusFilter)) {
    $joueurs = array_filter($joueurs, function ($joueur) use ($searchQuery, $statusFilter) {
        $matchSearch = true;
        $matchStatus = true;

        // Filtrer par nom, prénom ou numéro de licence
        if (!empty($searchQuery)) {
            $searchLower = strtolower($searchQuery);
            $nomComplet = strtolower($joueur['prenom'] . ' ' . $joueur['nom']);
            $nomInverse = strtolower($joueur['nom'] . ' ' . $joueur['prenom']);
            $licence = strtolower($joueur['num_licence']);

            $matchSearch = (strpos($nomComplet, $searchLower) !== false) ||
                (strpos($nomInverse, $searchLower) !== false) ||
                (strpos($licence, $searchLower) !== false);
        }

        // Filtrer par statut
        if (!empty($statusFilter)) {
            $matchStatus = ($joueur['statut'] === $statusFilter);
        }

        return $matchSearch && $matchStatus;
    });
}

$_SESSION['joueurs'] = $joueurs;
$_SESSION['search_query'] = $searchQuery;
$_SESSION['status_filter'] = $statusFilter;

header("Location: ../../vue/joueurs/listeJoueurs.php");
exit;
?>