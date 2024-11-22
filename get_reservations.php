<?php
session_start();
include 'db_config.php';

if (!isset($_GET['conference_id']) || !isset($_GET['status'])) {
    echo json_encode([]);
    exit;
}

$conference_id = intval($_GET['conference_id']);
$status = $_GET['status'];

// Query to get pending reservations with user details, reservation_id, and tickets_count
$query = "
    SELECT r.reservation_id, u.name, u.lastname, r.tickets_count
    FROM reservations r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.conference_id = ? AND r.status = ?
";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param("is", $conference_id, $status);
$stmt->execute();

// Use bind_result to fetch the necessary data
$stmt->bind_result($reservation_id, $name, $lastname, $tickets_count);

$reservations = [];
while ($stmt->fetch()) {
    $reservations[] = [
        'reservation_id' => $reservation_id,
        'name' => $name,
        'lastname' => $lastname,
        'tickets_count' => $tickets_count // Include tickets_count
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($reservations);
?>
