<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
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

$dao = new RencontreDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_rencontre'];
    $date = $_POST['date_rencontre'];
    $heure = $_POST['heure'];
    $adresse = htmlspecialchars($_POST['adresse']);
    $equipe = htmlspecialchars($_POST['nom_equipe_adverse']);
    $lieu = $_POST['lieu'];
    $resultat = isset($_POST['resultat']) ? $_POST['resultat'] : null;

    $imageStade = null;
    if (isset($_FILES['image_stade']) && $_FILES['image_stade']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../modele/img/matchs/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $oldMatch = $dao->getRencontreById($id);
        if (!empty($oldMatch['image_stade'])) {
            $oldImagePath = $uploadDir . $oldMatch['image_stade'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $extension = pathinfo($_FILES['image_stade']['name'], PATHINFO_EXTENSION);
        $imageStade = sanitizeStadiumName($adresse) . '.' . $extension;

        $targetPath = $uploadDir . $imageStade;
        move_uploaded_file($_FILES['image_stade']['tmp_name'], $targetPath);
    }

    $dao->modifierRencontre($id, $date, $heure, $adresse, $equipe, $lieu, $resultat, $imageStade);

    header("Location: RechercherUneRencontre.php?id=" . $id);
    exit;

} elseif (isset($_GET['id'])) {
    $rencontre = $dao->getRencontreById($_GET['id']);
    require __DIR__ . '/../../vue/rencontres/formRencontre.php';
}
?>