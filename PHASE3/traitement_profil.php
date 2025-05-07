<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$email = $_SESSION['email'];
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

foreach ($utilisateurs as &$u) {
    if ($u['email'] === $email) {
        // Mise à jour des champs modifiables
        if (isset($_POST['nom'])) $u['nom'] = $_POST['nom'];
        if (isset($_POST['prenom'])) $u['prenom'] = $_POST['prenom'];
        if (isset($_POST['email'])) $u['email'] = $_POST['email'];
        if (isset($_POST['motdepasse'])) $u['motdepasse'] = $_POST['motdepasse'];
        if (isset($_POST['age'])) $u['age'] = $_POST['age'];
        if (isset($_POST['telephone'])) $u['telephone'] = $_POST['telephone'];

        // Si email a changé, il faut aussi le changer dans $_SESSION
        $_SESSION['email'] = $u['email'];
        break;
    }
}

// Réécriture du fichier JSON
file_put_contents("data/utilisateur.json", json_encode($utilisateurs, JSON_PRETTY_PRINT));

// Redirection ou confirmation
header("Location: profil.php?success=1");
exit();

