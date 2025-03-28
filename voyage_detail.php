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
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['description'] ?? 'Non spécifiée') ?></p>
        <p><strong>Prix de base :</strong> <?= htmlspecialchars($voyage['prix_total']) ?> €</p>
    </div>

    <form method="POST" action="panier.php">
        <input type="hidden" name="voyage_id" value="<?= $id ?>">

        <?php if (!empty($voyage['etapes'])): ?>
            <?php foreach ($voyage['etapes'] as $etape_index => $etape): ?>
                <div class="presentation">
                    <h3><?= htmlspecialchars($etape['titre']) ?></h3>
                    <p><strong>Lieu :</strong> <?= htmlspecialchars($etape['position']['nom_lieu']) ?></p>

                    <?php if (!empty($etape['options'])): ?>
                        <?php foreach ($etape['options'] as $option_index => $option): ?>
                            <label>
                                <?= ucfirst(htmlspecialchars($option['type'])) ?> :
                                <?= htmlspecialchars($option['nom']) ?> (<?= htmlspecialchars($option['prix_par_personne']) ?> € / personne)
                            </label><br>
                            <input type="checkbox" 
                                   name="options[<?= $etape_index ?>][<?= $option_index ?>]" 
                                   value="<?= htmlspecialchars($option['nom']) ?>|<?= htmlspecialchars($option['prix_par_personne']) ?>">
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

        <!-- Nouvelle section pour afficher toutes les activités disponibles -->
        <?php if (!empty($voyage['activites'])): ?>
            <div class="activites">
                <h3>Activités disponibles</h3>
                <p>Choisissez parmi nos activités optionnelles :</p>
                
                <?php foreach ($voyage['activites'] as $activite_index => $activite): ?>
                    <div class="activite">
                        <label>
                            <input type="checkbox" 
                                   name="activites[<?= $activite_index ?>]" 
                                   value="<?= htmlspecialchars($activite['nom']) ?>|<?= htmlspecialchars($activite['prix']) ?>">
                            <?= htmlspecialchars($activite['nom']) ?> - 
                            <?= htmlspecialchars($activite['prix']) ?> €
                        </label>
                        <p><?= htmlspecialchars($activite['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button type="submit" class="acheter-btn">Passer au paiement</button>
    </form>
</main>

</body>
</html>
