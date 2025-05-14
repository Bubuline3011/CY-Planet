<?php

header('Content-Type: application/json');
$filtres = $_GET;
$index = json_decode(file_get_contents("data/index_voyages.json"), true);
$resultats = [];

foreach ($index as $id => $fichier) {
    $voyage = json_decode(file_get_contents("data/" . $fichier), true);

    if (!empty($filtres['titre']) && stripos($voyage['titre'], $filtres['titre']) === false) continue;
    if (!empty($filtres['note_min']) && $voyage['note'] < (int)$filtres['note_min']) continue;
    if (!empty($filtres['prix_min']) && $voyage['prix_total'] < (int)$filtres['prix_min']) continue;
    if (!empty($filtres['prix_max']) && $voyage['prix_total'] > (int)$filtres['prix_max']) continue;
    if (!empty($filtres['duree_max']) && $voyage['duree'] > (int)$filtres['duree_max']) continue;
    if (!empty($filtres['theme']) && $voyage['theme'] !== $filtres['theme']) continue;
    if (!empty($filtres['transport']) && $voyage['transport'] !== $filtres['transport']) continue;

    $resultats[] = $voyage;
}
echo json_encode($resultats);