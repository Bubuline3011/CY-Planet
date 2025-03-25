<?php
include("getapikey.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les paramètres envoyés par CY Bank
    $transaction = $_GET["transaction"] ?? "";
    $montant = $_GET["montant"] ?? "";
    $vendeur = $_GET["vendeur"] ?? "";
    $statut = $_GET["statut"] ?? "";
    $control_recu = $_GET["control"] ?? "";

    // Vérification des valeurs reçues
    if (empty($transaction) || empty($montant) || empty($vendeur) || empty($statut) || empty($control_recu)) {
        die("Erreur : paramètres de retour invalides.");
    }

    // Récupérer la clé API du vendeur
    $api_key = getAPIKey($vendeur);

    // Recalcul de la valeur de contrôle attendue
    $control_attendu = md5($api_key."#".$transaction."#". $montant."#".$vendeur."#".$statut."#");

    // Vérification de l'intégrité des données
    if ($control_attendu !== $control_recu) {
        die("Erreur : contrôle d'intégrité échoué. Données modifiées ou corrompues.");
    }

    // Traitement en fonction du statut
    if ($statut === "accepted") {
        echo "<h2>Paiement accepté ✅</h2>";
        echo "<p>Transaction : $transaction</p>";
        echo "<p>Montant : $montant $</p>";
        echo "<p>Merci pour votre achat ! Vous allez être redirigé vers votre espace client.</p>";
        
        // Ici, on peut ajouter un enregistrement en base de données ou envoyer un email de confirmation
        
        // Redirection après 5 secondes
        header("refresh:5;url=espace_client.php");
    } 
    else {
        echo "<h2>Paiement refusé ❌</h2>";
        echo "<p>Transaction : $transaction</p>";
        echo "<p>Montant : $montant $</p>";
        echo "<p>Le paiement a été refusé. Vérifiez vos informations bancaires et réessayez.</p>";
        
        // Redirection après 5 secondes
        header("refresh:5;url=page_paiement.php");
    }
} 
else {
    echo "Accès interdit.";
}
?>


