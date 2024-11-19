<?php
include 'db_config.php'; // Připojení k databázi

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conference_id = $_GET['id'];

    $sql = "SELECT * FROM conferences WHERE conference_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $conference_id);
    $stmt->execute();
    
    $stmt->store_result();
    $stmt->bind_result($conference_id, $name, $description, $genre, $location, $start_datetime, $end_datetime, $price, $capacity, $organizer_id);

    if ($stmt->fetch()) {
        $conference = [
            'conference_id' => $conference_id,
            'name' => $name,
            'description' => $description,
            'genre' => $genre,
            'location' => $location,
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime,
            'price' => $price,
            'capacity' => $capacity,
            'organizer_id' => $organizer_id
        ];
    } else {
        echo "Konferencia nebola nájdená.";
        exit;
    }
} else {
    echo "Neplatné ID konferencie.";
    exit;
}
$conn->close();

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seznam Konferencí</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="header-container">
        <h2 style="float: left;">
            <a href="index.html" style="text-decoration: none; color: inherit;">
                <i class="fas fa-home" style="margin-right: 5px;"></i>
                Konference
            </a>
        </h2>
        <div style="float: right;">
            <a href="login.html" id="loginLogoutButton" class="login-button">Login</a>
            <a href="admin_page.html" id="adminPageButton" class="login-button" style="display: none; margin-right: 10px;">Admin Page</a>
        </div>
    </div>
    

    <?php if (isset($conference)): ?>
        <p><strong><?php echo htmlspecialchars($conference['name']); ?></strong></p>
        <p><strong>Popis:</strong> <?php echo htmlspecialchars($conference['description']); ?></p>
        <p><strong>Žáner:</strong> <?php echo htmlspecialchars($conference['genre']); ?></p>
        <p><strong>Miesto:</strong> <?php echo htmlspecialchars($conference['location']); ?></p>
        <p><strong>Začiatok:</strong> <?php echo htmlspecialchars($conference['start_datetime']); ?></p>
        <p><strong>Koniec:</strong> <?php echo htmlspecialchars($conference['end_datetime']); ?></p>
        <p><strong>Cena:</strong> €<?php echo htmlspecialchars($conference['price']); ?></p>
        <p><strong>Kapacita:</strong> <?php echo htmlspecialchars($conference['capacity']); ?> osôb</p>
    <?php else: ?>
        <p>Konferencia nebola nájdená.</p>
    <?php endif; ?>

</body>
</html>