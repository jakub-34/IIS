<?php
// Nastavení připojení k databázi
$servername = "localhost";
$username = "xfurik00";
$password = "5ujomzam";
$database = "xfurik00";

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $database);

// Kontrola připojení
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}

// Získání ID konference z URL
$conference_id = isset($_POST['conference_id']) ? intval($_POST['conference_id']) : null;

if ($conference_id === null) {
    echo "Chyba: ID konference není definováno!";
    exit;
}// Konference by měla být předána jako GET parametr


// Ověření, že všechna pole byla vyplněna
if (isset($_POST['title'], $_POST['description'])) {
    $title = $_POST['title'];
    $photo_url = $_POST['photo-url'];
    $description = $_POST['description'];

    // Příprava SQL dotazu pro vložení nové prezentace
    $sql = "INSERT INTO presentations (conference_id, title, poster_url, description, status) 
            VALUES (?, ?, ?, ?, 'not_approved')";

    if ($stmt = $conn->prepare($sql)) {
        // Vázání parametrů
        $stmt->bind_param("isss", $conference_id, $title, $photo_url, $description);

        // Spuštění dotazu a kontrola, zda bylo vložení úspěšné
        if ($stmt->execute()) {
            echo "Prezentace byla úspěšně odeslána.";
        } else {
            echo "Chyba při odesílání prezentace: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Chyba při přípravě SQL dotazu: " . $conn->error;
    }
} else {
    echo "Všechna pole musí být vyplněná!";
}

$conn->close();
?>
