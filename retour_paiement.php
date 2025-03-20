<?php
session_start();
$usersFile = "utilisateur.json";
$usersData = json_decode(file_get_contents($usersFile), true) ?? [];

$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? ''; // accepted ou denied
$control = $_GET['control'] ?? '';

require('getapikey.php'); // Récupération dynamique de la clé API
$api_key = getAPIKey($vendeur);
$control_hash = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut);

$erreur = null;
$message = null;

if ($control !== $control_hash) {
    $erreur = "Erreur : paiement non valide.";
} 
elseif ($statut === "accepted") {
    foreach ($usersData as &$user) {
        if ($user['email'] === $_SESSION['email']) {
            $user['voyages_achetes'][] = [
                "transaction" => $transaction,
                "montant" => $montant,
                "date" => date("Y-m-d H:i:s")
            ];
            file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));
            break;
        }
    }
    $message = "Paiement accepté ! Votre voyage a été ajouté à vos achats.";
} else {
    $erreur = "Paiement refusé. Veuillez réessayer.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Paiement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="retour-paiement">
    <div class="paiement-container">
        <h2>Statut du paiement</h2>
        
        <?php if ($message): ?>
            <p class="message-confirmation"><?= htmlspecialchars($message); ?></p>
            <div class="retour-container">
                <a href="profil.php" class="button">Voir mes voyages</a>
            </div>
        <?php endif; ?>

        <?php if ($erreur): ?>
            <p class="message-erreur"><?= htmlspecialchars($erreur); ?></p>
            <div class="retour-container">
                <a href="paiement.php" class="button">Recommencer le paiement</a>
                <a href="voyage.php" class="button">Modifier mon voyage</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


