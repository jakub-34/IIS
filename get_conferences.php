<?php
// Nastavení připojení k databázi
$servername = "localhost"; // Hostitel databáze
$username = "xfurik00";    // Uživatel databáze
$password = "5ujomzam"; // Heslo databáze
$database = "xfurik00";      // Název databáze

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $database);

// Kontrola připojení
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}

// SQL dotaz pro načtení konferencí
$sql = "SELECT name, start_datetime, end_datetime FROM conferences";
$result = $conn->query($sql);

// Převedení výsledků do JSON formátu
$conferences = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $conferences[] = $row;
    }
}
$conn->close();

// Nastavení hlavičky a výpis JSON
header('Content-Type: application/json');
echo json_encode($conferences);
?>
