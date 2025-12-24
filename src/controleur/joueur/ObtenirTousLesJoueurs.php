<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}
require_once __DIR__ . '/../../modele/JoueurDAO.php';
$dao = new JoueurDAO();
$joueurs = $dao->getJoueurs();

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['statut']) ? $_GET['statut'] : '';

if (!empty($searchQuery) || !empty($statusFilter)) {
    $joueurs = array_filter($joueurs, function ($joueur) use ($searchQuery, $statusFilter) {
        $matchSearch = true;
        $matchStatus = true;

        if (!empty($searchQuery)) {
            $searchLower = strtolower($searchQuery);
            $nomComplet = strtolower($joueur['prenom'] . ' ' . $joueur['nom']);
            $nomInverse = strtolower($joueur['nom'] . ' ' . $joueur['prenom']);
            $licence = strtolower($joueur['num_licence']);

            $matchSearch = (strpos($nomComplet, $searchLower) !== false) ||
                (strpos($nomInverse, $searchLower) !== false) ||
                (strpos($licence, $searchLower) !== false);
        }

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