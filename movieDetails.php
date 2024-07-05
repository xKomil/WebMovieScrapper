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
        } else {
            $error_message = "Brak danych dla filmu o ID: $id_filmu";
        }
    } else {
        $error_message = "Błąd podczas wykonania zapytania: " . $stmt->error;
    }

    // Zamknięcie zapytania
    $stmt->close();
} else {
    $error_message = "Brak parametru 'id' w URL";
}

// Zamknięcie połączenia
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKino</title>

    <link rel="shortcut icon" href="assets/KK Cinema.ico" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="css/movieDetails.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="fetchReviews.js"></script>
</head>

<body id="top">

    <header class="header" data-header>
        <div class="container">
            <div class="overlay" data-overlay></div>
            <a href="#" class="logo">
                <img src="assets/KK Cinema.ico" alt="logo KKino"><span>
            </a>
            <div class="header-actions">
                <div class="lang-wrapper">
                    <label for="language">
                        <ion-icon name="globe-outline"></ion-icon>
                    </label>
                    <select name="language" id="language">
                        <option value="en">PL</option>
                        <option value="en">ENG(kiedyś :))</option>
                    </select>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="logout" value="true">
                    <button type="submit" class="btn btn-primary">Wyloguj się</button>
                </form>
            </div>
            <button class="menu-open-btn" data-menu-open-btn>
                <ion-icon name="reorder-two"></ion-icon>
            </button>
            <nav class="navbar" data-navbar>
                <div class="navbar-top">
                    <button class="menu-close-btn" data-menu-close-btn>
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                </div>
                <ul class="navbar-list">
                    <li>
                        <a href="#home" class="navbar-link">Strona domowa</a>
                    </li>
                    <li>
                        <a href="#movie" class="navbar-link">Filmy</a>
                    </li>
                    <li>
                        <a href="#tv-series" class="navbar-link">Bilety</a>
                    </li>
                    <li>
                        <a href="#contact" class="navbar-link">Kontakt</a>
                    </li>
                    <li>
                        <a href="userprofile.php" class="navbar-link">Profil</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Informacje o filmie</h1>
        
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php else: ?>
            <div class="movie-details">
                <h2><?php echo $film['Tytuł']; ?></h2>
                <div class="movie-info">
                    <img src="<?php echo $film['Zdjecie']; ?>" alt="<?php echo $film['Tytuł']; ?>" class="film-image">
                    <table class="movie-table">
                        <tr>
                            <th>Reżyser:</th>
                            <td><?php echo $film['Reżyser']; ?></td>
                        </tr>
                        <tr>
                            <th>Gatunek:</th>
                            <td><?php echo $film['Gatunek']; ?></td>
                        </tr>
                        <tr>
                            <th>Czas trwania:</th>
                            <td><?php echo $film['Czas_trwania']; ?> minut</td>
                        </tr>
                        <tr>
                            <th>Opis:</th>
                            <td><?php echo $film['Opis']; ?></td>
                        </tr>
                        <tr>
                            <th>Zwiastun:</th>
                            <td><a href="<?php echo $film['Zwiastun']; ?>" target="_blank">Zobacz zwiastun</a></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="movie-reviews">
                <h3>Opinie z Filmwebu</h3>
                <div id="opinie"></div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
