<?php

include 'db_config.php'; 
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Check if reservation_id and tickets_count are set in the input
if (!isset($data['reservation_id']) || !isset($data['tickets_count'])) {
    echo json_encode(['success' => false, 'error' => 'Missing reservation ID or ticket count.']);
    exit;
}

$reservationId = $data['reservation_id'];
$newTicketCount = $data['tickets_count'];
$userId = $_SESSION['user_id'];

// Fetch current capacity and existing ticket count
$sql_capacity = "
    SELECT 
        c.capacity AS total_capacity,
        IFNULL(SUM(r.tickets_count), 0) AS reserved_tickets,
        (SELECT tickets_count FROM reservations WHERE reservation_id = ?) AS user_tickets
    FROM conferences c
    LEFT JOIN reservations r ON r.conference_id = c.conference_id AND r.status != 'cancelled'
    WHERE c.conference_id = (SELECT conference_id FROM reservations WHERE reservation_id = ?)
    GROUP BY c.conference_id
";

$stmt = $conn->prepare($sql_capacity);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Query preparation failed.']);
    exit;
}

$stmt->bind_param("ii", $reservationId, $reservationId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($total_capacity, $reserved_tickets, $user_tickets);

if ($stmt->fetch()) {
    $capacity_info = [
        'total_capacity' => $total_capacity,
        'reserved_tickets' => $reserved_tickets,
        'user_tickets' => $user_tickets,
    ];
} 
else {
    echo json_encode(['success' => false, 'error' => 'Conference not found.']);
    exit;
}

$stmt->close();

// Calculate remaining capacity (total capacity - reserved tickets)
$remainingCapacity = $capacity_info['total_capacity'] - $capacity_info['reserved_tickets'] + $capacity_info['user_tickets'];

// Check if the new ticket count exceeds the available capacity
if ($newTicketCount > $remainingCapacity) {
    echo json_encode([
        'success' => false,
        'error' => "Not enough tickets available. Remaining capacity: $remainingCapacity."
    ]);
    exit;
}

// Update ticket count
$stmt_update = $conn->prepare("UPDATE reservations SET tickets_count = ? WHERE reservation_id = ? AND user_id = ?");
if (!$stmt_update) {
    echo json_encode(['success' => false, 'error' => 'Query preparation failed.']);
    exit;
}

$stmt_update->bind_param("iii", $newTicketCount, $reservationId, $userId);
if ($stmt_update->execute()) {
    echo json_encode(['success' => true]);
} 
else {
    echo json_encode(['success' => false, 'error' => 'Failed to update ticket count.']);
}

$stmt_update->close();
$conn->close();
?>
