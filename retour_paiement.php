<?php
include("getapikey.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Récupération des paramètres envoyés par CY Bank
    $transaction = $_GET["transaction"] ?? "";
    $montant = $_GET["montant"] ?? "";
    $vendeur = $_GET["vendeur"] ?? "";
    $status = $_GET["status"] ?? ""; // Correction ici (anciennement "statut")
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

        // Redirection après 5 secondes vers la page de paiement
        header("refresh:5;url=page_paiement.php");
    }
} else {
    echo "Accès interdit.";
}
?>



