<?php
include 'db_config.php';

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'];

try {
    // Delete user from database
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } 
    else {
        // Check if the error is caused by a foreign key constraint
        if ($conn->errno == 1451) { // 1451 Is error code for "Cannot delete or update a parent row"
            echo json_encode(['success' => false, 'error' => "Can't delete this user, because they are part of a conference."]);
        } 
        else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    }
} 
catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) { // 1451 Is error code for "Cannot delete or update a parent row"
        echo json_encode(['success' => false, 'error' => "Can't delete this user, because they are part of a conference."]);
    } 
    else {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

$stmt->close();
$conn->close();
?>
