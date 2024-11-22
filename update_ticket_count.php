<?php
include 'db_config.php'; 
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['reservation_id']) || !isset($data['tickets_count'])) {
    echo json_encode(['success' => false, 'error' => 'Missing reservation ID or ticket count.']);
    exit;
}

$reservationId = $data['reservation_id'];
$newTicketCount = $data['tickets_count'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE reservations SET tickets_count = ? WHERE reservation_id = ? AND user_id = ?");

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Query preparation failed.']);
    exit;
}

$stmt->bind_param("iii", $newTicketCount, $reservationId, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update ticket count.']);
}

$stmt->close();
$conn->close();
?>
