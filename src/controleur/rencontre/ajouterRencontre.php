<?php
// Gère l'affichage du formulaire et l'enregistrement d'un nouveau match
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';

function sanitizeStadiumName($adresse)
{
    $parts = explode(',', $adresse);
    $stadiumName = trim($parts[0]);

    $stadiumName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $stadiumName);
    $stadiumName = strtolower(str_replace(' ', '_', $stadiumName));
    $stadiumName = preg_replace('/[^a-z0-9_]/', '', $stadiumName);
    return $stadiumName;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dao = new RencontreDAO();

    $date = $_POST['date_rencontre'];
    $heure = $_POST['heure'];
    $adresse = htmlspecialchars($_POST['adresse']);
    $equipe = htmlspecialchars($_POST['nom_equipe_adverse']);
    $lieu = $_POST['lieu'];

    $imageStade = null;
    if (isset($_FILES['image_stade']) && $_FILES['image_stade']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../modele/img/matchs/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image_stade']['name'], PATHINFO_EXTENSION);
        $imageStade = sanitizeStadiumName($adresse) . '.' . $extension;

        $targetPath = $uploadDir . $imageStade;
        move_uploaded_file($_FILES['image_stade']['tmp_name'], $targetPath);
    }

    $dao->ajouterRencontre($date, $heure, $adresse, $equipe, $lieu, $imageStade);

    header("Location: ObtenirToutesLesRencontres.php");
    exit;

} else {
    require __DIR__ . '/../../vue/rencontres/formRencontre.php';
}
?>