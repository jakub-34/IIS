<?php
include 'db_config.php'; // Connection to database

session_start();

$conference_id = isset($_GET['conference_id']) ? intval($_GET['conference_id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];

    // Add new user into database
    $stmt = $conn->prepare("INSERT INTO users (name, lastname, role) VALUES (?, ?, 'guest')");
    $stmt->bind_param("ss", $name, $lastname);
    $stmt->execute();
    $user_id = $conn->insert_id; 
    $stmt->close();

    $tickets_count = $_POST['tickets_count'];
    $status = $_POST['payment_status']; // Assuming this is passed in the form

    // Reservation creation part
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, conference_id, tickets_count, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $conference_id, $tickets_count, $status);

    // Execute the reservation query
    if ($stmt->execute()) {
        header("Location: index.html?conference_id=$conference_id&status=success");
        exit;
    } else {
        header("Location: index.html.php?conference_id=$conference_id&status=error");
        exit;
    }

    $stmt->close();
}

$conn->close();
?>