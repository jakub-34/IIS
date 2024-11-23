<?php
include 'db_config.php';

session_start();

$speaker_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

var_dump($_POST);
$conference_id = isset($_POST['conference_id']) ? intval($_POST['conference_id']) : null;
var_dump($conference_id);

if ($conference_id === null) {
    echo "Chyba: ID konference není definováno!";
    exit;
}

if (isset($_POST['title'], $_POST['description'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $sql = "INSERT INTO presentations (conference_id, title, speaker_id, description, status) 
            VALUES (?, ?, ?, ?, 'not_approved')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isss", $conference_id, $title, $speaker_id, $description);

        if ($stmt->execute()) {
            echo "Prezentace byla úspěšně odeslána.";
        } 
        else {
            echo "Chyba při odesílání prezentace: " . $stmt->error;
        }

        $stmt->close();
    } 
    else {
        echo "Chyba při přípravě SQL dotazu: " . $conn->error;
    }
} 
else {
    echo "Všechna pole musí být vyplněná!";
}

$conn->close();
?>
