<?php
include 'db_config.php'; // Připojení k databázi

// Získání seznamu místností
$result = $conn->query("SELECT room_id, name FROM rooms"); 

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row; // Přidání každé místnosti do pole
    }
}

header('Content-Type: application/json'); 
echo json_encode($rooms); // Odeslání místností jako JSON
$conn->close();
?>