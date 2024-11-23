<?php
include 'db_config.php';

$conference_id = isset($_GET['conference_id']) ? intval($_GET['conference_id']) : 0;

// SQL query to retrieve conference details
$sql = "SELECT conference_id, name, description, location, start_datetime, end_datetime, capacity, genre FROM conferences WHERE conference_id = $conference_id";
$result = $conn->query($sql);

$conference_details = null;
if ($result->num_rows > 0) {
    $conference_details = $result->fetch_assoc();
}

// SQL query to load presentations
$sql_presentations = "SELECT 
    p.presentation_id, 
    p.title, 
    p.status, 
    p.start_time, 
    p.end_time, 
    p.date, 
    p.room_name, 
    p.description, 
    u.name AS speaker_name, 
    u.lastname AS speaker_lastname
    FROM presentations p
    LEFT JOIN users u ON p.speaker_id = u.user_id
    WHERE p.conference_id = $conference_id AND status = 'approved'
    ORDER BY p.date ASC, p.start_time ASC";
$result_presentations = $conn->query($sql_presentations);

$presentations = array();
if ($result_presentations->num_rows > 0) {
    while ($row = $result_presentations->fetch_assoc()) {
        $presentations[] = $row;
    }
}

$sql_capacity = "SELECT 
                c.capacity AS total_capacity, 
                (c.capacity - IFNULL(SUM(r.tickets_count), 0)) AS remaining_capacity
                FROM conferences c
                LEFT JOIN reservations r ON c.conference_id = r.conference_id AND r.status != 'cancelled'
                WHERE c.conference_id = $conference_id
                GROUP BY c.conference_id";
$result_capacity = $conn->query($sql_capacity);

$capacity_info = null;
if ($result_capacity->num_rows > 0) {
    $capacity_info = $result_capacity->fetch_assoc();
}

$response = array(
    "conference_details" => $conference_details,
    "presentations" => $presentations,
    "capacity_info" => $capacity_info
);

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
