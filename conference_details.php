<?php
// Nastavení připojení k databázi
$servername = "localhost"; // Hostitel databáze
$username = "xfurik00";    // Uživatel databáze
$password = "5ujomzam"; // Heslo databáze
$database = "xfurik00";      // Název databáze

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $database);

// Kontrola připojení
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}

// Získanie ID konferencie z GET parametru
$conference_id = isset($_GET['conference_id']) ? intval($_GET['conference_id']) : 0;

// SQL dotaz pro načtení detailů konferencie
$sql_conference = "SELECT conference_id, name, description, location, start_datetime, end_datetime, capacity, genre FROM conferences WHERE conference_id = $conference_id";
$result_conference = $conn->query($sql_conference);

// Inicializácia dát
$conference_details = null;
if ($result_conference->num_rows > 0) {
    $conference_details = $result_conference->fetch_assoc();
}

// SQL dotaz pro načtení prezentací
$sql_presentations = "SELECT presentation_id, title, description, status, date, start_time, end_time FROM presentations WHERE conference_id = $conference_id AND status = 'approved'";
$result_presentations = $conn->query($sql_presentations);

// Převedení výsledků do JSON formátu pro prezentace
$presentations = array();
if ($result_presentations->num_rows > 0) {
    while ($row = $result_presentations->fetch_assoc()) {
        $presentations[] = $row;
    }
}

$sql_capacity = "SELECT 
                    c.capacity AS total_capacity, 
                    (c.capacity - IFNULL(SUM(r.tickets_count), 0)) AS remaining_capacity
                FROM 
                    conferences c
                LEFT JOIN 
                    reservations r ON c.conference_id = r.conference_id AND r.status != 'cancelled'
                WHERE 
                    c.conference_id = $conference_id
                GROUP BY 
                    c.conference_id";
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

// Uzavření připojení
$conn->close();
?>
