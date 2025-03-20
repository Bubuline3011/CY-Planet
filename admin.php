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
                <tr>
                    <td>Alice</td>
                    <td>Dupont</td>
                    <td>alice.dupont@example.com</td>
                    <td>
                        <button class="vip-btn">VIP</button>
                        <button class="ban-btn">Bannir</button>
                        <button class="normal-btn">Normal</button>
                    </td>
                    <td><button class="voir-btn">Voir</button></td>
                </tr>
                <tr>
                    <td>Jean</td>
                    <td>Martin</td>
                    <td>jean.martin@example.com</td>
                    <td>
                        <button class="vip-btn">VIP</button>
                        <button class="ban-btn">Bannir</button>
                        <button class="normal-btn">Normal</button>
                    </td>
                    <td><button class="voir-btn">Voir</button></td>
                </tr>
                <tr>
                    <td>Emma</td>
                    <td>Lefèvre</td>
                    <td>emma.lefevre@example.com</td>
                    <td>
                        <button class="vip-btn">VIP</button>
                        <button class="ban-btn">Bannir</button>
                        <button class="normal-btn">Normal</button>
                    </td>
                    <td><button class="voir-btn">Voir</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
