<?php
include 'db_config.php';

// Database query
$sql = "SELECT user_id, name, lastname, username, role FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();

// Set content type
header('Content-Type: application/json');
echo json_encode($users);
?>