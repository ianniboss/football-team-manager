<?php
require_once __DIR__ . '/DAO.php';
require_once __DIR__ . '/../jwt_utils.php';
require_once __DIR__ . '/../api_utils.php';
require_once __DIR__ . '/../../../config.php'; // contient JWT_SECRET
$dao = new DAO();

function main()
{
    global $dao;
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method) {
        case "OPTIONS":
            http_response_code(204);
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            return '';
        case "POST":
            $data = validateJsonInput();
            if ($data === false) {
                return sendError("Le JSON fourni est mal formé.", 400);
            }

            // création d'utilisateurs
            // if (isset($data['login_admin'], $data['password_admin'], $data['login_guest'], $data['password_guest'])) {
            //     try {
            //         $dao->createUser($data['login_admin'], $data['password_admin'], 'admin');
            //         $dao->createUser($data['login_guest'], $data['password_guest'], 'guest');
            //         return sendSuccess("Utilisateurs admin et guest créés");
            //     } catch (Exception $e) {
            //         return sendError("Erreur lors de la création : " . $e->getMessage(), 500);
            //     }
            // }

            if (!isset($data['login']) || !isset($data['password'])) {
                return sendError("Les champs 'login' et 'password' sont requis.", 400);
            }
            if ($dao->verifyUser($data['login'], $data['password'])) {
                $header = ['alg' => 'HS256', 'typ' => 'JWT'];
                $payload = ['login' => $data['login'], 'role' => $dao->getUserRole($data['login']), 'exp' => time() + 900]; // Token valide pendant 15 minutes
                $token = generate_jwt($header, $payload, JWT_SECRET);
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/json; charset=utf-8');
                return sendSuccess(["status" => "success", "status_code" => 200, "status_message" => "[R401 REST AUTH] : Authentification OK", "data" => $token]);
            } else {
                return sendError("Login ou mot de passe incorrect.", 401);
            }
        default:
            return sendError("Méthode HTTP non autorisée.", 405);
    }
}
echo main();
