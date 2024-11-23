<?php
include 'db_config.php';

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'];
$new_role = $input['role'];

// Check data
if (!in_array($new_role, ['admin', 'registered'])) {
    echo json_encode(['success' => false, 'error' => 'Neplatná role']);
    exit;
}

// Update role in database
$stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
$stmt->bind_param("si", $new_role, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} 
else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
