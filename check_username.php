<?php
include 'db_config.php'; // Connect to database

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }

    $stmt->close();
}
$conn->close();
?>
