<?php
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';
require_once __DIR__ . '/../jwt_utils.php';
require_once __DIR__ . '/../../../../config.php'; // contient la variable JWT_SECRET

$joueurDAO = new JoueurDAO();
$commentaireDAO = new CommentaireDAO();

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
    $error = ['error' => $message];
    return json_encode($error, JSON_UNESCAPED_UNICODE);
}

function sanitizeFilename($string)
{
    $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
    $string = strtolower(str_replace(' ', '_', $string));
    $string = preg_replace('/[^a-z0-9_]/', '', $string);
    return $string;
}

function validateJsonInput()
{
    $json = file_get_contents('php://input');
    if (empty($json)) return null;
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Optionnel : affiche l'erreur exacte pendant que tu développes
        error_log("Erreur JSON : " . json_last_error_msg());
        return false;
    }
    return $data;
}

// --- Logique métier ---

function getJoueur($id)
{
    global $joueurDAO, $commentaireDAO;
    $id = intval($id);
    $joueur = $joueurDAO->getJoueurById($id);

    if (!$joueur) {
        return sendError("Joueur introuvable.", 404);
    }

    $commentaires = $commentaireDAO->getCommentairesByJoueur($id);
    return sendSuccess([
        'joueur' => $joueur,
        'commentaires' => $commentaires
    ]);
}

function getAll()
{
    global $joueurDAO;
    $joueurs = $joueurDAO->getJoueurs();

    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    $statusFilter = isset($_GET['statut']) ? $_GET['statut'] : '';

    if (!empty($searchQuery) || !empty($statusFilter)) {
        $joueurs = array_filter($joueurs, function ($joueur) use ($searchQuery, $statusFilter) {
            $matchSearch = true;
            $matchStatus = true;

            if (!empty($searchQuery)) {
                $searchLower = strtolower($searchQuery);
                $nomComplet = strtolower($joueur['prenom'] . ' ' . $joueur['nom']);
                $licence = strtolower($joueur['num_licence']);
                $matchSearch = (strpos($nomComplet, $searchLower) !== false) || (strpos($licence, $searchLower) !== false);
            }

            if (!empty($statusFilter)) {
                $matchStatus = ($joueur['statut'] === $statusFilter);
            }

            return $matchSearch && $matchStatus;
        });
    }

    return sendSuccess(array_values($joueurs));
}

function creerJoueur($data)
{
    global $joueurDAO;

    $nom = htmlspecialchars($data['nom'] ?? '');
    $prenom = htmlspecialchars($data['prenom'] ?? '');

    if (empty($nom) || empty($prenom)) {
        return sendError("Le nom et le prénom sont obligatoires.");
    }
    $num_licence = $data['num_licence'] ?? '';
    $date_naissance = $data['date_naissance'] ?? '';
    $taille = $data['taille'] ?? 0;
    $poids = $data['poids'] ?? 0;
    $statut = $data['statut'] ?? 'Actif';

    $joueurDAO->ajouterJoueur(
        $nom,
        $prenom,
        $num_licence,
        $date_naissance,
        $taille,
        $poids,
        $statut,
        null
    );

    $playerId = $joueurDAO->getLastInsertId();

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../modele/img/players/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = sanitizeFilename($prenom) . '_' . sanitizeFilename($nom) . '_' . $playerId . '.' . $extension;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
            $joueurDAO->modifierJoueur($playerId, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $imageName);
        }
    }

    return sendSuccess($joueurDAO->getJoueurById($playerId), 201);
}

function updateJoueur($id, $data)
{
    global $joueurDAO;

    // return json_encode([
    //     "DEBUG_INFO" => "Je suis dans updateJoueur",
    //     "ID_RECU" => $id,
    //     "DATA_RECUE" => $data,
    //     "TYPE_DATA" => gettype($data),
    //     "PHP_INPUT" => file_get_contents('php://input')
    // ]);
    $existing = $joueurDAO->getJoueurById($id);
    if (!$existing) return sendError("Joueur non trouvé.", 404);

    // Fusion des données existantes avec les nouvelles (logique PUT/PATCH simplifiée)
    $nom = $data['nom'] ?? $existing['nom'];
    $prenom = $data['prenom'] ?? $existing['prenom'];
    $licence = $data['num_licence'] ?? $existing['num_licence'];
    $date_n = $data['date_naissance'] ?? $existing['date_naissance'];
    $taille = $data['taille'] ?? $existing['taille'];
    $poids = $data['poids'] ?? $existing['poids'];
    $statut = $data['statut'] ?? $existing['statut'];
    $image = $existing['image']; // L'image via json est complexe, on garde l'actuelle ici. Peut-être trouver un moyen de poster une photo
    // et passer l'url dans le data ?
    $joueurDAO->modifierJoueur($id, $nom, $prenom, $licence, $date_n, $taille, $poids, $statut, $image);
    return sendSuccess($joueurDAO->getJoueurById($id));
}

function updateJoueurStatut($id, $nouveauStatut)
{
    global $joueurDAO;

    $statutsAutorises = ['Actif', 'Blessé', 'Suspendu', 'Absent'];
    $joueur = $joueurDAO->getJoueurById($id);
    if (!$joueur) return sendError("Joueur non trouvé.", 404);
    $nouveauStatut = ucfirst(strtolower(trim($nouveauStatut)));
    if (!in_array($nouveauStatut, $statutsAutorises, true)) {
        return sendError("Statut invalide. Les options sont : " . implode(', ', $statutsAutorises), 400);
    }
    $joueurDAO->modifierJoueur(
        $id,
        $joueur['nom'],
        $joueur['prenom'],
        $joueur['num_licence'],
        $joueur['date_naissance'],
        $joueur['taille'],
        $joueur['poids'],
        $nouveauStatut,
    );

    return sendSuccess($joueurDAO->getJoueurById($id));
}

function deleteJoueur($id)
{
    global $joueurDAO;
    if (!$joueurDAO->getJoueurById($id)) return sendError("Joueur inexistant.", 404);
    $joueurDAO->supprimerJoueur($id);
    return sendSuccess(null, 204);
}

// --- Point d'entrée Principal ---

function main()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    switch ($method) {
        case "GET":
            return ($id) ? getJoueur($id) : getAll();

        case "POST":
            // Vérification JWT
            // $token = get_bearer_token();
            // if (!$token || !is_jwt_valid($token, JWT_SECRET)) return sendError("Non autorisé", 401);
            $data = validateJsonInput();
            if ($data === false) {
                return sendError("Le JSON fourni est mal formé.", 400);
            }
            return creerJoueur($data);

        case "PATCH":
            // $token = get_bearer_token();
            // if (!$token || !is_jwt_valid($token, JWT_SECRET)) return sendError("Non autorisé", 401);

            if (!$id) return sendError("ID manquant", 400);

            // Si l'action est présente (ex: ?id=5&action=Blessé)
            if (isset($_GET['statut'])) {
                return updateJoueurStatut($id, $_GET['statut']);
            } else {
                $data = validateJsonInput();
                return updateJoueur($id, $data);
            }

        case "DELETE":
            // $token = get_bearer_token();
            // if (!$token || !is_jwt_valid($token, JWT_SECRET)) return sendError("Non autorisé", 401);
            if (!$id) return sendError("ID manquant", 400);
            return deleteJoueur($id);

        case "OPTIONS":
            header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            http_response_code(204);
            exit;

        default:
            return sendError("Méthode non supportée", 405);
    }
}

echo main();
