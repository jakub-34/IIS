<?php
// Set up database connection
$servername = "localhost";
$username = "xfurik00";
$password = "5ujomzam";
$database = "xfurik00"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>
