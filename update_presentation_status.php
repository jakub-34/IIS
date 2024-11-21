<?php
// Zahrnutí konfiguračního souboru pro připojení k databázi
include 'db_config.php'; // Tento soubor obsahuje nastavení připojení k databázi

// Získání dat z JSON požadavku
$input = json_decode(file_get_contents('php://input'), true);
$presentation_id = $input['presentation_id'];
$status = $input['status'];

// Příprava SQL dotazu pro aktualizaci statusu v databázi
$stmt = $conn->prepare("UPDATE presentations SET status = ? WHERE presentation_id = ?");
$stmt->bind_param("si", $status, $presentation_id);

// Provedení dotazu
if ($stmt->execute()) {
    echo json_encode(['success' => true]); // Pokud je úspěch, vrátí odpověď s 'success'
} else {
    // Pokud došlo k chybě při provádění dotazu, vrátí chybu
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Zavření statementu a připojení k databázi
$stmt->close();
$conn->close();
?>
