<?php
include 'db_config.php'; 
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['reservation_id'])) {
    echo json_encode(['success' => false, 'error' => 'Missing reservation ID.']);
    exit;
}

$reservationId = $data['reservation_id'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    DELETE FROM reservations 
    WHERE reservation_id = ? AND user_id = ?
");

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Query preparation failed.']);
    exit;
}

$stmt->bind_param("ii", $reservationId, $userId);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } 
    else {
        echo json_encode(['success' => false, 'error' => 'Reservation not found or not owned by user.']);
    }
} 
else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete reservation.']);
}

$stmt->close();
$conn->close();
?>
