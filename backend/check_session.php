<?php
session_start();
$response = [
    "isLoggedIn" => isset($_SESSION['user_id']),
    "role" => $_SESSION['role'] ?? null,
    "username" => $_SESSION['username'] ?? null
];
header('Content-Type: application/json');
echo json_encode($response);
?>
