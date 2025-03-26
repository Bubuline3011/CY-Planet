<?php
session_start();

// Vérification de l'accès admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Chargement des utilisateurs depuis le fichier JSON
$utilisateurs = json_decode(file_get_contents("data/utilisateur.json"), true);

// Pagination
$utilisateursParPage = 10;
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalUtilisateurs = count($utilisateurs);
$totalPages = ceil($totalUtilisateurs / $utilisateursParPage);

// Découpe du tableau d'utilisateurs pour la page actuelle
$debut = ($pageActuelle - 1) * $utilisateursParPage;
$utilisateursPage = array_slice($utilisateurs, $debut, $utilisateursParPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="admin">
    <?php include 'header.php'; ?>
    <div class="admin-contenue">
        <h1>Gestion des Utilisateurs</h1>
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
                <?php foreach ($utilisateursPage as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <button class="vip-btn">VIP</button>
                        <button class="ban-btn">Bannir</button>
                        <button class="normal-btn">Normal</button>
                    </td>
                    <td><a class="voir-btn" href="profil_utilisateur.php?email=<?= urlencode($user['email']) ?>">Voir</a></td>


                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($pageActuelle > 1): ?>
                <a href="?page=<?= $pageActuelle - 1 ?>" class="button">Page précédente</a>
            <?php endif; ?>

            <?php if ($pageActuelle < $totalPages): ?>
                <a href="?page=<?= $pageActuelle + 1 ?>" class="button">Page suivante</a>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
