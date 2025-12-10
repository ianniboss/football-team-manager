<?php
// Gère l'affichage du formulaire et l'enregistrement d'un nouveau match
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/DAO/RencontreDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Traitement ---
    $dao = new RencontreDAO();
    
    // Nettoyage
    $date = $_POST['date_rencontre'];
    $heure = $_POST['heure'];
    $adresse = htmlspecialchars($_POST['adresse']);
    $equipe = htmlspecialchars($_POST['nom_equipe_adverse']);
    $lieu = $_POST['lieu']; // 'Domicile' ou 'Exterieur'

    $dao->ajouterRencontre($date, $heure, $adresse, $equipe, $lieu);

    header("Location: ObtenirToutesLesRencontres.php");
    exit;

} else {
    // --- Affichage ---
    require __DIR__ . '/../../vue/rencontres/formRencontre.php';
}
?>