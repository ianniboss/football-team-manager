<?php
// affiche la liste des matchs (passés et à venir)
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../vue/index.php");
    exit;
}

require_once __DIR__ . '/../../modele/RencontreDAO.php';

$dao = new RencontreDAO();
$rencontres = $dao->getRencontres();

// Appel de la vue
require __DIR__ . '/../../vue/rencontres/listeRencontres.php';
?>