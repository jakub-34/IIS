<?php
include 'db_config.php';

session_start();

if (isset($_SESSION['user_id']) && isset($_GET['conference_id'])) {
    $userId = $_SESSION['user_id'];
    $conferenceId = $_GET['conference_id'];
    $ticketsCount = 1;
    $status = 'pending';

    $sql_capacity = "
        SELECT c.capacity - IFNULL(SUM(r.tickets_count), 0) AS remaining_capacity
        FROM conferences c
        LEFT JOIN reservations r ON r.conference_id = c.conference_id AND r.status != 'cancelled'
        WHERE c.conference_id = ?
        GROUP BY c.conference_id";

    $stmt = $conn->prepare($sql_capacity);
    if (!$stmt) {
        die("Error: Could not prepare statement");
    }

    $stmt->bind_param("i", $conferenceId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($remainingCapacity);

    if ($stmt->fetch()) {
        if ($remainingCapacity < $ticketsCount) {
            header('Location: conference_details.php?conference_id=' . $conferenceId . '&error=no_tickets');
            exit;
        }
    } 
    else {
        header('Location: conference_details.php?conference_id=' . $conferenceId . '&error=not_found');
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO reservations (user_id, conference_id, tickets_count, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $userId, $conferenceId, $ticketsCount, $status);

    if ($stmt->execute()) {
        header('Location: user_reservations.html');
        exit;
    } 
    else {
        die("Error: " . $stmt->error);
    }
} 
else {
    header('Location: login.php');
    exit;
}

$stmt->close();
$conn->close();
?>
