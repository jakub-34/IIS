<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['isInSchedule' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$presentation_id = $_GET['presentation_id'];

$query = "SELECT COUNT(*) as count FROM user_presentation WHERE user_id = ? AND presentation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $presentation_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();

echo json_encode(['isInSchedule' => $count > 0]);

$stmt->close();
$conn->close();
?>
