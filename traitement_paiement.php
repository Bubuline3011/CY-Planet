<?php
session_start();
if (!isset($_SESSION['connecte']) || !isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

// Vérification des données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_carte = $_POST['numero_carte'];
    $nom_proprietaire = $_POST['nom_proprietaire'];
    $expiration = $_POST['expiration'];
    $cvv = $_POST['cvv'];
    $montant = $_POST['montant'];
    $voyage_id = $_POST['voyage_id'];

    // Vérifier que le numéro de carte contient bien 16 chiffres
    if (!preg_match("/^\d{16}$/", $numero_carte)) {
        die("Numéro de carte invalide.");
    }

    // Vérifier que le cryptogramme est valide (3 chiffres)
    if (!preg_match("/^\d{3}$/", $cvv)) {
        die("Cryptogramme invalide.");
    }

    // Génération des infos pour CY Bank
    $transaction_id = uniqid(); // Génère un ID unique
    $code_vendeur = "MI-5_E"; // Remplace par ton code projet
    $retour_url = "http://localhost/retour_paiement.php";
    $api_key = "zzzz"; // À récupérer dynamiquement via getAPIKey()

    $control_hash = md5($api_key . "#" . $transaction_id . "#" . $montant . "#" . $code_vendeur . "#" . $retour_url);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation du paiement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="paiement">
    <div class="paiement-container">
        <h2>Validation du paiement</h2>
        <p>Votre paiement est en cours de traitement...</p>

        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?= $transaction_id; ?>">
            <input type="hidden" name="montant" value="<?= number_format($montant, 2, '.', ''); ?>">
            <input type="hidden" name="vendeur" value="<?= $code_vendeur; ?>">
            <input type="hidden" name="retour" value="<?= $retour_url; ?>">
            <input type="hidden" name="control" value="<?= $control_hash; ?>">

            <button type="submit" class="button">Confirmer le paiement</button>
        </form>
    </div>
</body>
</html>



