<?php
// On démarre la session pour accéder aux données de l'utilisateur
session_start();

// On inclut le fichier qui permet de récupérer la clé API du vendeur
include("getapikey.php");

// On vérifie si la requête est bien de type GET (envoyée par CY Bank)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // On récupère les paramètres renvoyés par la banque
    $transaction = $_GET["transaction"] ?? "";
    $montant = $_GET["montant"] ?? "";
    $vendeur = $_GET["vendeur"] ?? "";
    $status = $_GET["status"] ?? ""; 
    $control_recu = $_GET["control"] ?? "";

    // Vérification que tous les paramètres sont bien présents
    if (empty($transaction) || empty($montant) || empty($vendeur) || empty($status) || empty($control_recu)) {
        die("Erreur : paramètres de retour invalides.");
    }

    // On récupère la clé API du vendeur à partir de son nom
    $api_key = getAPIKey($vendeur);

    // On recalcule le code de contrôle pour s’assurer que les données n’ont pas été modifiées
    $control_attendu = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

    // Si le contrôle ne correspond pas à ce qui est attendu → danger
    if ($control_attendu !== $control_recu) {
        die("Erreur : contrôle d'intégrité échoué. Données modifiées ou corrompues.");
    }

    // Si tout est OK et le paiement est accepté
    if ($status === "accepted") {
        echo "<h2>Paiement accepté ✅</h2>";
        echo "<p>Transaction : $transaction</p>";
        echo "<p>Montant : $montant $</p>";
        echo "<p>Merci pour votre achat ! Vous allez être redirigé vers votre espace client.</p>";

        // Ici, on pourrait enregistrer la transaction dans une base de données si besoin

        // Redirection automatique après 5 secondes vers le profil
        header("refresh:5;url=profil.php");
    } else {
        // Si le paiement est refusé
        echo "<h2>Paiement refusé ❌</h2>";
        echo "<p>Transaction : $transaction</p>";
        echo "<p>Montant : $montant $</p>";
        echo "<p>Le paiement a été refusé. Vérifiez vos informations bancaires et réessayez.</p>";

        // Redirection vers la page du voyage concerné après 5 secondes
        $id_voyage = $_SESSION['commande']['voyage_id'] ?? null;

        if ($id_voyage !== null) {
            header("refresh:5;url=voyage_detail.php?id=" . urlencode($id_voyage));
        } else {
            header("refresh:5;url=voyage_detail.php");
        }
        exit;
    }
} else {
    // Si quelqu’un essaie d’accéder directement à ce fichier autrement que par GET
    echo "Accès interdit.";
}

// =====================
// MISE À JOUR DU FICHIER utilisateur.json
// =====================

// On vérifie qu’on a bien un utilisateur et une commande en session
if (!isset($_SESSION['email']) || !isset($_SESSION['commande'])) {
    echo "<p>Erreur : aucune commande ou utilisateur en session.</p>";
    exit;
}

// On récupère l’email de l’utilisateur et sa commande
$email = $_SESSION['email'];
$commande = $_SESSION['commande'];

// On crée une structure représentant le voyage acheté
$voyage_achete = [
    "id" => $commande['voyage_id'],
    "nom" => $commande['titre'],
    "date_achat" => date('Y-m-d'),
    "prix_total" => $commande['prix_total']
];

// Chemin vers le fichier utilisateur
$utilisateur_path = "data/utilisateur.json";

// Si le fichier existe, on le met à jour
if (file_exists($utilisateur_path)) {
    $utilisateurs = json_decode(file_get_contents($utilisateur_path), true);

    foreach ($utilisateurs as &$user) {
        // On trouve l'utilisateur correspondant
        if ($user['email'] === $email) {
            // Si le tableau des achats n’existe pas encore, on le crée
            if (!isset($user['voyages_achetes'])) {
                $user['voyages_achetes'] = [];
            }
            // On ajoute le nouveau voyage dans la liste des achats
            $user['voyages_achetes'][] = $voyage_achete;
            break;
        }
    }

    // On réécrit le fichier JSON avec les nouveaux achats
    file_put_contents($utilisateur_path, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
