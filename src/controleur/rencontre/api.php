<?php
require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';
require_once __DIR__ . '/../jwt_utils.php';
require_once __DIR__ . '/../../../../config.php'; // contient JWT_SECRET

$rencontreDAO  = new RencontreDAO();
$participerDAO = new ParticiperDAO();

// --- Fonctions utilitaires de réponse ---

function sendSuccess($data, $code = 200)
{
    http_response_code($code);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function sendError($message, $code = 400)
{
    http_response_code($code);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    return json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
}

function validateJsonInput()
{
    $json = file_get_contents('php://input');
    if (empty($json)) return null;
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erreur JSON : " . json_last_error_msg());
        return false;
    }
    return $data;
}

function validateId($id)
{
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id === false || $id <= 0) return null;
    return $id;
}

/**
 * Nettoie le nom du stade (première partie de l'adresse) pour un nom de fichier.
 * Ex : "Stade Municipal, 31000 Toulouse" → "stade_municipal"
 */
function sanitizeStadiumName($adresse)
{
    $parts = explode(',', $adresse);
    $name  = trim($parts[0]);
    $name  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
    $name  = strtolower(str_replace(' ', '_', $name));
    $name  = preg_replace('/[^a-z0-9_]/', '', $name);
    return $name;
}

/**
 * Déplace l'image du stade uploadée dans /img/matchs/ et retourne son nom.
 * Supprime l'ancienne image si $ancienneImage est fournie.
 * Retourne null si aucun fichier n'est envoyé.
 */
