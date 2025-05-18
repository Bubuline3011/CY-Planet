<?php
// calculate_price.php
// Ce fichier calcule le prix total basé sur les options sélectionnées

if (isset($_POST['voyage']) && isset($_POST['options'])) {
    $voyage = $_POST['voyage'];
    $options = $_POST['options'];

    $file_path = __DIR__ . "/../PHASE3/data/" . strtolower($voyage) . ".json";
    $total = 0;

    if (file_exists($file_path)) {
        $data = json_decode(file_get_contents($file_path), true);

        foreach ($options as $option) {
            $etape_id = $option['etape_id'];
            $option_id = $option['option_id'];
            $choix = $option['choix'];
            $nb_personnes = intval($option['nb_personnes']);

            foreach ($data['etapes'] as $etape) {
                if ($etape['id'] === $etape_id) {
                    foreach ($etape['options'] as $opt) {
                        if ($opt['id'] === $option_id && isset($opt['prix_par_valeur'][$choix])) {
                            $prix_unitaire = floatval($opt['prix_par_valeur'][$choix]);
                            $total += $prix_unitaire * $nb_personnes;
                        }
                    }
                }
            }
        }
    }
    echo json_encode(["total" => number_format($total, 2)]);
}
?>
