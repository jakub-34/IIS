<?php

session_start();
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

// Získání conference_id z URL
$conference_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Pokud je ID konferencí v URL (např. ?id=123)

// Dotaz na organizátorovo ID pro tuto konferenci
$sql = "SELECT organizer_id FROM conferences WHERE conference_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $conference_id);  // Parametr pro připravený dotaz (integer)
$stmt->execute();
$stmt->bind_result($organizer_id);
$stmt->fetch();

// Vrátí odpověď v JSON formátu
$response = [
    "isLoggedIn" => isset($_SESSION['user_id']),
    "role" => $_SESSION['role'] ?? null,
    "username" => $_SESSION['username'] ?? null,
    "userId" => $_SESSION['user_id'] ?? null,
    "organizerId" => $organizer_id
];

// Závěr
header('Content-Type: application/json');
echo json_encode($response);

// Zavření připojení
$stmt->close();
$conn->close();
?>
