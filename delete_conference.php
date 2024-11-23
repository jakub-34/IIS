<?php
include 'db_config.php';

header('Content-Type: application/json'); 

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['conference_id'])) {
    echo json_encode(['success' => false, 'error' => 'Conference ID is required']);
    exit;
}

$conferenceId = intval($data['conference_id']);

$conn->begin_transaction();

try {
    // Smazání záznamů z tabulky `user_presentation`
    $stmt = $conn->prepare("DELETE up FROM user_presentation up
                            JOIN presentations p ON up.presentation_id = p.presentation_id
                            WHERE p.conference_id = ?");
    $stmt->bind_param("i", $conferenceId);
    $stmt->execute();

    // Smazání záznamů z tabulky `reservations`
    $stmt = $conn->prepare("DELETE FROM reservations WHERE conference_id = ?");
    $stmt->bind_param("i", $conferenceId);
    $stmt->execute();

    // Smazání záznamů z tabulky `presentations`
    $stmt = $conn->prepare("DELETE FROM presentations WHERE conference_id = ?");
    $stmt->bind_param("i", $conferenceId);
    $stmt->execute();

    // Smazání samotné konference
    $stmt = $conn->prepare("DELETE FROM conferences WHERE conference_id = ?");
    $stmt->bind_param("i", $conferenceId);
    $stmt->execute();

    // Potvrzení změn
    $conn->commit();

    echo json_encode(['success' => true]);
} 
catch (Exception $e) {
    // Zpětné vrácení změn v případě chyby
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
