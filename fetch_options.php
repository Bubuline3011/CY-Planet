<?php
// fetch_options.php
// Ce fichier récupère dynamiquement les options pour un voyage donné

if (isset($_GET['voyage']) && isset($_GET['etape'])) {
    $voyage = $_GET['voyage'];
    $etape = intval($_GET['etape']);

    // Lecture du fichier JSON correspondant au voyage
    $file_path = __DIR__ . "/../PHASE3/data/" . strtolower($voyage) . ".json";

    if (file_exists($file_path)) {
        $data = json_decode(file_get_contents($file_path), true);
        foreach ($data['etapes'] as $step) {
            if ($step['id'] === $etape) {
                echo json_encode($step['options']);
                exit;
            }
        }
    }
    echo json_encode([]);
}
?>
