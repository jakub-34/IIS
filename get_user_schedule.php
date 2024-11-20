<?php
session_start();
include 'db_config.php'; // Připojení k databázi

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        p.title, 
        p.description, 
        p.date, 
        p.start_time, 
        p.end_time, 
        r.name AS room_name
    FROM user_presentation up
    JOIN presentations p ON up.presentation_id = p.presentation_id
    JOIN rooms r ON p.room_id = r.room_id
    WHERE up.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($schedule);
?>
