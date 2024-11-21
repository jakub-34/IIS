<?php
session_start();
include 'db_config.php'; // Připojení k databázi

// Zkontrolujte, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Dotaz na databázi
$query = "
    SELECT
        p.presentation_id, 
        p.title, 
        p.description, 
        p.date, 
        p.start_time, 
        p.end_time,
        p.room_name
    FROM user_presentation up
    JOIN presentations p ON up.presentation_id = p.presentation_id
    WHERE up.user_id = ?
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database query preparation failed.']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

// Bind the result variables
$stmt->bind_result($presentation_id, $title, $description, $date, $start_time, $end_time, $room_name);

$schedule = [];
while ($stmt->fetch()) {
    $schedule[] = [
        'presentation_id' => $presentation_id,
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'room_name' => $room_name,
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($schedule);
?>