function gererUploadImageStade($adresse, $ancienneImage = null)
{
    if (!isset($_FILES['image_stade']) || $_FILES['image_stade']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = __DIR__ . '/../../modele/img/matchs/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if ($ancienneImage) {
        $oldPath = $uploadDir . $ancienneImage;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    $extension  = pathinfo($_FILES['image_stade']['name'], PATHINFO_EXTENSION);
    $imageName  = sanitizeStadiumName($adresse) . '.' . $extension;
    $targetPath = $uploadDir . $imageName;

    if (!move_uploaded_file($_FILES['image_stade']['tmp_name'], $targetPath)) {
        return null;
    }

    return $imageName;
}

// --- Logique métier ---

/**
 * GET /api.php
 * Retourne toutes les rencontres.
 */
function getAll()
{
    global $rencontreDAO;
    $rencontres = $rencontreDAO->getRencontres();
    return sendSuccess([
        'data'  => array_values($rencontres),
        'total' => count($rencontres),
    ]);
}

/**
 * GET /api.php?id=X
 * Retourne une rencontre avec sa feuille de match (joueurs convoqués).
 */
function getRencontre($id)
{
    global $rencontreDAO, $participerDAO;

    $id = validateId($id);
    if ($id === null) {
        return sendError("L'ID doit être un entier valide et positif.", 400);
    }

    $rencontre = $rencontreDAO->getRencontreById($id);
    if (!$rencontre) {
        return sendError("Rencontre introuvable.", 404);
    }
    $feuilleMatch = $participerDAO->getFeuilleMatch($id);
    return sendSuccess([
        'rencontre'     => $rencontre,
        'feuille_match' => $feuilleMatch,
    ]);
}

/**
 * POST /api_rencontres.php
 * Crée une nouvelle rencontre. Accepte multipart/form-data (image optionnelle).
 * Champs obligatoires : date_rencontre, heure, adresse, nom_equipe_adverse, lieu
 */
function creerRencontre($data)
{
    global $rencontreDAO;

    $date_rencontre = htmlspecialchars($data['date_rencontre'] ?? '');
    $heure = htmlspecialchars($data['heure'] ?? '');
    $adresse = htmlspecialchars($data['adresse'] ?? '');
    $nom_equipe_adverse = htmlspecialchars($data['nom_equipe_adverse'] ?? '');
    $lieu = htmlspecialchars($data['lieu'] ?? '');

    $imageStade = gererUploadImageStade($adresse);

    $rencontreDAO->ajouterRencontre($date_rencontre, $heure, $adresse, $nom_equipe_adverse, $lieu, $imageStade);
    $newId = $rencontreDAO->getLastInsertId();

    return sendSuccess($rencontreDAO->getRencontreById($newId), 201);
}

/**
 * PUT /api.php?id=X
 * Met à jour les infos d'une rencontre à venir.
 * Refuse la modification d'un match passé (date < aujourd'hui).
 */
function updateRencontre($id, $data)
{
    global $rencontreDAO;

    $id = validateId($id);
    if ($id === null) {
        return sendError("L'ID doit être un entier valide et positif.", 400);
    }

    $rencontre = $rencontreDAO->getRencontreById($id);
    if (!$rencontre) {
        return sendError("Rencontre introuvable.", 404);
    }

    // Refus de modification d'un match passé
    $matchDate = new DateTime($rencontre['date_rencontre']);
    $today     = new DateTime('today');
    if ($matchDate < $today) {
        return sendError("Impossible de modifier un match dont la date est déjà passée.", 403);
    }

    $date_rencontre = htmlspecialchars($data['date_rencontre'] ?? '');
    $heure = htmlspecialchars($data['heure'] ?? '');
    $adresse = htmlspecialchars($data['adresse'] ?? '');
    $nom_equipe_adverse = htmlspecialchars($data['nom_equipe_adverse'] ?? '');
    $lieu = htmlspecialchars($data['lieu'] ?? '');
    $resultat = htmlspecialchars($data['resultat'] ?? '');
    // Nouvelle date également dans le passé ?
    $newDate = new DateTime($date_rencontre);
    if ($newDate < $today) {
        return sendError("La nouvelle date du match ne peut pas être dans le passé.", 400);
    }

    $ancienneImage = $rencontre['image_stade'] ?? null;
    $imageStade    = gererUploadImageStade($adresse, $ancienneImage) ?? $ancienneImage;

    $rencontreDAO->modifierRencontre($id, $date_rencontre, $heure, $adresse, $nom_equipe_adverse, $lieu, $resultat, $imageStade);

    return sendSuccess($rencontreDAO->getRencontreById($id));
}

/**
 * PATCH /api.php?id=X&action=resultat
 * Saisit le résultat d'un match terminé et les évaluations des joueurs.
 *
 * Corps JSON attendu :
 * {
 *   "resultat": "Victoire",           // obligatoire
 *   "evaluations": {                  // optionnel
 *     "12": 8,
 *     "15": 6
 *   }
 * }
 *
 * Équivalent du changerStatut des joueurs : action ciblée sur un seul aspect
 * de la ressource, sans toucher au reste (date, adresse, équipe…).
 */
function saisirResultatEtEvaluations($id)
{
    global $rencontreDAO, $participerDAO;

    $id = validateId($id);
    if ($id === null) {
        return sendError("L'ID doit être un entier valide et positif.", 400);
    }

    $rencontre = $rencontreDAO->getRencontreById($id);
    if (!$rencontre) {
        return sendError("Rencontre introuvable.", 404);
    }

    $data = validateJsonInput();
    if ($data === false) {
        return sendError("Le JSON fourni est mal formé.", 400);
    }
    if (empty($data['resultat'])) {
        return sendError("Le champ 'resultat' est obligatoire.", 400);
    }

    $resultatsValides = ['Victoire', 'Défaite', 'Nul'];
    if (!in_array($data['resultat'], $resultatsValides, true)) {
        return sendError("Résultat invalide. Valeurs acceptées : " . implode(', ', $resultatsValides) . ".", 400);
    }

    // Mise à jour du résultat (on préserve toutes les autres infos du match)
    $rencontreDAO->modifierRencontre(
        $id,
        $rencontre['date_rencontre'],
        $rencontre['heure'],
        $rencontre['adresse'],
        $rencontre['nom_equipe_adverse'],
        $rencontre['lieu'],
        $data['resultat'],
        $rencontre['image_stade'] ?? null
    );

    // Mise à jour des évaluations des joueurs si fournies
    $evaluationsTraitees = [];
    if (!empty($data['evaluations']) && is_array($data['evaluations'])) {
        foreach ($data['evaluations'] as $id_participation => $note) {
            $id_participation = filter_var($id_participation, FILTER_VALIDATE_INT);
            $note = filter_var($note, FILTER_VALIDATE_INT);

            if ($id_participation === false || $id_participation <= 0) continue;
            if ($note === false || $note < 0 || $note > 10) continue;

            $participerDAO->noterJoueur($id_participation, $note);
            $evaluationsTraitees[] = $id_participation;
        }
    }

    return sendSuccess([
        'rencontre' => $rencontreDAO->getRencontreById($id),
        'evaluations_mises_a_jour' => count($evaluationsTraitees),
    ]);
}

/**
 * DELETE /api.php?id=X
 * Supprime une rencontre.
 */
function deleteRencontre($id)
{
    global $rencontreDAO;

    $id = validateId($id);
    if ($id === null) {
        return sendError("L'ID doit être un entier valide et positif.", 400);
    }

    if (!$rencontreDAO->getRencontreById($id)) {
        return sendError("Rencontre introuvable.", 404);
    }

    $rencontreDAO->supprimerRencontre($id);

    http_response_code(204);
    header('Access-Control-Allow-Origin: *');
    // 204 No Content : pas de corps de réponse
}

// --- Point d'entrée principal ---

function main()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    if ($method === 'OPTIONS') {
        http_response_code(204);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        return;
    }

    switch ($method) {
        case 'GET':
            return ($id) ? getRencontre($id) : getAll();
        case 'POST':
            // $token = get_bearer_token();
            // if (!$token || !is_jwt_valid($token, JWT_SECRET)) {
            //     return sendError("Authentification requise pour créer une ressource.", 401);
            // }
            if ($id) {
                return sendError("L'ID ne doit pas être fourni pour une requête POST.", 400);
            }
            $data = validateJsonInput();
            if ($data === false) {
                return sendError("Le JSON fourni est mal formé.", 400);
            }
            return creerRencontre($data);
        case 'PATCH':
            // $token = get_bearer_token();
            // if (!$token || !is_jwt_valid($token, JWT_SECRET)) {
            //     return sendError("Authentification requise pour modifier une ressource.", 401);
            // }
            if (!$id) {
                return sendError("L'ID est obligatoire pour une requête PATCH.", 400);
            }
            if (isset($_GET['action']) && $_GET['action'] === 'resultat') {
                return saisirResultatEtEvaluations($id);
            }
            $data = validateJsonInput();
            if ($data === false) {
                return sendError("Le JSON fourni est mal formé.", 400);
            }
            return updateRencontre($id, $data);
        case 'DELETE':
            // $token = get_bearer_token();
            // if (!$token || !is_jwt_valid($token, JWT_SECRET)) {
            //     return sendError("Authentification requise pour supprimer une ressource.", 401);
            // }
            if (!$id) {
                return sendError("L'ID est obligatoire pour une requête DELETE.", 400);
            }
            return deleteRencontre($id);
        case "OPTIONS":
            header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            http_response_code(204);
            exit;
        default:
            return sendError("Méthode HTTP non supportée.", 405);
    }
}

echo main();
