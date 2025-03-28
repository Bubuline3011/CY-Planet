<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: connexion.php');
    exit();
}

// Vérifie si un ID est présent et est un entier positif
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] <= 0) {
    echo "<p>Erreur : Aucun identifiant de voyage valide spécifié.</p>";
    exit;
}

$id = (int)$_GET['id'];

// Charger la table d'index des fichiers voyages
$indexPath = 'data/index_voyages.json';
if (!file_exists($indexPath)) {
    echo "<p>Erreur : index_voyages.json introuvable.</p>";
    exit;
}

$index = json_decode(file_get_contents($indexPath), true);

// Vérifie si l'id est valide dans l'index
if (!array_key_exists($id, $index)) {
    echo "<p>Erreur : Voyage inconnu (ID : $id).</p>";
    exit;
}

$filename = 'data/' . $index[$id];
if (!file_exists($filename)) {
    echo "<p>Erreur : fichier $filename introuvable.</p>";
    exit;
}

$voyage = json_decode(file_get_contents($filename), true);
$voyage['id'] = $id; // on ajoute l'ID dans le tableau, au cas où il manque

// Enregistrement de la consultation dans le profil utilisateur
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $consultation = [
        "id" => $voyage['id'],
        "nom" => $voyage['titre'],
        "date_consultation" => date('Y-m-d H:i:s')
    ];

    $utilisateur_path = "data/utilisateur.json";
    if (file_exists($utilisateur_path)) {
        $utilisateurs = json_decode(file_get_contents($utilisateur_path), true);

        foreach ($utilisateurs as &$user) {
            if ($user['email'] === $email) {
                if (!isset($user['voyages_consultes'])) {
                    $user['voyages_consultes'] = [];
                }

                $deja_consulte = false;
                foreach ($user['voyages_consultes'] as &$c) {
                    if ($c['id'] === $voyage['id']) {
                        $c['date_consultation'] = $consultation['date_consultation'];
                        $deja_consulte = true;
                        break;
                    }
                }
                unset($c); // bonne pratique pour casser la référence

                if (!$deja_consulte) {
                    $user['voyages_consultes'][] = $consultation;
                }

                break;
            }
        }

        file_put_contents($utilisateur_path, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($voyage['titre']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="acceuil">
<?php include 'header.php'; ?>

<main class="destinations">
    <h2><?= htmlspecialchars($voyage['titre']) ?></h2>

    <div class="intro">
        <p><strong>Dates :</strong> du <?= htmlspecialchars($voyage['date_depart']) ?> au <?= htmlspecialchars($voyage['date_retour']) ?> (<?= htmlspecialchars($voyage['duree']) ?> jours)</p>
        <p><strong>Spécificités :</strong> <?= htmlspecialchars($voyage['specificites']) ?></p>
        <p><strong>Prix de base :</strong> <?= htmlspecialchars($voyage['prix_total']) ?> €</p>
    </div>

    <!-- Formulaire pour sélectionner les options et personnes supplémentaires -->
    <form method="POST" action="panier.php">
        <input type="hidden" name="voyage_id" value="<?= $id ?>">

        <?php if (isset($voyage['etapes'])): ?>
            <?php foreach ($voyage['etapes'] as $etape_index => $etape): ?>
                <div class="presentation">
                    <h3><?= htmlspecialchars($etape['titre']) ?></h3>
                    <p><strong>Lieu :</strong> <?= htmlspecialchars($etape['position']['nom_lieu']) ?></p>

                    <!-- Affichage des options disponibles -->
                    <?php if (isset($etape['options'])): ?>
                        <?php foreach ($etape['options'] as $option_index => $option): ?>
                            <label for="option_<?= $etape_index ?>_<?= $option_index ?>">
                                <?= ucfirst(htmlspecialchars($option['type'])) ?> :
                                <?= htmlspecialchars($option['nom']) ?> (<?= $option['prix_par_personne'] ?> € par personne)
                            </label><br>
                            <input type="checkbox" 
                                   name="options[<?= $etape_index ?>][<?= $option_index ?>]" 
                                   value="<?= htmlspecialchars($option['nom']) ?>|<?= $option['prix_par_personne'] ?>">
                            <label>Inclure</label>
                            <br><br>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Liste déroulante pour le nombre de personnes supplémentaires -->
                    <label for="supp_<?= $etape_index ?>"><strong>Personnes supplémentaires pour cette étape :</strong></label><br>
                    <select name="personnes_supplementaires[<?= $etape_index ?>]" id="supp_<?= $etape_index ?>">
                        <?php for ($i = 0; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> personne<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                    <br><br>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <button type="submit" class="acheter-btn">Passer au paiement</button>
    </form>
</main>
</body>
</html>

