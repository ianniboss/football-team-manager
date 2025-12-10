<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/DAO/RencontreDAO.php';

if (isset($_POST['id_rencontre']) || isset($_GET['id'])) {
    $id = isset($_POST['id_rencontre']) ? $_POST['id_rencontre'] : $_GET['id'];
    
    $dao = new RencontreDAO();
    // supprimer les participations liées 
    $dao->supprimerRencontre($id);
}

header("Location: ObtenirToutesLesRencontres.php");
exit;
?>