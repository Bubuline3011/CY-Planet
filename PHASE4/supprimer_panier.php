<?php
session_start();

// Supprime uniquement la commande du panier
unset($_SESSION['commande']);

// Redirige vers la page d’accueil (ou une autre)
header("Location: acceuil.php");
exit;
?>

