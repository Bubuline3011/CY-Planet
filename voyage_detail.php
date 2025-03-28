<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: connexion.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    echo "<p>Erreur : aucun identifiant de voyage fourni dans l'URL.</p>";
    exit;
}

$id = (int)$_GET['id'];

$indexPath = 'data/index_voyages.json';
if (!file_exists($indexPath)) {
    echo "<p>Erreur : fichier index_voyages.json introuvable.</p>";
    exit;
}

$index = json_decode(file_get_contents($indexPath), true);
if (!isset($index[$id])) {
    echo "<p>Erreur : ID de voyage non trouvé dans l’index.</p>";
    exit;
}

$filename = 'data/' . $index[$id];
if (!file_exists($filename)) {
    echo "<p>Erreur : le fichier JSON du voyage est manquant.</p>";
    exit;
}

$voyage = json_decode(file_get_contents($filename), true);
$voyage['id'] = $id;
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
        <p><strong>Dates :</strong> du <?= $voyage['date_depart'] ?> au <?= $voyage['date_retour'] ?> (<?= $voyage['duree'] ?> jours)</p>
        <p><strong>Spécificités :</strong> <?= $voyage['specificites'] ?></p>
        <p><strong>Prix de base :</strong> <?= $voyage['prix_total'] ?> €</p>
    </div>

    <form method="POST" action="panier.php">
        <input type="hidden" name="voyage_id" value="<?= $id ?>">

        <?php if (!empty($voyage['etapes'])): ?>
            <?php foreach ($voyage['etapes'] as $etape_index => $etape): ?>
                <div class="presentation">
                    <h3><?= $etape['titre'] ?></h3>
                    <p><strong>Lieu :</strong> <?= $etape['position']['nom_lieu'] ?></p>

                    <?php if (!empty($etape['options'])): ?>
                        <?php foreach ($etape['options'] as $option_index => $option): ?>
                            <label>
                                <?= ucfirst($option['type']) ?> :
                                <?= $option['nom'] ?> (<?= $option['prix_par_personne'] ?> € / personne)
                            </label><br>
                            <input type="checkbox" 
                                   name="options[<?= $etape_index ?>][<?= $option_index ?>]" 
                                   value="<?= $option['nom'] ?>|<?= $option['prix_par_personne'] ?>">
                            <label>Inclure</label><br><br>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <label><strong>Personnes supplémentaires :</strong></label><br>
                    <select name="personnes_supplementaires[<?= $etape_index ?>]">
                        <?php for ($i = 0; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> personne<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select><br><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune étape définie pour ce voyage.</p>
        <?php endif; ?>

        <button type="submit" class="acheter-btn">Passer au paiement</button>
    </form>
</main>

</body>
</html>
