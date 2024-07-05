<?php
if (isset($_GET['title'])) {
    $nazwa_filmu = $_GET['title'];

    // Wywołanie skryptu Pythona
    $command = escapeshellcmd("python3 fetchReviews.py \"$nazwa_filmu\"");
    $output = shell_exec($command);

    // Zwrot danych JSON
    header('Content-Type: application/json');
    echo $output;
} else {
    echo json_encode([]);
}
?>
