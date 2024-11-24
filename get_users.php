<?php
include 'db_config.php';

$sql = "SELECT user_id, name, lastname, username, role FROM users WHERE username IS NOT NULL ORDER BY username ASC";
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