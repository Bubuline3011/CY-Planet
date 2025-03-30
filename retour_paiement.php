<?php
session_start();

include("getapikey.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Récupération des paramètres envoyés par CY Bank
    $transaction = $_GET["transaction"] ?? "";
    $montant = $_GET["montant"] ?? "";
    $vendeur = $_GET["vendeur"] ?? "";
    $status = $_GET["status"] ?? ""; 
    $control_recu = $_GET["control"] ?? "";

    // Vérification des valeurs reçues
    if (empty($transaction) || empty($montant) || empty($vendeur) || empty($status) || empty($control_recu)) {
        die("Erreur : paramètres de retour invalides.");
    }

    // Récupérer la clé API du vendeur
    $api_key = getAPIKey($vendeur);

    // Recalcul de la valeur de contrôle attendue
    $control_attendu = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

    // Vérification de l'intégrité des données
    if ($control_attendu !== $control_recu) {
        die("Erreur : contrôle d'intégrité échoué. Données modifiées ou corrompues.");
    }

    // Affichage du message en fonction du statut
    if ($status === "accepted") {
        echo "<h2>Paiement accepté ✅</h2>";
        echo "<p>Transaction : $transaction</p>";
        echo "<p>Montant : $montant $</p>";
        echo "<p>Merci pour votre achat ! Vous allez être redirigé vers votre espace client.</p>";

        // Enregistrement de la transaction (ajouter ici du code pour stocker en base de données si nécessaire)

        // Redirection après 5 secondes vers l'espace client
        header("refresh:5;url=profil.php");
    } else {
        echo "<h2>Paiement refusé ❌</h2>";
        echo "<p>Transaction : $transaction</p>";
        echo "<p>Montant : $montant $</p>";
        echo "<p>Le paiement a été refusé. Vérifiez vos informations bancaires et réessayez.</p>";

        // Redirection après 5 secondes vers la page de destination
        header("refresh:5;url=destination.php");
        exit;
    }
} else {
    echo "Accès interdit.";
}

// === MISE À JOUR DE utilisateur.json ===
if (!isset($_SESSION['email']) || !isset($_SESSION['commande'])) {
    echo "<p>Erreur : aucune commande ou utilisateur en session.</p>";
    exit;
}

$email = $_SESSION['email'];
$commande = $_SESSION['commande'];
$commande['id'] = $commande['voyage_id']; // on force la présence de l'ID pour l’enregistrement

$voyage_achete = [
    "id" => $commande['voyage_id'],
    "nom" => $commande['titre'],
    "date_achat" => date('Y-m-d'),
    "prix_total" => $commande['prix_total']
];

$utilisateur_path = "data/utilisateur.json";
if (file_exists($utilisateur_path)) {
    $utilisateurs = json_decode(file_get_contents($utilisateur_path), true);

    foreach ($utilisateurs as &$user) {
        if ($user['email'] === $email) {
            if (!isset($user['voyages_achetes'])) {
                $user['voyages_achetes'] = [];
            }
            $user['voyages_achetes'][] = $voyage_achete;
            break;
        }
    }

    file_put_contents($utilisateur_path, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

?>



