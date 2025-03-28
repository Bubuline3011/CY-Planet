<?php
session_start();

// Vérifier si un ID de voyage est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ ID de voyage invalide !");
}

// Charger le fichier JSON correspondant
$id = intval($_GET['id']);
$filename = "Voyage/voyage_$id.json";

if (!file_exists($filename)) {
    die("❌ Voyage non trouvé !");
}

// Lire le fichier JSON
$voyage = json_decode(file_get_contents($filename), true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($voyage['titre']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="voyage-detail">

    <?php include 'header.php'; ?>

    <div class="container">
        <h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
        <p><strong>Durée :</strong> <?php echo $voyage['duree']; ?> jours</p>
        <p><strong>Date :</strong> <?php echo $voyage['date_depart']; ?> → <?php echo $voyage['date_retour']; ?></p>

        <h2>🌍 Étapes du voyage</h2>

        <form method="POST" action="recap_voyage.php">
            <input type="hidden" name="voyage_id" value="<?php echo $voyage['id']; ?>">

            <?php foreach ($voyage['etapes'] as $index => $etape) : ?>
                <div class="etape">
                    <h3><?php echo htmlspecialchars($etape['titre']); ?></h3>
                    <p><strong>Lieu :</strong> <?php echo htmlspecialchars($etape['position']['nom_lieu']); ?></p>

                    <!-- Sélection d'hébergement -->
                    <label>🏨 Hébergement :</label>
                    <select name="hebergement[<?php echo $index; ?>]">
                        <?php foreach ($etape['options'] as $option) :
                            if ($option['type'] === 'hébergement') : ?>
                                <option value="<?php echo htmlspecialchars($option['nom']); ?>">
                                    <?php echo htmlspecialchars($option['nom']); ?> - <?php echo $option['prix_par_personne']; ?>€ / pers
                                </option>
                        <?php endif; endforeach; ?>
                    </select>

                    <!-- Sélection d'activités -->
                    <label>🎢 Activités :</label>
                    <select name="activites[<?php echo $index; ?>]">
                        <?php foreach ($etape['options'] as $option) :
                            if ($option['type'] === 'activité sportive' || $option['type'] === 'activité culturelle') : ?>
                                <option value="<?php echo htmlspecialchars($option['nom']); ?>">
                                    <?php echo htmlspecialchars($option['nom']); ?> - <?php echo $option['prix_par_personne']; ?>€ / pers
                                </option>
                        <?php endif; endforeach; ?>
                    </select>

                    <!-- Sélection du transport -->
                    <label>🚀 Transport :</label>
                    <select name="transport[<?php echo $index; ?>]">
                        <?php foreach ($etape['options'] as $option) :
                            if ($option['type'] === 'transport') : ?>
                                <option value="<?php echo htmlspecialchars($option['nom']); ?>">
                                    <?php echo htmlspecialchars($option['nom']); ?> - <?php echo $option['prix_par_personne']; ?>€ / pers
                                </option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="button">Passer au récapitulatif</button>
        </form>
   

