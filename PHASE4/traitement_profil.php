<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['email'])) {
    echo "error"; // Si l'utilisateur n'est pas connecté, renvoie une erreur
    exit();
}

$email = $_SESSION['email'];
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

// On parcourt les utilisateurs pour trouver celui correspondant à l'email
foreach ($utilisateurs as &$u) {
    if ($u['email'] === $email) {
        // Mise à jour des champs modifiables avec les données envoyées par AJAX
        if (isset($_POST['nom'])) $u['nom'] = $_POST['nom'];
        if (isset($_POST['prenom'])) $u['prenom'] = $_POST['prenom'];
        if (isset($_POST['email'])) $u['email'] = $_POST['email'];
        if (isset($_POST['motdepasse'])) $u['motdepasse'] = $_POST['motdepasse'];
        if (isset($_POST['age'])) $u['age'] = $_POST['age'];
        if (isset($_POST['telephone'])) $u['telephone'] = $_POST['telephone'];

        // Si l'email a changé, on met à jour la session
        if (isset($_POST['email']) && $_POST['email'] !== $email) {
            $_SESSION['email'] = $_POST['email'];
        }

        // On quitte dès qu'on a trouvé l'utilisateur
        break;
    }
}

// Sauvegarde les modifications dans le fichier JSON
if (file_put_contents("data/utilisateur.json", json_encode($utilisateurs, JSON_PRETTY_PRINT))) {
    echo "success"; // Si tout se passe bien, on renvoie "success" pour la requête AJAX
} else {
    echo "error"; // Si une erreur se produit lors de l'enregistrement, renvoie "error"
}
exit();

