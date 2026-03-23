<?php
require_once __DIR__ . '/DAO.php';
require_once __DIR__ . '/jwt_utils.php';
require_once __DIR__ . '/config.php'; // contient la variable $secret
$dao = new DAO();

function sendSuccess($data, $code = 200) {
    http_response_code($code);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($data);
}

function sendError($message, $code = 400) {
    http_response_code($code);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    $error = ['error' => $message];
    return json_encode($error);
}

function validateJsonInput() {
    $json = file_get_contents('php://input');
    if (empty($json)) {
        return null;
    }
    $data = json_decode($json, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }
    return $data;
}

function main() {
    global $dao;
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        case "OPTIONS":
            http_response_code(204);
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            return '';
        case "POST" :
            $data = validateJsonInput();
            if ($data === false) {
                return sendError("Le JSON fourni est mal formé.", 400);
            }
            if (!isset($data['login']) || !isset($data['password'])) {
                return sendError("Les champs 'login' et 'password' sont requis.", 400);
            }
            if ($dao->verifyUser($data['login'], $data['password'])) {
                $header = ['alg' => 'HS256', 'typ' => 'JWT'];
                $payload = ['login' => $data['login'], 'role' => $dao->getUserRole($data['login']), 'exp' => time() + 360]; // Token valide pendant 6 minutes
                $token = generate_jwt($header, $payload, JWT_SECRET);
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/json; charset=utf-8');
                return sendSuccess(["status" => "success", "status_code" => 200, "status_message" => "[R401 REST AUTH] : Authentification OK", "data" => $token]);
            } else {
                return sendError("Login ou mot de passe incorrect.", 401);
            }
        default :
            return sendError("Méthode HTTP non autorisée.", 405);
    }
}
?>