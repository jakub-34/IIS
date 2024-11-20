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

$conference_id = isset($_GET['conference_id']) ? intval($_GET['conference_id']) : 0;

// SQL dotaz pro načtení konferencí
$sql = "SELECT presentation_id, title, description, status, date, start_time, end_time FROM presentations WHERE conference_id = $conference_id AND status = 'approved'";
$result = $conn->query($sql);

// Převedení výsledků do JSON formátu
$presentation = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $presentation[] = $row;
    }
}
$conn->close();

// Nastavení hlavičky a výpis JSON
header('Content-Type: application/json');
echo json_encode($presentation);
?>
