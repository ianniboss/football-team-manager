<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';

if (isset($_POST['id_rencontre']) || isset($_GET['id'])) {
    $id = isset($_POST['id_rencontre']) ? $_POST['id_rencontre'] : $_GET['id'];

    $dao = new RencontreDAO();
    $dao->supprimerRencontre($id);
}

header("Location: ObtenirToutesLesRencontres.php");
exit;
?>