<?php
include 'db_config.php'; // Connection to database


session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $location = $_POST['location'];
    $start_datetime = str_replace('T', ' ', $_POST['start_datetime']) . ':00';
    $end_datetime = str_replace('T', ' ', $_POST['end_datetime']) . ':00';
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $id_organizer = $_SESSION['user_id'];

    // Add new user into database
    $stmt = $conn->prepare("INSERT INTO conferences (name, description, genre, location, start_datetime, end_datetime, price, capacity, organizer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdii", $name, $description, $genre, $location, $start_datetime, $end_datetime, $price, $capacity, $id_organizer);  
    $stmt->execute();
    header("Location: index.html"); // Go to index.html
    exit;
    $stmt->close();
}
$conn->close();
?>