<?php
// Activé temporairement pour voir l'erreur réelle en console JS
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../modele/ParticiperDAO.php';
require_once __DIR__ . '/../../jwt_utils.php';
require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../../config.php'; // contient JWT_SECRET

$rencontreDAO  = new RencontreDAO();
$participerDAO = new ParticiperDAO();

/**
 * Nettoie le nom du stade (première partie de l'adresse) pour un nom de fichier.
 * Ex : "Stade Municipal, 31000 Toulouse" → "stade_municipal"
 */
function sanitizeStadiumName($adresse)
{
    $parts = explode(',', $adresse);
    $name  = trim($parts[0]);

    // Remplacement de iconv (souvent source de 500) par un nettoyage plus simple
    $name = str_replace([' ', '\''], '_', $name);
    $name = preg_replace('/[^A-Za-z0-9\_]/', '', $name);

    return strtolower($name);
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
    return sendSuccess(array_values($rencontres));
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
function creerRencontre()
{
    global $rencontreDAO;

    $date_rencontre = trim($_POST['date_rencontre'] ?? '');
    $heure = trim($_POST['heure'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $nom_equipe_adverse = trim($_POST['nom_equipe_adverse'] ?? '');
    $lieu = trim($_POST['lieu'] ?? '');

    if (empty($date_rencontre) || empty($nom_equipe_adverse)) {
        return sendError("La date et l'équipe adverse sont obligatoires.", 400);
    }

    $imageStade = gererUploadImageStade($adresse);
    $rencontreDAO->ajouterRencontre($date_rencontre, $heure, $adresse, $nom_equipe_adverse, $lieu, $imageStade);
    $newId = $rencontreDAO->getLastInsertId();

    return sendSuccess($rencontreDAO->getRencontreById($newId), 201);
}

/**
 * Mise à jour via POST (Multipart) pour supporter l'upload d'image.
 */
function modifierRencontreMultipart($id)
{
    global $rencontreDAO;
    $existing = $rencontreDAO->getRencontreById($id);
    if (!$existing) return sendError("Rencontre introuvable.", 404);

    // Sécurité : pas de modif sur match passé
    if (new DateTime($existing['date_rencontre']) < new DateTime('today')) {
        return sendError("Impossible de modifier un match passé.", 403);
    }

    $date_rencontre = trim($_POST['date_rencontre'] ?? $existing['date_rencontre']);
    $heure = trim($_POST['heure'] ?? $existing['heure']);
    $adresse = trim($_POST['adresse'] ?? $existing['adresse']);
    $nom_equipe_adverse = trim($_POST['nom_equipe_adverse'] ?? $existing['nom_equipe_adverse']);
    $lieu = trim($_POST['lieu'] ?? $existing['lieu']);

    $ancienneImage = $existing['image_stade'] ?? null;
    $imageStade = gererUploadImageStade($adresse, $ancienneImage) ?? $ancienneImage;

    $rencontreDAO->modifierRencontre($id, $date_rencontre, $heure, $adresse, $nom_equipe_adverse, $lieu, $existing['resultat'], $imageStade);
    return sendSuccess($rencontreDAO->getRencontreById($id));
}

/**
 * PUT /api.php?id=X
 * Remplacement total de la ressource.
 * Tous les champs obligatoires doivent être présents. Les champs omis sont mis à null/défaut.
 */
function putRencontre($id, $data)
{
    global $rencontreDAO;

    $id = validateId($id);
    if ($id === null) return sendError("ID invalide.", 400);

    $existing = $rencontreDAO->getRencontreById($id);
    if (!$existing) return sendError("Rencontre introuvable.", 404);

    $matchDate = new DateTime($existing['date_rencontre']);
    $today = new DateTime('today');
    if ($matchDate < $today) return sendError("Impossible de modifier un match passé.", 403);

    $champsObligatoires = ['date_rencontre', 'heure', 'adresse', 'nom_equipe_adverse', 'lieu'];
    foreach ($champsObligatoires as $champ) {
        if (!isset($data[$champ]) || trim($data[$champ]) === '') {
            return sendError("Le champ '$champ' est obligatoire pour un remplacement complet (PUT).", 400);
        }
    }

    $date_rencontre = trim($data['date_rencontre']);
    $heure = trim($data['heure']);
    $adresse = trim($data['adresse']);
    $nom_equipe_adverse = trim($data['nom_equipe_adverse']);
    $lieu = trim($data['lieu']);

    // Si 'resultat' n'est pas envoyé dans un PUT, la règle REST veut qu'on le réinitialise (null)
    $resultat = $data['resultat'] ?? null;

    $ancienneImage = $existing['image_stade'] ?? null;
    $imageStade = gererUploadImageStade($adresse, $ancienneImage) ?? $ancienneImage;

    $rencontreDAO->modifierRencontre($id, $date_rencontre, $heure, $adresse, $nom_equipe_adverse, $lieu, $resultat, $imageStade);

    return sendSuccess($rencontreDAO->getRencontreById($id));
}

/**
 * PATCH /api.php?id=X
 * Modification partielle. Remplace uniquement les clés fournies dans le JSON.
 */
function patchRencontre($id, $data)
{
    global $rencontreDAO;

    $id = validateId($id);
    if ($id === null) return sendError("ID invalide.", 400);

    $existing = $rencontreDAO->getRencontreById($id);
    if (!$existing) return sendError("Rencontre introuvable.", 404);

    $matchDate = new DateTime($existing['date_rencontre']);
    $today = new DateTime('today');
    if ($matchDate < $today) return sendError("Impossible de modifier un match passé.", 403);

    $date_rencontre = array_key_exists('date_rencontre', $data) ? trim($data['date_rencontre']) : $existing['date_rencontre'];
    $heure = array_key_exists('heure', $data) ? trim($data['heure']) : $existing['heure'];
    $adresse = array_key_exists('adresse', $data) ? trim($data['adresse']) : $existing['adresse'];
    $nom_equipe_adverse = array_key_exists('nom_equipe_adverse', $data) ? trim($data['nom_equipe_adverse']) : $existing['nom_equipe_adverse'];
    $lieu = array_key_exists('lieu', $data) ? trim($data['lieu']) : $existing['lieu'];
    $resultat = array_key_exists('resultat', $data) ? $data['resultat'] : $existing['resultat'];

    // Si on a changé l'adresse, on gère l'image, sinon on garde l'ancienne.
    $ancienneImage = $existing['image_stade'] ?? null;
    $imageStade = gererUploadImageStade($adresse, $ancienneImage) ?? $ancienneImage;

    $rencontreDAO->modifierRencontre($id, $date_rencontre, $heure, $adresse, $nom_equipe_adverse, $lieu, $resultat, $imageStade);

    return sendSuccess($rencontreDAO->getRencontreById($id));
}

/**
 * PATCH /api.php?id=X&action=resultat
 * Saisit le résultat d'un match terminé et les évaluations des joueurs.
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
    global $rencontreDAO, $participerDAO;

    $id = validateId($id);
    if ($id === null) {
        return sendError("L'ID doit être un entier valide et positif.", 400);
    }

    $rencontre = $rencontreDAO->getRencontreById($id);
    if (!$rencontre) {
        return sendError("Rencontre introuvable.", 404);
    }

    // 1. D'ABORD : Supprimer les participations (feuille de match) pour éviter l'erreur de clé étrangère
    $participerDAO->supprimerParticipationsParMatch($id);

    // 2. Supprimer l'image du stade si elle existe
    if (!empty($rencontre['image_stade'])) {
        $imagePath = __DIR__ . '/../../modele/img/matchs/' . $rencontre['image_stade'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // 3. ENFIN : Supprimer la rencontre
    if ($rencontreDAO->supprimerRencontre($id)) {
        return sendSuccess(null, 204);
    } else {
        return sendError("Erreur lors de la suppression en base de données.", 500);
    }
}

// --- Point d'entrée principal ---

function main()
{
    $user = checkAuth();
    if (!$user) {
        echo sendError("Accès refusé. Token invalide ou expiré.", 401);
        exit;
    }
    $role = $user['role']; // 'admin' ou 'guest'
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    switch ($method) {
        case 'GET':
            return ($id) ? getRencontre($id) : getAll();
        case 'POST':
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }

            if (empty($_POST)) {
                return sendError("Données de formulaire manquantes.", 400);
            }

            // Si un ID est présent dans l'URL ou dans le corps du formulaire, c'est une modif
            $targetId = validateId($id ?? $_POST['id_rencontre'] ?? null);
            if ($targetId) {
                return modifierRencontreMultipart($targetId);
            }

            return creerRencontre();
        case 'PUT':
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            if (!$id) return sendError("L'ID est obligatoire pour un PUT.", 400);
            $data = validateJsonInput();
            if ($data === false) return sendError("JSON mal formé.", 400);
            return putRencontre($id, $data);

        case 'PATCH':
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            if (!$id) return sendError("L'ID est obligatoire pour un PATCH.", 400);
            // Si on a l'action spécifique "resultat" (saisie de fin de match avec évaluations)
            if (isset($_GET['action'])) {
                if ($_GET['action'] === 'resultat') {
                    return saisirResultatEtEvaluations($id);
                }
                return sendError("Action non reconnue. Seule 'resultat' est supportée.", 400);
            }
            // Sinon, c'est une modification partielle classique
            $data = validateJsonInput();
            if ($data === false) return sendError("JSON mal formé.", 400);
            return patchRencontre($id, $data);
        case 'DELETE':
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
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

// On s'assure qu'aucun contenu parasite n'est envoyé avant ou après
$output = main();
if ($output !== null) {
    echo $output;
}
