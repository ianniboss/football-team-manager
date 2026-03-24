<?php
require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';
require_once __DIR__ . '/../jwt_utils.php';
require_once __DIR__ . '/../api_utils.php';
require_once __DIR__ . '/../../../../config.php';

$rencontreDAO   = new RencontreDAO();
$joueurDAO      = new JoueurDAO();
$participerDAO  = new ParticiperDAO();
$commentaireDAO = new CommentaireDAO();

define('MIN_TITULAIRES', 11);

// Pattern BFF (Backend For Frontend) ou Data Aggregation
// API d'aggregation


// --- Logique GET : Préparer l'interface de sélection ---

function getSelectionData($id_rencontre)
{
    global $rencontreDAO, $joueurDAO, $participerDAO, $commentaireDAO;

    $rencontre = $rencontreDAO->getRencontreById($id_rencontre);
    if (!$rencontre) return sendError("Match introuvable.", 404);

    $joueursActifs = $joueurDAO->getJoueursActifs();
    $feuilleMatchRaw = $participerDAO->getFeuilleMatch($id_rencontre);

    // Indexation de la sélection actuelle par ID joueur pour le front
    $selectionActuelle = [];
    foreach ($feuilleMatchRaw as $p) {
        $selectionActuelle[$p['id_joueur']] = $p;
    }

    // Agrégation des commentaires et stats par joueur
    $detailsJoueurs = [];
    foreach ($joueursActifs as $joueur) {
        $id = $joueur['id_joueur'];
        $allComments = $commentaireDAO->getCommentairesByJoueur($id);

        $detailsJoueurs[] = [
            'infos' => $joueur,
            'stats' => $participerDAO->getStatsJoueur($id),
            'commentaires' => array_slice($allComments, 0, 3)
        ];
    }

    return sendSuccess([
        'rencontre' => $rencontre,
        'liste_joueurs' => $detailsJoueurs,
        'selection_actuelle' => $selectionActuelle
    ]);
}

// --- Logique POST : Enregistrer la sélection ---

function sauvegarderSelection($data)
{
    global $participerDAO;

    $id_rencontre = validateId($data['id_rencontre']);
    $joueursEnregristres = $data['joueurs'] ?? []; // Array d'objets {id_joueur, poste, titulaire}

    if (!$id_rencontre) return sendError("ID rencontre invalide.", 400);

    // Validation du nombre de titulaires
    $titulairesCount = 0;
    foreach ($joueursEnregristres as $j) {
        if (!empty($j['selected']) && !empty($j['titulaire'])) $titulairesCount++;
    }

    if ($titulairesCount < MIN_TITULAIRES) {
        return sendError("Il faut au moins " . MIN_TITULAIRES . " titulaires (actuel : $titulairesCount).", 400);
    }

    $existingList = $participerDAO->getFeuilleMatch($id_rencontre);
    $existingIds = [];
    foreach ($existingList as $p) {
        $existingIds[$p['id_joueur']] = $p['id_participation'];
    }

    foreach ($joueursEnregristres as $j) {
        if (empty($j['selected'])) continue;

        $id_j = (int)$j['id_joueur'];
        $poste = htmlspecialchars($j['poste'] ?? 'Remplaçant');
        $is_titul = !empty($j['titulaire']) ? 1 : 0;

        if (array_key_exists($id_j, $existingIds)) {
            $participerDAO->modifierParticipation($existingIds[$id_j], $poste, $is_titul);
            unset($existingIds[$id_j]);
        } else {
            $participerDAO->ajouterParticipation($id_rencontre, $id_j, $poste, $is_titul);
        }
    }
    foreach ($existingIds as $id_participation) {
        $participerDAO->supprimerParticipation($id_participation);
    }
    return sendSuccess(['message' => 'Sélection enregistrée avec succès']);
}

// --- Main ---
function main()
{
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $id = $_GET['id_rencontre'] ?? null;
            if (!$id) sendError("Paramètre id_rencontre manquant.");
            return getSelectionData($id);
        case 'POST':
            $data = validateJsonInput();
            if (!$data) sendError("JSON invalide.");
            return sauvegarderSelection($data);
        default:
            return sendError("Méthode non autorisée.", 405);
    }
}

echo main();
