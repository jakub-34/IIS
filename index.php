<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
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
            <a href="index.php" style="text-decoration: none; color: inherit;">
                <i class="fas fa-home" style="margin-right: 5px;"></i>
                Konference
            </a>
        </h2>
        <?php if ($isLoggedIn): ?>
            <!-- Tlačítko Logout, pokud je uživatel přihlášen -->
            <a href="logout.php" class="login-button">Logout</a>
        <?php else: ?>
            <!-- Tlačítko Login, pokud není uživatel přihlášen -->
            <a href="login.html" class="login-button">Login</a>
        <?php endif; ?>
    </div>
    
    <div class="body">
        <table>
            <tbody id="conferenceTable">
            </tbody>
        </table>
    </div>
    
    <a href="add.html" class="fixed-button">
        <i class="fas fa-plus"></i>
    </a>

<script>
// Načtení konferencí z PHP skriptu
fetch('get_conferences.php')
    .then(response => response.json())
    .then(data => {
        var count = 1;
        const tableBody = document.getElementById('conferenceTable'); // Předpokládám, že existuje s tímto ID

        // Hlavní obalovací div pro dva sloupce
        const mainContainer = document.createElement('div');
        mainContainer.style.display = 'flex';        // Flexbox pro vytvoření dvou sloupců
        mainContainer.style.justifyContent = 'space-between';
        mainContainer.style.gap = '20px';            // Mezera mezi sloupci (volitelně)

        // Levý sloupec (pro lichá čísla)
        const leftColumn = document.createElement('div');
        leftColumn.style.flex = '1';

        // Pravý sloupec (pro sudá čísla)
        const rightColumn = document.createElement('div');
        rightColumn.style.flex = '1';

        // Iterace přes data konferencí
        data.forEach(conference => {
            // Hlavní obalovací div pro jednotlivou konferenci
            const containerDiv = document.createElement('div');
            containerDiv.style.display = 'flex';
            containerDiv.style.alignItems = 'center';
            containerDiv.style.marginBottom = '10px';

            // Číslo konference
            const text = document.createElement('p');
            text.innerHTML = `${count}`;
            text.style.marginRight = '10px';
            text.style.fontWeight = 'bold';

            // Wrapper div pro obsah konference
            const wrapperDiv = document.createElement('div');
            wrapperDiv.className = "conference";

            // První řádek s názvem konference
            const row1 = document.createElement('tr');
            row1.innerHTML = `<td colspan="2">${conference.name}</td>`;
            row1.style.paddingBottom = '10vw';
            wrapperDiv.appendChild(row1);

            // Druhý řádek s časovými údaji
            const row2 = document.createElement('tr');
            row2.innerHTML = `<td>${new Date(conference.start_datetime).getDate()}.${new Date(conference.start_datetime).getMonth() + 1}. - ${new Date(conference.end_datetime).toLocaleDateString()}</td><td>${new Date(conference.start_datetime).toLocaleTimeString('cs-CZ',  { hour: '2-digit', minute: '2-digit', hour12: false })}</td>-<td>${new Date(conference.end_datetime).toLocaleTimeString('cs-CZ',  { hour: '2-digit', minute: '2-digit', hour12: false })}</td>`;
            wrapperDiv.appendChild(row2);

            // Přidání čísla a wrapperDiv do containerDiv
            containerDiv.appendChild(text);
            containerDiv.appendChild(wrapperDiv);

            // Přidání do levého nebo pravého sloupce podle sudosti/lichosti
            if (count % 2 !== 0) {
                leftColumn.appendChild(containerDiv);
            } else {
                rightColumn.appendChild(containerDiv);
            }

            count++;
        });

        // Přidání obou sloupců do hlavního kontejneru
        mainContainer.appendChild(leftColumn);
        mainContainer.appendChild(rightColumn);

        // Přidání hlavního kontejneru do tableBody
        tableBody.appendChild(mainContainer);

    })
    .catch(error => console.error('Chyba při načítání dat:', error));
</script>

</body>
</html>
