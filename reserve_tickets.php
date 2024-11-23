<?php
include 'db_config.php'; // Connection to database

session_start();

$conference_id = isset($_GET['conference_id']) ? intval($_GET['conference_id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $tickets_count = $_POST['tickets_count'];
    $status = $_POST['payment_status'];

    // Fetch the total capacity and the already reserved tickets for the conference
    $sql_capacity = "
        SELECT 
            c.capacity AS total_capacity,
            IFNULL(SUM(r.tickets_count), 0) AS reserved_tickets
        FROM conferences c
        LEFT JOIN reservations r ON r.conference_id = c.conference_id AND r.status != 'cancelled'
        WHERE c.conference_id = ?
        GROUP BY c.conference_id
    ";

    $stmt = $conn->prepare($sql_capacity);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error preparing statement']);
        exit;
    }

    $stmt->bind_param("i", $conference_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($total_capacity, $reserved_tickets);

    if ($stmt->fetch()) {
        $remaining_capacity = $total_capacity - $reserved_tickets;
    } 
    else {
        echo json_encode(['success' => false, 'error' => 'Conference not found.']);
        exit;
    }

    $stmt->close();

    // Check if there are enough available tickets
    if ($tickets_count > $remaining_capacity) {
        echo json_encode([
            'success' => false,
            'error' => "Not enough tickets available. Remaining capacity: $remaining_capacity."
        ]);
        exit;
    }

    // Add new user into database
    $stmt = $conn->prepare("INSERT INTO users (name, lastname, role) VALUES (?, ?, 'guest')");
    $stmt->bind_param("ss", $name, $lastname);
    if ($stmt->execute()) {
        $user_id = $conn->insert_id; 
    } 
    else {
        echo json_encode(['success' => false, 'error' => 'Error creating user.']);
        exit;
    }
    $stmt->close();

    // Reservation creation part
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, conference_id, tickets_count, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $conference_id, $tickets_count, $status);

    // Execute the reservation query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reservation successful.']);
        exit;
    } 
    else {
        echo json_encode(['success' => false, 'error' => 'Error creating reservation.']);
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
