<?php
session_start();
// Gère la modification des infos générales (Nom, Prénom, Taille, etc.).

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/JoueurDAO.php';

function sanitizeFilename($string)
{
    $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
    $string = strtolower(str_replace(' ', '_', $string));
    $string = preg_replace('/[^a-z0-9_]/', '', $string);
    return $string;
}

$dao = new JoueurDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enregistrement des modifications
    $id = $_POST['id_joueur'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $licence = htmlspecialchars($_POST['num_licence']);
    $date_naissance = $_POST['date_naissance'];
    $taille = $_POST['taille'];
    $poids = $_POST['poids'];
    $statut = $_POST['statut'];

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../modele/img/players/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $oldPlayer = $dao->getJoueurById($id);
        if (!empty($oldPlayer['image'])) {
            $oldImagePath = $uploadDir . $oldPlayer['image'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = sanitizeFilename($prenom) . '_' . sanitizeFilename($nom) . '_' . $id . '.' . $extension;

        $targetPath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $dao->modifierJoueur($id, $nom, $prenom, $licence, $date_naissance, $taille, $poids, $statut, $imageName);

    header("Location: ObtenirUnJoueur.php?id=" . $id);
    exit;

} elseif (isset($_GET['id'])) {
    $joueur = $dao->getJoueurById($_GET['id']);
    require __DIR__ . '/../../vue/joueurs/modifierJoueur.php';
}
?>