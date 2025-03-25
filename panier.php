<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include("getapikey.php");
        $transaction = '154632ABCZWTC';
        $montant = "405768.99";
        $vendeur = "TEST";
        $api_key = getAPIKey($vendeur);
        $retour = "http://localhost:8080/retour_paiement.php";
        $control = md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");
    ?>
    
    <h2>Votre panier</h2>
    <ul>
        <li>Voyage à Footbolis (405768.99$)</li>
        <ul>
            <li>Option : visite du stade intergalactique</li>
        </ul>
    </ul>
    <h3>Total à payer : 405768.99$</h3>
    
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="retour" value="<?php echo $retour; ?>">
        <input type="hidden" name="control" value="<?php echo $control; ?>">
        <input type="submit" class="bouton-paiement" value="Valider et payer">
    </form>
</body>
</html>

