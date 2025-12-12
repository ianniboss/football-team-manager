<?php
// afficher la liste principale
session_start();

// 1. Vérification de sécurité (Authentification)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Inclusion du DAO
require_once __DIR__ . '/../../modele/JoueurDAO.php';

// 3. Récupération des données
$dao = new JoueurDAO();
$joueurs = $dao->getJoueurs();
$_SESSION['joueurs'] = $joueurs;

header("Location: ../../vue/joueurs/listeJoueurs.php");
exit;
?>