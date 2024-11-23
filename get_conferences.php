<?php
include 'db_config.php';

$sql = "SELECT conference_id, name, start_datetime, end_datetime FROM conferences ORDER BY start_datetime ASC";
$result = $conn->query($sql);

$conferences = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $conferences[] = $row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($conferences);
?>
