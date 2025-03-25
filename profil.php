<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$email = $_SESSION['email'];

// Charger tous les utilisateurs
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

// Trouver l'utilisateur correspondant
$user = null;
foreach ($utilisateurs as $u) {
    if ($u['email'] === $email) {
        $user = $u;
        break;
    }
}

if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="profil">
	<?php include 'header.php'; ?>
    <div class="profil-container">
        <h2>Mon Profil</h2>

        <div class="profil-info">
            <div class="profil-champ">
                <label>Nom :</label>
                <input type="text" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
                <button class="modifier-btn"><i class='bx bxs-edit-alt'></i></button>
            </div>
            <div class="profil-champ">
                <label>Prénom :</label>
                <input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
                <button class="modifier-btn"><i class='bx bxs-edit-alt'></i></button>
            </div>
            <div class="profil-champ">
                <label>Email :</label>
                <input type="text" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                <button class="modifier-btn"><i class='bx bxs-edit-alt'></i></button>
            </div>
            <div class="profil-champ">
                <label>Mot de passe :</label>
                <input type="text" value="<?= htmlspecialchars($user['motdepasse']) ?>" readonly>
                <button class="modifier-btn"><i class='bx bxs-edit-alt'></i></button>
            </div>
            <div class="profil-champ">
                <label>Age :</label>
                <input type="text" value="<?= htmlspecialchars($user['age']) ?>" readonly>
                <button class="modifier-btn"><i class='bx bxs-edit-alt'></i></button>
            </div>          
            <div class="profil-champ">
                <label>Téléphone :</label>
                <input type="text" value="<?= htmlspecialchars($user['telephone']) ?>" readonly>
                <button class="modifier-btn"><i class='bx bxs-edit-alt'></i></button>
            </div>
            <div class="profil-champ">
                <label>Dernière connexion :</label>
                <input type="text" value="<?= htmlspecialchars($user['derniere_connexion']) ?>" readonly>
            </div>
        </div>

        <h3>Mes voyages payés</h3>
        <ul>
            <?php
            if (!empty($user['voyages_achetes'])) {
                foreach ($user['voyages_achetes'] as $voyage) {
                    echo '<li><a class="voir-btn" href="voyage_detail.php?id=' . htmlspecialchars($voyage['id']) . '">' .
                         htmlspecialchars($voyage['nom']) . ' – ' .
                         htmlspecialchars($voyage['date_achat']) . ' – ' .
                         htmlspecialchars($voyage['prix_total']) . ' €</a></li>';
                }
            } else {
                echo "<li>Aucun voyage acheté pour l’instant.</li>";
            }
            ?>
        </ul>
	<button class="button-sauvegarder">Sauvegarder</button>
        <?php if ($user['role'] === 'admin') : ?>
        <div class="acces-admin">
            <a href="admin.php" class="button">Accès Admin</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>



