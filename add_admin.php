<?php
include 'db_config.php';

// Hashování hesla
$adminPassword = password_hash('admin', PASSWORD_BCRYPT);

// SQL dotaz pro vložení admina
$sql = "INSERT INTO users (name, lastname, username, password, role) 
        VALUES ('Admin', 'Admin', 'admin', '$adminPassword', 'admin')";

// Spuštění dotazu
if ($conn->query($sql) === TRUE) {
    echo "Admin byl úspěšně přidán.";
} 
else {
    echo "Chyba při přidávání admina: " . $conn->error;
}

// Uzavření připojení
$conn->close();
?>