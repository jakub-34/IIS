<?php
session_start();
include 'db_config.php'; // Include your database configuration

if (!isset($_GET['conference_id']) || !isset($_GET['status'])) {
    echo json_encode([]);
    exit;
}

$conference_id = intval($_GET['conference_id']);
$status = $_GET['status'];

// Query to get pending reservations with user details
$query = "
    SELECT u.name, u.lastname
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

// Use bind_result instead of get_result
$stmt->bind_result($name, $lastname);

$reservations = [];
while ($stmt->fetch()) {
    $reservations[] = [
        'name' => $name,
        'lastname' => $lastname
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($reservations);
