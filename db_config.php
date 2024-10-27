<?php
// Set up database connection
$servername = "localhost"; // Host of the database
$username = "xfurik00";    // User of the database
$password = "5ujomzam"; // Password of the database
$database = "xfurik00";      // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>
