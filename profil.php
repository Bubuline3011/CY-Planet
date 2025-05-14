<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

// Chargement des données utilisateur
$email = $_SESSION['email'];
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);
$user = null;

foreach ($utilisateurs as &$u) {
    if ($u['email'] === $email) {
        $user = $u;
        break;
    }
}

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur introuvable']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mise à jour des informations utilisateur
    if (isset($_POST['nom'])) $user['nom'] = $_POST['nom'];
    if (isset($_POST['prenom'])) $user['prenom'] = $_POST['prenom'];
    if (isset($_POST['email'])) $user['email'] = $_POST['email'];
    if (isset($_POST['motdepasse'])) $user['motdepasse'] = $_POST['motdepasse'];
    if (isset($_POST['age'])) $user['age'] = $_POST['age'];
    if (isset($_POST['telephone'])) $user['telephone'] = $_POST['telephone'];

    // Si l'email a changé, on met à jour la session
    if (isset($_POST['email']) && $_POST['email'] !== $email) {
        $_SESSION['email'] = $_POST['email'];
    }

    // Sauvegarde des modifications dans le fichier JSON
    if (file_put_contents("data/utilisateur.json", json_encode($utilisateurs, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success', 'message' => 'Modifications enregistrées avec succès']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la sauvegarde des modifications']);
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="theme-css" rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="profil">
    <?php include 'header.php'; ?>

    <div class="profil-container">
        <h2>Mon Profil</h2>
        <form id="profil-form" method="post">
            <div class="profil-info">
                <?php
                $champs = [
                    'nom' => 'Nom',
                    'prenom' => 'Prénom',
                    'email' => 'Email',
                    'motdepasse' => 'Mot de passe',
                    'age' => 'Âge',
                    'telephone' => 'Téléphone'
                ];

                foreach ($champs as $cle => $label) {
                    $valeur = htmlspecialchars($user[$cle]);
                    echo "
                    <div class=\"profil-champ\">
                        <label for=\"$cle\">$label :</label>
                        <input type=\"text\" id=\"$cle\" name=\"$cle\" value=\"$valeur\" readonly>
                        <button type=\"button\" class=\"modifier-btn\"><i class='bx bxs-edit-alt'></i></button>
                    </div>";
                }

                // Dernière connexion (non modifiable)
                echo "
                <div class=\"profil-champ\">
                    <label>Dernière connexion :</label>
                    <input type=\"text\" value=\"" . htmlspecialchars($user['derniere_connexion']) . "\" readonly>
                </div>";
                ?>
            </div>

            <!-- Voyages achetés -->
            <h3>Mes voyages payés</h3>
            <ul>
                <?php
                if (!empty($user['voyages_achetes'])) {
                    foreach ($user['voyages_achetes'] as $voyage) {
                        echo '<li><a class="voyage_achete" href="voyage_detail.php?id=' .
                            htmlspecialchars($voyage['id']) . '">' .
                            htmlspecialchars($voyage['nom']) . ' – ' .
                            htmlspecialchars($voyage['date_achat']) . ' – ' .
                            htmlspecialchars($voyage['prix_total']) . ' €</a></li>';
                    }
                } else {
                    echo "<li>Aucun voyage acheté pour l’instant.</li>";
                }
                ?>
            </ul>

            <!-- Champ caché pour l'action -->
            <input type="hidden" name="action" value="update">

            <!-- Bouton de soumission -->
            <div class="acces-admin">
                <button id="bouton-soumettre" class="button-sauvegarder" style="display:none;">Soumettre les modifications</button>
            </div>
        </form>

        <!-- Lien admin -->
        <?php if ($user['role'] === 'admin') : ?>
        <div class="acces-admin">
            <a href="admin.php" class="button">Accès Admin</a>
        </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>

    <script src="js/theme.js"></script>
    <script src="js/profil.js"></script>
</body>
</html>

