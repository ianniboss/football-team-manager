<?php
session_start();

// Identifiants stockés en clair (exemple)
$valid_users = [
    "admin" => "1234",
    "lucas" => "password",
    "user" => "iutinfo",
];

// Vérification du formulaire
if (!isset($_POST['username'], $_POST['password'])) {
    header("Location: ../vue/index.php");
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

// Vérifier si l'utilisateur existe et que le mot de passe correspond
if (array_key_exists($username, $valid_users) && $valid_users[$username] === $password) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    header("Location: ../vue/accueil.php");
} else {
    header("Location: ../vue/index.php?error=invalid");
    exit;
}
?>