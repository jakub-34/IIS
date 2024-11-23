<?php

session_start();
include 'db_config.php';

$conference_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT organizer_id FROM conferences WHERE conference_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $conference_id);
$stmt->execute();
$stmt->bind_result($organizer_id);
$stmt->fetch();

$response = [
    "isLoggedIn" => isset($_SESSION['user_id']),
    "role" => $_SESSION['role'] ?? null,
    "username" => $_SESSION['username'] ?? null,
    "userId" => $_SESSION['user_id'] ?? null,
    "organizerId" => $organizer_id
];

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>
