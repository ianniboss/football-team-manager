<?php
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';
require_once __DIR__ . '/../../jwt_utils.php';
require_once __DIR__ . '/../../api_utils.php';
require_once __DIR__ . '/../../../../config.php'; // contient la variable JWT_SECRET

$joueurDAO = new JoueurDAO();
$commentaireDAO = new CommentaireDAO();

function getJoueur($id)
{
    global $joueurDAO, $commentaireDAO;
    $id = validateId($id);
    if ($id === null) {
        return sendError("L'ID doit être un entier valide et positif.", 400);
    }
    $joueur = $joueurDAO->getJoueurById($id);

    if (!$joueur) {
        return sendError("Joueur introuvable.", 404);
    }
    // "Data Aggregation"... Plutôt que d'atomiser les ressources on centralise pour
    // que la réponse soit la plus utile possible pour la vue
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

    // utilisation de trim et vérif de types au lieu de htmlspecialchars,
    // on utilise htmlspecialchars uniquement lors de l'affichage de la page
    $nom = trim($data['nom'] ?? '');
    $prenom = trim($data['prenom'] ?? '');

    if (empty($nom) || empty($prenom)) {
        return sendError("Le nom et le prénom sont obligatoires.");
    }

    $num_licence = trim($data['num_licence'] ?? '');
    $date_naissance = trim($data['date_naissance'] ?? '');
    $taille = isset($data['taille']) ? (int)$data['taille'] : 0;
    $poids = isset($data['poids']) ? (int)$data['poids'] : 0;
    $statut = trim($data['statut'] ?? 'Actif');

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

/**
 * PUT : Remplacement total
 * Les champs non fournis dans le JSON sont réinitialisés.
 */
function putJoueur($id, $data)
{
    global $joueurDAO;

    $id = validateId($id);
    if ($id === null) return sendError("L'ID doit être un entier valide.", 400);

    $existing = $joueurDAO->getJoueurById($id);
    if (!$existing) return sendError("Joueur non trouvé.", 404);

    if (!isset($data['nom']) || trim($data['nom']) === '' || !isset($data['prenom']) || trim($data['prenom']) === '') {
        return sendError("Le nom et le prénom sont obligatoires pour un remplacement complet (PUT).", 400);
    }

    $nom = trim($data['nom']);
    $prenom = trim($data['prenom']);
    $licence = trim($data['num_licence'] ?? '');
    $date_n = trim($data['date_naissance'] ?? '');
    $taille = isset($data['taille']) ? (int)$data['taille'] : 0;
    $poids = isset($data['poids']) ? (int)$data['poids'] : 0;
    $statut = trim($data['statut'] ?? 'Actif');

    $image = $existing['image'];

    $joueurDAO->modifierJoueur($id, $nom, $prenom, $licence, $date_n, $taille, $poids, $statut, $image);
    return sendSuccess($joueurDAO->getJoueurById($id));
}

/**
 * PATCH : Modification partielle
 * Seules les clés envoyées dans le JSON sont modifiées.
 */
function patchJoueur($id, $data)
{
    global $joueurDAO;

    $id = validateId($id);
    if ($id === null) return sendError("L'ID doit être un entier valide.", 400);

    $existing = $joueurDAO->getJoueurById($id);
    if (!$existing) return sendError("Joueur non trouvé.", 404);

    // On utilise array_key_exists pour détecter la présence de la clé, même si la valeur est null
    // mieux que isset vu que si l'user souhaite mettre null isset devient false
    $nom = array_key_exists('nom', $data) ? trim($data['nom']) : $existing['nom'];
    $prenom = array_key_exists('prenom', $data) ? trim($data['prenom']) : $existing['prenom'];
    $licence = array_key_exists('num_licence', $data) ? trim($data['num_licence']) : $existing['num_licence'];
    $date_n = array_key_exists('date_naissance', $data) ? trim($data['date_naissance']) : $existing['date_naissance'];
    $taille = array_key_exists('taille', $data) ? (int)$data['taille'] : $existing['taille'];
    $poids = array_key_exists('poids', $data) ? (int)$data['poids'] : $existing['poids'];
    $statut = array_key_exists('statut', $data) ? trim($data['statut']) : $existing['statut'];

    $image = $existing['image'];

    $joueurDAO->modifierJoueur($id, $nom, $prenom, $licence, $date_n, $taille, $poids, $statut, $image);
    return sendSuccess($joueurDAO->getJoueurById($id));
}

function updateJoueurStatut($id, $nouveauStatut)
{
    global $joueurDAO;
    $id = validateId($id);
    if ($id === null) return sendError("L'ID doit être un entier valide.", 400);

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
        $joueur['image']
    );

    return sendSuccess($joueurDAO->getJoueurById($id));
}

function deleteJoueur($id)
{
    global $joueurDAO;
    $id = validateId($id);
    if ($id === null) return sendError("L'ID doit être un entier valide et positif.", 400);
    if (!$joueurDAO->getJoueurById($id)) return sendError("Joueur inexistant.", 404);

    $joueurDAO->supprimerJoueur($id);
    return sendSuccess(null, 204);
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
        case "GET":
            return ($id) ? getJoueur($id) : getAll();

        case "POST":
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            $data = validateJsonInput();
            if ($data === false) return sendError("Le JSON fourni est mal formé.", 400);
            return creerJoueur($data);

        case "PUT":
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            if (!$id) return sendError("ID manquant pour le PUT.", 400);

            $data = validateJsonInput();
            if ($data === false) return sendError("Le JSON fourni est mal formé.", 400);
            return putJoueur($id, $data);

        case "PATCH":
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            if (!$id) return sendError("ID manquant pour le PATCH.", 400);

            // Action spécifique : mise à jour rapide du statut via l'URL
            if (isset($_GET['statut'])) {
                return updateJoueurStatut($id, $_GET['statut']);
            }

            // Sinon, mise à jour partielle via le JSON
            $data = validateJsonInput();
            if ($data === false) return sendError("Le JSON fourni est mal formé.", 400);
            return patchJoueur($id, $data);

        case "DELETE":
            if ($role !== 'admin') {
                return sendError("Droits insuffisants. Seul un administrateur peut modifier ces données.", 403);
            }
            if (!$id) return sendError("L'ID est obligatoire pour une requête DELETE.", 400);
            return deleteJoueur($id);


        case "OPTIONS":
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            http_response_code(204);
            exit;

        default:
            return sendError("Méthode non supportée", 405);
    }
}

echo main();
