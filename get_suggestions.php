<?php
include 'db_config.php';

$conference_id = isset($_GET['conference_id']) ? intval($_GET['conference_id']) : 0;

$sql = "SELECT 
            p.presentation_id, 
            p.title, 
            p.status, 
            p.start_time, 
            p.end_time, 
            p.date, 
            p.room_name, 
            p.description, 
            u.name AS speaker_name, 
            u.lastname AS speaker_lastname,
            c.start_datetime AS conference_start,
            c.end_datetime AS conference_end
        FROM presentations p
        LEFT JOIN users u ON p.speaker_id = u.user_id
        LEFT JOIN conferences c ON p.conference_id = c.conference_id
        WHERE p.conference_id = $conference_id";
$result = $conn->query($sql);

$presentation = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $presentation[] = $row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($presentation);
?>
