<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$presentation_id = $data['presentation_id'];
$user_id = $_SESSION['user_id'];

$query = "DELETE FROM user_presentation WHERE user_id = ? AND presentation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $presentation_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error.']);
}
$stmt->close();
$conn->close();
?>
