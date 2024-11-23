<?php
include 'db_config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $previousPage = $_POST['previousPage'] ?? 'index.html';

    // Find user in database
    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $username_db, $hashed_password, $role);

    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username_db;
            $_SESSION['role'] = $role;

            header("Location: $previousPage");
            exit;
        } 
        else {
            echo "<script>alert('Wrong username or password.'); window.location.href='../login.html';</script>";
        }
    } 
    else {
        echo "<script>alert('Wrong username or password.'); window.location.href='../login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
