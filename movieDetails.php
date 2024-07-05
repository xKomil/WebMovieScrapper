<?php
// Połączenie z bazą danych
require_once 'config/config.php';

// Sprawdzenie czy parametr 'id' został przekazany w URL
if (isset($_GET['id'])) {
    // Pobranie ID filmu z parametru GET
    $id_filmu = $_GET['id'];

    // Zapytanie SQL do pobrania danych o filmie
    $query = "SELECT * FROM Filmy WHERE ID_filmu = ?";

    // Przygotowanie zapytania
    $stmt = $conn->prepare($query);

    // Związanie parametru ID filmu z zapytaniem
    $stmt->bind_param('i', $id_filmu);

    // Wykonanie zapytania
    if ($stmt->execute()) {
        // Pobranie wyników zapytania
        $result = $stmt->get_result();

        // Sprawdzenie czy znaleziono film
        if ($result->num_rows > 0) {
            $film = $result->fetch_assoc();

            // Wyświetlenie danych o filmie
            echo "<h2>{$film['Tytuł']}</h2>";
            echo "<p><strong>Reżyser:</strong> {$film['Reżyser']}</p>";
            echo "<p><strong>Gatunek:</strong> {$film['Gatunek']}</p>";
            echo "<p><strong>Czas trwania:</strong> {$film['Czas_trwania']} minut</p>";
            echo "<p><strong>Opis:</strong> {$film['Opis']}</p>";
            echo "<img src='{$film['Zdjecie']}' alt='{$film['Tytuł']}' class='film-image'>";
            echo "<p><a href='{$film['Zwiastun']}' target='_blank'>Zobacz zwiastun</a></p>";
        } else {
            echo "<p>Brak danych dla filmu o ID: $id_filmu</p>";
        }
    } else {
        echo "<p>Błąd podczas wykonania zapytania: " . $stmt->error . "</p>";
    }

    // Zamknięcie zapytania
    $stmt->close();
} else {
    echo "<p>Brak parametru 'id' w URL</p>";
}

// Zamknięcie połączenia
$conn->close();
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informacje o filmie</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet href="movieDetails.css">
</head>
<body>
    <div class="container">
        <h1>Informacje o filmie</h1>
    </div>
</body>
</html>
