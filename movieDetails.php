<?php
// Połączenie z bazą danych
require_once 'config/config.php';

// Zapytanie SQL do pobrania danych o filmach
$query = "SELECT * FROM Filmy WHERE ID_filmu = :id_filmu";
$id_filmu = $_GET['id']; // Pobranie parametru 'id' z URL

// Przygotowanie zapytania
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_filmu', $id_filmu, PDO::PARAM_INT);

// Wykonanie zapytania
if ($stmt->execute()) {
    // Pobranie wyników zapytania
    $film = $stmt->fetch(PDO::FETCH_ASSOC);

    // Wyświetlenie danych o filmie
    if ($film) {
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
    echo "<p>Błąd podczas pobierania danych o filmie</p>";
}
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
        
        <div class="film-info">
            <?php include 'film_info.php'; ?>
        </div>
    </div>
</body>
</html>
