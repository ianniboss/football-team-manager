<?php
session_start();
// Ce fichier fait le gros du travail de calcul 
// (surtout pour les pourcentages globaux) afin 
// que la Vue n'ait plus qu'à afficher les variables.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.html");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';

$rencontreDAO = new RencontreDAO();
$joueurDAO = new JoueurDAO();
$participerDAO = new ParticiperDAO();

// --- A. Statistiques Globales (Matchs) ---
$tousLesMatchs = $rencontreDAO->getRencontres();

$statsGlobales = [
    'total_joues' => 0,
    'victoires' => 0,
    'defaites' => 0,
    'nuls' => 0,
    'pct_victoires' => 0,
    'pct_defaites' => 0,
    'pct_nuls' => 0
];

foreach ($tousLesMatchs as $match) {
    if ($match['resultat'] !== null) {
        $statsGlobales['total_joues']++;
        if ($match['resultat'] === 'Victoire')
            $statsGlobales['victoires']++;
        elseif ($match['resultat'] === 'Defaite')
            $statsGlobales['defaites']++;
        elseif ($match['resultat'] === 'Nul')
            $statsGlobales['nuls']++;
    }
}

// Calcul des pourcentages (éviter la division par zéro)
if ($statsGlobales['total_joues'] > 0) {
    $statsGlobales['pct_victoires'] = round(($statsGlobales['victoires'] / $statsGlobales['total_joues']) * 100, 1);
    $statsGlobales['pct_defaites'] = round(($statsGlobales['defaites'] / $statsGlobales['total_joues']) * 100, 1);
    $statsGlobales['pct_nuls'] = round(($statsGlobales['nuls'] / $statsGlobales['total_joues']) * 100, 1);
}

// --- B. Statistiques par Joueur ---
$tousLesJoueurs = $joueurDAO->getJoueurs();
$tableauJoueurs = [];

foreach ($tousLesJoueurs as $joueur) {
    $id = $joueur['id_joueur'];

    // On récupère les données brutes via le DAO
    $infos = $participerDAO->getStatsJoueur($id);
    $pourcentageGagne = $participerDAO->getPourcentageGagne($id);
    $postePrefere = $participerDAO->getPostePrefere($id);
    $serie = $participerDAO->getSerieEnCours($id); // La méthode que nous venons d'ajouter

    // On compile tout dans un tableau propre pour la vue
    $tableauJoueurs[] = [
        'nom' => $joueur['nom'],
        'prenom' => $joueur['prenom'],
        'statut' => $joueur['statut'],
        'poste_prefere' => $postePrefere,
        'titularisations' => $infos['nb_titularisations'] ?? 0, // ?? 0 gère le cas NULL
        'remplacements' => $infos['nb_remplacements'] ?? 0,
        'moyenne_notes' => $infos['moyenne_notes'] ? round($infos['moyenne_notes'], 1) : '-',
        'pct_gagne' => $pourcentageGagne,
        'serie_cours' => $serie
    ];
}

// 3. Appel de la vue
require __DIR__ . '/../../vue/stats/index.php';
?>