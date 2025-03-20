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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_carte = preg_replace('/\s+/', '', $_POST['numero_carte']);
    $nom_proprietaire = $_POST['nom_proprietaire'];
    $expiration = $_POST['expiration'];
    $cvv = $_POST['cvv'];
    $montant = $_POST['montant'];
    $montant_format = number_format((float)$_POST['montant'], 2, '.', '');

    $voyage_id = $_POST['voyage_id'];

    if (!preg_match("/^\d{16}$/", $numero_carte) || !preg_match("/^\d{3}$/", $cvv)) {
        die("Erreur: Informations bancaires invalides.");
    }
    
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
    
    $code_vendeur = "MI-5_E";
    $api_key = getAPIKey($code_vendeur);
    if ($api_key === "zzzz") {
        die("Erreur: Clé API invalide.");
    }
    echo "Clé API récupérée: " . $api_key . "\n";
    $retour_url = "http://localhost/retour_paiement.php";
    if ($montant == 0) {
    $montant = 100.00; // Montant test
	}
    $montant_format = number_format((float)$montant, 2, '.', '');
    $transaction_id = uniqid();
    $control_hash = md5($api_key . "#" . $transaction_id . "#" . $montant_format . "#" . $code_vendeur . "#accepted");
}
echo "<pre>";
echo "API Key: " . $api_key . "\n";
echo "Transaction: " . $transaction_id . "\n";
echo "Montant: " . $montant_format . "\n";
echo "Vendeur: " . $code_vendeur . "\n";
echo "Retour URL: " . $retour_url . "\n";
echo "Control attendu: " . $control_hash . "\n";
echo "</pre>";
file_put_contents("debug_envoi.txt", "Transaction: $transaction_id | Montant: $montant_format | Vendeur: $code_vendeur | Control: $control_hash\n", FILE_APPEND);
?>

<form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
    <input type="hidden" name="transaction" value="<?= $transaction_id; ?>">
    <input type="hidden" name="montant" value="<?= $montant_format; ?>">
    <input type="hidden" name="vendeur" value="<?= $code_vendeur; ?>">
    <input type="hidden" name="retour" value="<?= $retour_url; ?>">
    <input type="hidden" name="control" value="<?= $control_hash; ?>">
    <button type="submit">Confirmer le paiement</button>
</form>



