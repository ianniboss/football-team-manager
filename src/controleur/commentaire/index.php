<?php
require_once __DIR__ . '/../../modele/CommentaireDAO.php';
require_once __DIR__ . '/../jwt_utils.php';
require_once __DIR__ . '/../../../../config.php';

$commentaireDAO = new CommentaireDAO();

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
    $data = json_decode($json, true);
    return (json_last_error() === JSON_ERROR_NONE) ? $data : false;
}

// --- Logique Métier ---

function ajouterCommentaire($data)
{
    global $commentaireDAO;

    $id_joueur = filter_var($data['id_joueur'] ?? null, FILTER_VALIDATE_INT);
    $texte = trim($data['commentaire'] ?? ''); // On aligne sur le nom du champ dans ton DAO

    if (!$id_joueur || empty($texte)) {
        return sendError("Données incomplètes (id_joueur ou commentaire manquant).", 400);
    }

    $date = date('Y-m-d');
    $success = $commentaireDAO->ajouterCommentaire($id_joueur, $texte, $date);

    if ($success) {
        return sendSuccess(['message' => 'Commentaire ajouté avec succès'], 201);
    }
    return sendError("Erreur lors de l'insertion en base de données.", 500);
}

function supprimerCommentaire($id)
{
    global $commentaireDAO;

    $id_commentaire = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id_commentaire) return sendError("ID de commentaire invalide.", 400);

    $success = $commentaireDAO->supprimerCommentaire($id_commentaire);

    if ($success) {
        return sendSuccess(null, 204);
    }
    return sendError("Erreur lors de la suppression.", 500);
}

// --- Main Routeur ---

function main()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    // authentification quoi qu'il arrive
    /*
    $token = get_bearer_token();
    if (!$token || !is_jwt_valid($token, JWT_SECRET)) {
        return sendError("Authentification requise.", 401);
    }
    */

    switch ($method) {
        case 'POST':
            // on passe l'id du joueur dans le data, meilleur pratique REST pour du POST
            $data = validateJsonInput();
            if ($data === false) return sendError("JSON mal formé.", 400);
            return ajouterCommentaire($data);

        case 'DELETE':
            if (!$id) return sendError("ID du commentaire manquant dans l'URL.", 400);
            return supprimerCommentaire($id);

        case 'OPTIONS':
            header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            http_response_code(204);
            exit;

        default:
            return sendError("Méthode non supportée.", 405);
    }
}

echo main();
