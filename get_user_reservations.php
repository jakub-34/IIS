<?php
include 'db_config.php'; 

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        r.reservation_id, 
        c.name AS conference_name, 
        r.tickets_count, 
        r.status
    FROM reservations r
    JOIN conferences c ON r.conference_id = c.conference_id
    WHERE r.user_id = ?
");

if (!$stmt) {
    echo json_encode(['error' => 'Query preparation failed', 'details' => $conn->error]);
    exit;
}

$stmt->bind_param("i", $userId);
$stmt->execute();

$stmt->bind_result($reservationId, $conferenceName, $ticketsCount, $status);

$reservations = [];
while ($stmt->fetch()) {
    $reservations[] = [
        'reservation_id' => $reservationId,
        'conference_name' => $conferenceName,
        'tickets_count' => $ticketsCount,
        'status' => $status
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($reservations);
?>
