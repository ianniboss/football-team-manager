<?php
// Empêche les warnings/erreurs PHP de polluer la réponse JSON
ini_set('display_errors', 0);

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';
require_once __DIR__ . '/../../jwt_utils.php';
require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../../config.php';

$rencontreDAO  = new RencontreDAO();
$joueurDAO     = new JoueurDAO();
$participerDAO = new ParticiperDAO();

/**
 * Agrège les statistiques globales de l'équipe et les statistiques détaillées par joueur.
 */
function getGlobalStats()
{
    global $rencontreDAO, $joueurDAO, $participerDAO;

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
            if ($match['resultat'] === 'Victoire') $statsGlobales['victoires']++;
            elseif ($match['resultat'] === 'Defaite') $statsGlobales['defaites']++;
            elseif ($match['resultat'] === 'Nul') $statsGlobales['nuls']++;
        }
    }

    if ($statsGlobales['total_joues'] > 0) {
        $total = $statsGlobales['total_joues'];
        $statsGlobales['pct_victoires'] = round(($statsGlobales['victoires'] / $total) * 100, 1);
        $statsGlobales['pct_defaites'] = round(($statsGlobales['defaites'] / $total) * 100, 1);
        $statsGlobales['pct_nuls'] = round(($statsGlobales['nuls'] / $total) * 100, 1);
    }

    $tousLesJoueurs = $joueurDAO->getJoueurs();
    $tableauJoueurs = [];

    foreach ($tousLesJoueurs as $joueur) {
        $id = $joueur['id_joueur'];

        $infos = $participerDAO->getStatsJoueur($id);
        $pourcentageGagne = $participerDAO->getPourcentageGagne($id);
        $postePrefere = $participerDAO->getPostePrefere($id);
        $serie = $participerDAO->getSerieEnCours($id);

        $tableauJoueurs[] = [
            'id_joueur' => $id,
            'nom' => $joueur['nom'],
            'prenom' => $joueur['prenom'],
            'statut' => $joueur['statut'],
            'image' => $joueur['image'] ?? null,
            'poste_prefere' => $postePrefere,
            'titularisations' => $infos['nb_titularisations'] ?? 0,
            'remplacements' => $infos['nb_remplacements'] ?? 0,
            'moyenne_notes' => $infos['moyenne_notes'] ? round($infos['moyenne_notes'], 1) : '-',
            'pct_gagne' => $pourcentageGagne,
            'serie_cours' => $serie
        ];
    }

    return sendSuccess([
        'club_stats' => $statsGlobales, // Statistiques globales de l'équipe
        'player_stats' => $tableauJoueurs // Statistiques détaillées par joueur
    ]);
}

// --- Main ---

$user = checkAuth();
if (!$user) {
    echo sendError("Accès refusé. Token invalide ou expiré.", 401);
    exit;
}
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        echo getGlobalStats();
        break;
    case "OPTIONS":
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        http_response_code(204);
        exit;
    default:
        echo sendError("Méthode non autorisée.", 405);
        break;
}
