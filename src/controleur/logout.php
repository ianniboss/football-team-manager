<?php
session_start();
session_destroy();
header("Location: ../vue/connexion.php");
exit;
?>