<?php
session_start();
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dao = new JoueurDAO();

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

        $dao->ajouterJoueur($nom, $prenom, $licence, $date_naissance, $taille, $poids, $statut, null);
        $playerId = $dao->getLastInsertId();

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = sanitizeFilename($prenom) . '_' . sanitizeFilename($nom) . '_' . $playerId . '.' . $extension;

        $targetPath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $dao->modifierJoueur($playerId, $nom, $prenom, $licence, $date_naissance, $taille, $poids, $statut, $imageName);
        }

        header("Location: ObtenirTousLesJoueurs.php");
        exit;
    } else {
        $dao->ajouterJoueur($nom, $prenom, $licence, $date_naissance, $taille, $poids, $statut, null);
        header("Location: ObtenirTousLesJoueurs.php");
        exit;
    }

} else {
    require __DIR__ . '/../../vue/joueurs/ajouterJoueur.php';
}
?>