<?php
if (isset($_GET['id'])) {
    $id_filmu = $_GET['id'];

    // Wywołanie skryptu Pythona
    $command = escapeshellcmd("python3 fetchReviews.py $id_filmu");
    $output = shell_exec($command);

    // Zwrot danych JSON
    header('Content-Type: application/json');
    echo $output;
} else {
    echo json_encode([]);
}
?>
