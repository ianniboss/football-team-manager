<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/DAO/JoueurDAO.php';

// On accepte POST pour la sécurité (éviter qu'un lien supprime par erreur), 
// ou GET si vous utilisez des liens simples <a> (moins sécurisé mais courant en TP)
if (isset($_POST['id_joueur']) || isset($_GET['id'])) {
    $id = isset($_POST['id_joueur']) ? $_POST['id_joueur'] : $_GET['id'];
    
    $dao = new JoueurDAO();
    $dao->supprimerJoueur($id);
}

header("Location: ObtenirTousLesJoueurs.php");
exit;
?>