<?php
include 'db_config.php'; // Connection to database

session_start();

if (isset($_SESSION['user_id']) && isset($_GET['conference_id'])) {
    $userId = $_SESSION['user_id'];
    $conferenceId = $_GET['conference_id'];
    $ticketsCount = 1;
    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO reservations (user_id, conference_id, tickets_count, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $userId, $conferenceId, $ticketsCount, $status);

    if ($stmt->execute()) {
        header('Location: user_reservations.html');
        exit;
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    header('Location: login.php');
    exit;
}

$stmt->close();
$conn->close();
?>