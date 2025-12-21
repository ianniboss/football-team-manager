<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/connexion.php");
    exit;
}

require_once __DIR__ . '/../../modele/JoueurDAO.php';

if (isset($_POST['id_joueur']) || isset($_GET['id'])) {
    $id = isset($_POST['id_joueur']) ? $_POST['id_joueur'] : $_GET['id'];

    $dao = new JoueurDAO();
    $dao->supprimerJoueur($id);
}

header("Location: ObtenirTousLesJoueurs.php");
exit;
?>