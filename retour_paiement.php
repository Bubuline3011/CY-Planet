<?php
session_start();
file_put_contents("debug_retour.txt", print_r($_GET, true) . "\n", FILE_APPEND);

$usersFile = "utilisateur.json";
$usersData = json_decode(file_get_contents($usersFile), true) ?? [];

$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control = $_GET['control'] ?? '';

require('getapikey.php');
$api_key = getAPIKey($vendeur);
echo "<pre>";
echo "=== DEBUG RETOUR PAIEMENT ===\n";
echo "Transaction reçue : " . $transaction . "\n";
echo "Montant reçu : " . $montant . "\n";
echo "Vendeur reçu : " . $vendeur . "\n";
echo "Statut reçu : '" . $statut . "' (avec guillemets pour voir les espaces)\n";
echo "Control attendu : " . $control_hash . "\n";
echo "Control reçu : " . $control . "\n";
echo "</pre>";
exit();

$statut = trim(strtolower($_GET['status']));
$control_hash = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut);

if ($control !== $control_hash) {
    die("Erreur: paiement non valide.");
}

if ($statut === "accepted") {
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
    echo "<p>Paiement accepté !</p>";
} else {
    echo "<p>Paiement refusé. Veuillez réessayer.</p>";
}
?>

