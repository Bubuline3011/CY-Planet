<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['connecte']) || !isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}
require('getapikey.php');
$usersFile = "utilisateur.json";
$usersData = json_decode(file_get_contents($usersFile), true) ?? [];

// Vérification des données reçues
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_carte = str_replace('/\s+/', '', $_POST['numero_carte']); // Enlève les espaces pour CY Bank
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
        die("CVV invalide.");
    }
    


	// Enregistrement dans utilisateur.json
foreach ($usersData as &$user) {
    if ($user['email'] === $_SESSION['email']) {
        $user['coordonnees_bancaires'] = [
            "numero_carte" => $numero_carte,
            "date_expiration" => $expiration,
            "cvv" => $cvv
        ];
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));
        break;
    }
}
    }
    // Génération des infos pour CY Bank
    $code_vendeur = "MI-5_E"; 
    $api_key = getAPIKey($code_vendeur); // Récupération dynamique de l'API Key
    if ($api_key === "zzzz") {
        die("Erreur : Clé API invalide pour le vendeur ");
    }
    $transaction_id = uniqid();
    $retour_url = "http://localhost/retour_paiement.php";
   
    $montant_format = number_format((float)$montant, 2, '.', ''); // Assure 2 décimales
    $control_hash = md5($api_key . "#" . $transaction_id . "#" . $montant_format . "#" . $code_vendeur . "#" . $retour_url);
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
	<p>Votre paiement est prêt à être envoyé à la banque.</p>
        <p>Veuillez confirmer le paiement.</p>
        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?= $transaction_id; ?>">
            <input type="hidden" name="montant" value="<?= $montant_format; ?>">
            <input type="hidden" name="vendeur" value="<?= $code_vendeur; ?>">
            <input type="hidden" name="retour" value="<?= $retour_url; ?>">
            <input type="hidden" name="control" value="<?= $control_hash; ?>">

            <button type="submit" class="button">Confirmer le paiement</button>
        </form>
    </div>
</body>
</html>



