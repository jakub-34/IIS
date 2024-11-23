<?php
include 'db_config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['presentation_id'], $input['date'], $input['start_time'], $input['end_time'], $input['room'], $input['status'])) {
    http_response_code(400); // Špatný požadavek
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

$presentation_id = $input['presentation_id'];
$date = $input['date'];
$start_time = $input['start_time'];
$end_time = $input['end_time'];
$room = $input['room'];
$status = $input['status'];

$stmt = $conn->prepare("UPDATE presentations SET date = ?, start_time = ?, end_time = ?, room_name = ?, status = ? WHERE presentation_id = ?");
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to prepare the SQL statement.']);
    exit;
}

$stmt->bind_param("sssssi", $date, $start_time, $end_time, $room, $status, $presentation_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} 
else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
