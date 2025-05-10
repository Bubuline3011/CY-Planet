<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voyage_id'])) {
    $voyage_id = $_POST['voyage_id'];

    // Lire index pour retrouver le fichier du voyage
    $index = json_decode(file_get_contents('data/index_voyages.json'), true);
    if (!isset($index[$voyage_id])) {
        die("Voyage non trouvé.");
    }

    $fichier = 'data/' . $index[$voyage_id];
    $voyage = json_decode(file_get_contents($fichier), true);

    $options_selectionnees = $_POST['options'] ?? [];
    $prix_total = $voyage['prix_total'];
    $recap = [];

    foreach ($options_selectionnees as $etapeIndex => $options) {
        foreach ($options as $optionIndex => $option) {
            $type = $option['type'] ?? 'inconnu';
            $nom = $option['nom'] ?? 'Option inconnue';
            $choix = $option['choix_utilisateur'] ?? 'Non';
            $nb = isset($option['personnes']) ? (int)$option['personnes'] : 1;

            $prix_unitaire = 0;
            foreach ($voyage['etapes'][$etapeIndex]['options'] as $opt) {
                if ($opt['type'] === $type && $opt['nom'] === $nom) {
                    $prix_unitaire = $opt['prix_par_valeur'][$choix] ?? 0;
                    break;
                }
            }

            $sous_total = $prix_unitaire * $nb;

            if (strtolower(trim($choix)) !== 'non' && $sous_total > 0) {
                $recap[] = [
                    'nom' => $nom,
                    'choix' => $choix,
                    'personnes' => $nb,
                    'prix_unitaire' => $prix_unitaire,
                    'prix_total' => $sous_total
                ];
                $prix_total += $sous_total;
            }
        }
    }

    // Enregistrer dans $_SESSION
    $_SESSION['commande'] = [
        'voyage_id' => $voyage_id,
        'titre' => $voyage['titre'],
        'date_depart' => $voyage['date_depart'],
        'date_retour' => $voyage['date_retour'],
        'duree' => $voyage['duree'],
        'prix_total' => $prix_total,
        'recap' => $recap
    ];

    header('Location: panier.php');
    exit();
} else {
    echo "Erreur : formulaire non valide.";
}

