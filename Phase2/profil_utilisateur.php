<?php
// On démarre la session pour accéder aux infos utilisateur
session_start();

// Vérifie si l'utilisateur connecté est un admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Vérifie qu'un email a été fourni dans l'URL
if (!isset($_GET['email'])) {
    echo "Aucun utilisateur spécifié.";
    exit();
}

// On récupère l'email de l'utilisateur à afficher
$emailRecherche = $_GET['email'];

// On charge les utilisateurs depuis le fichier JSON
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

// On cherche l'utilisateur correspondant à l'email
$user = null;
foreach ($utilisateurs as $u) {
    if ($u['email'] === $emailRecherche) {
        $user = $u;
        break;
    }
}

// Si l'utilisateur n'a pas été trouvé
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
    	<!-- Titre avec prénom et nom -->
        <h2>Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>

        <div class="profil-info">
        	<!-- Affichage des infos de l'utilisateur -->
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
            <!-- Liste des voyages achetés par l'utilisateur -->
            <h3>Voyages payés</h3>
        	<ul>
            	<?php
            	if (!empty($user['voyages_achetes'])) {
                	foreach ($user['voyages_achetes'] as $voyage) {
                    		echo '<li><a class="voyage_achete" href="voyage_detail.php?id=' . htmlspecialchars($voyage['id']) . '">' .
                         	htmlspecialchars($voyage['nom']) . ' – ' .
                         	htmlspecialchars($voyage['date_achat']) . ' – ' .
                         	htmlspecialchars($voyage['prix_total']) . ' €</a></li>';
                	}
            	} else {
                	echo "<li>Aucun voyage acheté pour l’instant.</li>";
            	}
            	?>
        	</ul>
        </div>
    </div>
</body>
</html>
