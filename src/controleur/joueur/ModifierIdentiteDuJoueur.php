<?php
session_start();
// Gère la modification des infos générales (Nom, Prénom, Taille, etc.).

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../modele/DAO/JoueurDAO.php';
$dao = new JoueurDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enregistrement des modifications
    $id = $_POST['id_joueur'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $licence = htmlspecialchars($_POST['num_licence']);
    $date_naissance = $_POST['date_naissance'];
    $taille = $_POST['taille'];
    $poids = $_POST['poids'];
    $statut = $_POST['statut']; // On garde le statut ici aussi si le formulaire est global

    $dao->modifierJoueur($id, $nom, $prenom, $licence, $date_naissance, $taille, $poids, $statut);

    // Retour au détail du joueur
    header("Location: ObtenirUnJoueur.php?id=" . $id);
    exit;

} elseif (isset($_GET['id'])) {
    // Affichage du formulaire pré-rempli
    $joueur = $dao->getJoueurById($_GET['id']);
    // On réutilise le même formulaire que pour l'ajout, mais avec les valeurs remplies
    require __DIR__ . '/../../vue/joueurs/ficheJoueur.php';
}
?>