<?php
include 'db_config.php'; // Connection to database

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Add new user into database
    $stmt = $conn->prepare("INSERT INTO users (name, lastname, username, password, role) VALUES (?, ?, ?, ?, 'registered')");
    $stmt->bind_param("ssss", $name, $lastname, $username, $hashed_password);
    
    if ($stmt->execute()) {
        // Auto login after registration
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'registered';

        header("Location: index.html"); // Go to index.html
        exit;
    } else {
        echo "<script>alert('There has been unexpected error in registration'); window.location.href='register.html';</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>
