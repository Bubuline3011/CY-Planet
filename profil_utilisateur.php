<?php
session_start();

// Vérifie que l'utilisateur est un administrateur
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Récupère l'email de l'utilisateur à afficher
if (!isset($_GET['email'])) {
    echo "Aucun utilisateur spécifié.";
    exit();
}

$emailRecherche = $_GET['email'];

// Charge les données utilisateurs
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

// Cherche l'utilisateur correspondant
$user = null;
foreach ($utilisateurs as $u) {
    if ($u['email'] === $emailRecherche) {
        $user = $u;
        break;
    }
}

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de l'utilisateur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="profil">
    <?php include 'header.php'; ?>
    <div class="profil-container">
        <h2>Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>

        <div class="profil-info">
            <div class="profil-champ">
                <label>Nom :</label>
                <input type="text" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
            </div>
            <div class="profil-champ">
                <label>Prénom :</label>
                <input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
            </div>
            <div class="profil-champ">
                <label>Email :</label>
                <input type="text" value="<?= htmlspecialchars($user['email']) ?>" readonly>
            </div>
            <div class="profil-champ">
                <label>Téléphone :</label>
                <input type="text" value="<?= htmlspecialchars($user['telephone']) ?>" readonly>
            </div>
            <div class="profil-champ">
                <label>Date inscription :</label>
                <input type="text" value="<?= htmlspecialchars($user['date_inscription']) ?>" readonly>
            </div>
            <div class="profil-champ">
                <label>Dernière connexion :</label>
                <input type="text" value="<?= htmlspecialchars($user['derniere_connexion']) ?>" readonly>
            </div>
            <div class="profil-champ">
                <label>Rôle :</label>
                <input type="text" value="<?= htmlspecialchars($user['role']) ?>" readonly>
            </div>
        </div>
    </div>
</body>
</html>
