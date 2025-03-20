<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Pour afficher correctement les accents -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Pour affichage responsive -->
    <title>Recherche</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="recherche">
    <?php include 'header.php'; ?>
    <div class="recherche-boite">
        <h1>Trouvez votre séjour idéal :</h1>
    
        <form action="acceuil.html" method="POST">
            
            <label for="destination">Destination :</label>
            <select id="destination" name="destination" required> 
                <option value="" disabled selected>Choisissez une destination</option>
                <option value="Footbolis">Footbolis</option>
                <option value="AquaWorld">AquaWorld</option>
                <option value="Musicaria">Musicaria</option>
                <option value="Adventuris">Adventuris</option>
                <option value="Médiévalia">Médiévalia</option>
                <option value="Dreamara">Dreamara</option>
                <option value="Ludopolis">Ludopolis</option>
            </select>
    
            <!-- Sélection de la date -->
            <div>
                <label for="depart">Date de départ :</label>
                <input type="date" id="depart" name="date_depart" required>
            </div>
    
            <div>
                <label for="retour">Date de retour :</label> 
                <input type="date" id="retour" name="date_retour" required>
            </div>
    
            <!-- Hébergements disponibles -->
            <fieldset>
                <legend>Hébergements disponibles</legend>
                <div id="hebergementsList"></div>
            </fieldset>
    
            <!-- Activités disponibles -->
            <fieldset>
                <legend>Activités</legend>
                <div id="activitiesList"></div>
            </fieldset>
    
            <label for="transport">Transport :</label>
            <select id="transport" name="transport" required> 
                <option value="" disabled selected>Choisissez votre transport pour vous y rendre</option>
                <option value="voiture">Voiture volante spatiale</option>
                <option value="portail">Portail interdimensionnel</option>
                <option value="vaisseaux">Vaisseaux Spatial</option>
                <option value="Train">Train magnétique interstellaire</option>
                <option value="mongolfière">Montgolfière spatiale</option>
            </select>
            
            <!-- Bouton pour confirmer -->
            <button type="submit" class="button">Rechercher</button>
            
            <!-- Résultat -->
            <h3 id="resultat"></h3>
        </form>
    </div>
    
    <script>
        // JavaScript pour mettre à jour les hébergements et activités selon la destination
    </script>
    
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>

