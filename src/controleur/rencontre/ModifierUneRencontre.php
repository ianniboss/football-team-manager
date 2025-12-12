<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';
$dao = new RencontreDAO();

// modifier les informations générales du match
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_rencontre'];
    $date = $_POST['date_rencontre'];
    $heure = $_POST['heure'];
    $adresse = htmlspecialchars($_POST['adresse']);
    $equipe = htmlspecialchars($_POST['nom_equipe_adverse']);
    $lieu = $_POST['lieu'];
    // On récupère le résultat s'il est posté, sinon on garde l'ancien ou NULL
    $resultat = isset($_POST['resultat']) ? $_POST['resultat'] : null;

    $dao->modifierRencontre($id, $date, $heure, $adresse, $equipe, $lieu, $resultat);

    header("Location: RechercherUneRencontre.php?id=" . $id);
    exit;

} elseif (isset($_GET['id'])) {
    $rencontre = $dao->getRencontreById($_GET['id']);
    require __DIR__ . '/../../vue/rencontres/formRencontre.php';
}
?>