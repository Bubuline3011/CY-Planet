<?php
// On démarre la session pour pouvoir utiliser les infos de l'utilisateur si besoin
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Pour afficher correctement les accents -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Pour affichage responsive -->
    <title>Recherche</title>

    <!-- Feuille de style principale -->
    <link id="theme-css" rel="stylesheet" href="style.css">

    <!-- Bibliothèque d'icônes Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="recherche">
    <!-- Inclusion du menu de navigation -->
    <?php include 'header.php'; ?>
    
    <div class="recherche-boite">
        <h1>Trouvez votre séjour idéal :</h1>
    
        <!-- Formulaire pour rechercher un séjour -->
        <form action="acceuil.php" method="POST">
            
            <!-- Choix de la destination -->
            <label for="destination">Destination :</label>
            <select id="destination" name="destination" required> 
                <option value="" disabled selected>Choisissez une destination</option>
                <!-- Liste fixe des destinations disponibles -->
                <option value="Footbolis">Footbolis</option>
                <option value="AquaWorld">AquaWorld</option>
                <option value="Musicaria">Musicaria</option>
                <option value="Adventuris">Adventuris</option>
                <option value="Médiévalia">Médiévalia</option>
                <option value="Dreamara">Dreamara</option>
                <option value="Ludopolis">Ludopolis</option>
                <option value="Gastrodelis">Gastrodelis</option>
                <option value="Sportimax">Sportimax</option>
                <option value="Cineralite">Cinerealite</option>
            </select>
    
            <!-- Sélection de la date de départ -->
            <div>
                <label for="depart">Date de départ :</label>
                <input type="date" id="depart" name="date_depart" required>
            </div>
    
            <!-- Sélection de la date de retour -->
            <div>
                <label for="retour">Date de retour :</label> 
                <input type="date" id="retour" name="date_retour" required>
            </div>
    
            <!-- Section pour les hébergements proposés (sera remplie par JS) -->
            <fieldset>
                <legend>Hébergements disponibles</legend>
                <div id="hebergementsList"></div>
            </fieldset>
    
            <!-- Section pour les activités proposées (sera remplie par JS) -->
            <fieldset>
                <legend>Activités</legend>
                <div id="activitiesList"></div>
            </fieldset>
    
            <!-- Choix du moyen de transport -->
            <label for="transport">Transport :</label>
            <select id="transport" name="transport" required> 
                <option value="" disabled selected>Choisissez votre transport pour vous y rendre</option>
                <option value="voiture">Voiture volante spatiale</option>
                <option value="portail">Portail interdimensionnel</option>
                <option value="vaisseaux">Vaisseaux Spatial</option>
                <option value="Train">Train magnétique interstellaire</option>
                <option value="mongolfière">Montgolfière spatiale</option>
            </select>
            
            <!-- Bouton pour envoyer le formulaire -->
            <button type="submit" class="button">Rechercher</button>
            
            <!-- Affichage du résultat (sera géré avec du JavaScript plus tard) -->
            <h3 id="resultat"></h3>
        </form>
    </div>
    
    <script>
        // Ici, on pourra ajouter du JavaScript pour afficher les hébergements
        // et activités selon la destination choisie
    </script>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
    <script src="js/theme.js"></script>

</body>
</html>
