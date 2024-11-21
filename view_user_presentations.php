<?php
include 'db_config.php'; // Připojení k databázi

$query = "SELECT * FROM user_presentation";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        print_r($row); // Výpis řádků do terminálu
    }
} else {
    echo "Tabulka user_presentation je prázdná.";
}

$conn->close();
?>
