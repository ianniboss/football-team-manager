<?php
require_once __DIR__ . '/DAO.php';
require_once __DIR__ . '/../jwt_utils.php';
require_once __DIR__ . '/../api_utils.php';
require_once __DIR__ . '/../../config.php';
$dao = new DAO();

// --- CONFIGURATION CORS ULTRA-ROBUSTE ---

$allowed_origins = [
    'https://ribou.fr',
    'https://www.ribou.fr'
];

$http_origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// On vérifie si l'origine est dans notre liste
if (in_array($http_origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $http_origin");
} else {
    // Si ce n'est pas dans la liste (ou premier appel sans origin), 
    // on autorise quand même l'un des deux par défaut
    header("Access-Control-Allow-Origin: https://ribou.fr");
}

// INDISPENSABLE pour la gestion dynamique des origines
header("Vary: Origin");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400"); // Cache la réponse OPTIONS pendant 24h

// Intercepter OPTIONS avant tout traitement
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function main()
{
    global $dao;
    $http_method = $_SERVER['REQUEST_METHOD'];

    switch ($http_method) {
        case "GET":
            header('Content-Type: application/json');
            return json_encode(["status" => "active", "message" => "R401 Authentification Service is running."]);

        case "POST":
            $data = validateJsonInput();
            if ($data === false) {
                return sendError("Le JSON fourni est mal formé.", 400);
            }

            if (!isset($data['login']) || !isset($data['password'])) {
                return sendError("Les champs 'login' et 'password' sont requis.", 400);
            }

            if ($dao->verifyUser($data['login'], $data['password'])) {
                $header = ['alg' => 'HS256', 'typ' => 'JWT'];
                $payload = ['login' => $data['login'], 'role' => $dao->getUserRole($data['login']), 'exp' => time() + 900];
                $token = generate_jwt($header, $payload, JWT_SECRET);
                header('Content-Type: application/json; charset=utf-8');
                return sendSuccess(["status" => "success", "data" => $token]);
            } else {
                return sendError("Login ou mot de passe incorrect.", 401);
            }

        default:
            return sendError("Méthode HTTP non autorisée.", 405);
    }
}

echo main();
