<?php
require_once __DIR__ . '/../../modele/CommentaireDAO.php';
require_once __DIR__ . '/../../jwt_utils.php';
require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../../config.php';

$commentaireDAO = new CommentaireDAO();

// --- Logique Métier ---

function ajouterCommentaire($data)
{
    global $commentaireDAO;

    $id_joueur = validateId($data['id_joueur']);
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

    $id_commentaire = validateId($id);
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
    $user = checkAuth();
    if (!$user) echo sendError("Accès refusé. Token invalide ou expiré.", 401);
    $role = $user['role']; // 'admin' ou 'guest'
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    switch ($method) {
        case 'POST':
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            // on passe l'id du joueur dans le data, meilleur pratique REST pour du POST
            $data = validateJsonInput();
            if ($data === false) return sendError("JSON mal formé.", 400);
            return ajouterCommentaire($data);

        case 'DELETE':
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
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
