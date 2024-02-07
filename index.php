<?php
// Datenbankkonfiguration
$servername = "localhost";
$username = "bingo";
$password = "Leon";
$dbname = "bingo";

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Bingo-Board initialisieren
$bingoBoard = [];

// Anzahl der benötigten einzigartigen Werte (6x6 gleích 36)
$neededValues = 36; 
$uniqueValues = []; // Array zum Speichern der ausgewählten Werte

// Zufällige Datensätze abrufen
$sql = "SELECT bingo FROM bingoBoard GROUP BY bingo HAVING COUNT(bingo) <= 2 ORDER BY RAND() LIMIT $neededValues";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Daten aus der Datenbank in ein Array einlesen
    while ($row = $result->fetch_assoc()) {
        array_push($uniqueValues, $row["bingo"]);
    }
    // Duplizieren der Werte, um das Bingo-Board zu füllen
    $bingoBoard = array_merge($uniqueValues, array_slice($uniqueValues, 0, $neededValues - count($uniqueValues)));
    shuffle($bingoBoard); // Die Werte mischen
    $bingoBoard = array_chunk($bingoBoard, 6); // In 6x6-Format umwandeln
} else {
    echo "Nicht genügend unterschiedliche Datensätze in der Datenbank.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo-Spiel</title>
    <style>
    <style>
        body {
            font-family: 'Calibri', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }
        .container {
            text-align: center;
        }
        .headline {
            color: #333;
            font-family: 'Calibri', sans-serif;
        }
        table {
            border-collapse: separate; /* Zeilenabstand nur horizontal anwenden */
            border-spacing: 3mm 0mm; /* Horizontaler und vertikaler Abstand */
            margin-top: 20px;
        }
        .bingo-cell {
            font-family: 'Calibri', sans-serif;
            height: 50px;
            line-height: 50px;
            cursor: pointer;
            border: 2px solid #333;
            border-radius: 25px;
            text-align: center;
            width: flex;
            user-select: none;
            padding: 1px;
            transition: background-color 0.3s, color 0.3s;
            background-color: #fff;
            color: #333;
        }
        .selected {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</style>

    </style>
</head>
<body>
    <div class="container">
        <h1 class="headline">Bingo-Spiel</h1>
        <table>
            <?php if (isset($bingoBoard)): ?>
                <?php foreach ($bingoBoard as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td class="bingo-cell"><?php echo $cell; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cells = document.querySelectorAll('.bingo-cell');
            cells.forEach(function(cell) {
                cell.addEventListener('click', function() {
                    this.classList.toggle('selected');
                });
            });
        });
    </script>
</body>



