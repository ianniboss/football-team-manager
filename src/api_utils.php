<?php

require_once __DIR__ . '/../../config.php'; // contient JWT_SECRET


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
    return json_decode($json, true);
}

function validateId($id)
{
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id === false || $id <= 0) return null;
    return $id;
}

/**
 * Vérifie si le token est présent et valide.
 * Si non, envoie un 401 et arrête tout.
 */
function checkAuth()
{
    $token = get_bearer_token();

    if (!$token || !is_jwt_valid($token, JWT_SECRET)) {
        return false;
    }
    return decode_jwt_payload($token);
}
