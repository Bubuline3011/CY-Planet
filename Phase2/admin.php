<?php
// On démarre la session pour accéder aux infos de l'utilisateur
session_start();

// Vérification que l'utilisateur est bien un admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    // Si l'utilisateur n'est pas connecté ou pas admin, on le redirige vers la page de connexion
    header("Location: connexion.php");
    exit();
}

// On lit le fichier JSON contenant les utilisateurs
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

// ---------- Gestion de la pagination ---------- //

// Nombre d'utilisateurs à afficher par page
$utilisateursParPage = 10;

// Page actuelle (récupérée dans l'URL avec GET)
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Nombre total d'utilisateurs
$totalUtilisateurs = count($utilisateurs);

// Calcul du nombre total de pages
$totalPages = ceil($totalUtilisateurs / $utilisateursParPage);

// On détermine à quel utilisateur commencer l'affichage
$debut = ($pageActuelle - 1) * $utilisateursParPage;

// On extrait les utilisateurs à afficher sur la page actuelle
$utilisateursPage = array_slice($utilisateurs, $debut, $utilisateursParPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Pour un affichage responsive (sur téléphone, tablette, etc.) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des Utilisateurs</title>

    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="style.css">

    <!-- Bibliothèque d'icônes Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="admin">
    <!-- Inclusion du header commun -->
    <?php include 'header.php'; ?>

    <div class="admin-contenue">
        <h1>Gestion des Utilisateurs</h1>

        <!-- Tableau des utilisateurs -->
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Profil</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle sur les utilisateurs de la page actuelle -->
                <?php foreach ($utilisateursPage as $user): ?>
                <tr>
                    <!-- Affichage sécurisé des infos de l'utilisateur -->
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <!-- Boutons d'action (ils ne font rien ici mais peuvent être liés à des scripts) -->
                        <button class="vip-btn">VIP</button>
                        <button class="ban-btn">Bannir</button>
                        <button class="normal-btn">Normal</button>
                    </td>
                    <!-- Lien vers la page de profil de l'utilisateur -->
                    <td><a class="voir-btn" href="profil_utilisateur.php?email=<?= urlencode($user['email']) ?>">Voir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Zone de pagination -->
        <div class="pagination">
            <!-- Lien vers la page précédente s’il y en a une -->
            <?php if ($pageActuelle > 1): ?>
                <a href="?page=<?= $pageActuelle - 1 ?>" class="button">Page précédente</a>
            <?php endif; ?>

            <!-- Lien vers la page suivante s’il y en a une -->
            <?php if ($pageActuelle < $totalPages): ?>
                <a href="?page=<?= $pageActuelle + 1 ?>" class="button">Page suivante</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
