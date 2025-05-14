<?php
// Pour activer les en-têtes JSON
header('Content-Type: application/json');

// Ajoute un délai de 2 secondes pour simuler la latence réseau
sleep(2);

// Vérifie que les données nécessaires sont bien envoyées
if (!isset($_POST['email']) || !isset($_POST['role'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

$email = $_POST['email'];
$nouveauRole = $_POST['role'];

// Charge les utilisateurs depuis le fichier JSON
$cheminFichier = 'data/utilisateur.json';
$utilisateurs = json_decode(file_get_contents($cheminFichier), true);

// Cherche l'utilisateur correspondant à l'email
$trouve = false;
foreach ($utilisateurs as &$utilisateur) {
    if ($utilisateur['email'] === $email) {
        $utilisateur['role'] = $nouveauRole;
        $trouve = true;
        break;
    }
}

if (!$trouve) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    exit;
}

// Réécrit le fichier JSON avec le rôle mis à jour
file_put_contents($cheminFichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

// Répond avec succès
echo json_encode(['success' => true, 'message' => 'Rôle mis à jour avec succès']);
exit;
?>

