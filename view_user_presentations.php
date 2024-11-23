<?php
include 'db_config.php';

$query = "SELECT * FROM user_presentation";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} 
else {
    echo "Tabulka user_presentation je prázdná.";
}

$conn->close();
?>
