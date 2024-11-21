<?php
// Zahrnutí konfiguračního souboru pro připojení k databázi
include 'db_config.php'; // Tento soubor by měl obsahovat nastavení připojení k databázi

// Získání dat z JSON požadavku
$input = json_decode(file_get_contents('php://input'), true);

// Ověření, zda jsou všechna potřebná data přítomna
if (!isset($input['presentation_id'], $input['date'], $input['start_time'], $input['end_time'], $input['room'], $input['status'])) {
    http_response_code(400); // Špatný požadavek
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

// Přiřazení přijatých dat do proměnných
$presentation_id = $input['presentation_id'];
$date = $input['date'];
$start_time = $input['start_time'];
$end_time = $input['end_time'];
$room = $input['room'];
$status = $input['status'];

// Příprava SQL dotazu pro aktualizaci prezentace
$stmt = $conn->prepare("UPDATE presentations SET date = ?, start_time = ?, end_time = ?, room_name = ?, status = ? WHERE presentation_id = ?");
if ($stmt === false) {
    http_response_code(500); // Chyba na straně serveru
    echo json_encode(['success' => false, 'error' => 'Failed to prepare the SQL statement.']);
    exit;
}

// Navázání parametrů a provedení dotazu
$stmt->bind_param("sssssi", $date, $start_time, $end_time, $room, $status, $presentation_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]); // Úspěšná aktualizace
} else {
    http_response_code(500); // Chyba na straně serveru
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Zavření statementu a připojení k databázi
$stmt->close();
$conn->close();
?>
