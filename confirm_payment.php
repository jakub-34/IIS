<?php
session_start();
include 'db_config.php'; // Include your database configuration

// Check if the reservation ID is provided
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['reservation_id'])) {
    echo json_encode(['success' => false, 'error' => 'Reservation ID is required.']);
    exit;
}

$reservation_id = intval($data['reservation_id']);

// Update the status of the reservation to 'paid'
$query = "UPDATE reservations SET status = 'paid' WHERE reservation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
