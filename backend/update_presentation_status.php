<?php
include 'db_config.php'; 

$input = json_decode(file_get_contents('php://input'), true);
$presentation_id = $input['presentation_id'];
$status = $input['status'];

$stmt = $conn->prepare("UPDATE presentations SET status = ? WHERE presentation_id = ?");
$stmt->bind_param("si", $status, $presentation_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]); 
} 
else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
