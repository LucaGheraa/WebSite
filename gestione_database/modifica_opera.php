<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_gherardiluca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    $sql = "SELECT * FROM Opera WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $opera = $result->fetch_assoc();

    if (!$opera) {
        echo "Opera non trovata.";
        exit;
    }
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titoloOpera = sanitize_input($_POST['titoloOpera']);
    $artista = sanitize_input($_POST['artista']);
    $annoCreazione = intval($_POST['annoCreazione']);
    $materialeTecnica = sanitize_input($_POST['materialeTecnica']);

    if (empty($titoloOpera) || empty($artista) || empty($annoCreazione) || empty($materialeTecnica)) {
        echo "Tutti i campi sono obbligatori.";
        exit;
    }

    $sql = "UPDATE Opera SET TitoloOpera = ?, Artista = ?, AnnoCreazione = ?, MaterialeTecnica = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $titoloOpera, $artista, $annoCreazione, $materialeTecnica, $id);
    $stmt->execute();

    header("Location: visualizza_opere.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Opera</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Modifica Opera</h1>

    <form class="form-style" method="POST">
        <label for="titoloOpera">Titolo Opera:</label>
        <input type="text" id="titoloOpera" name="titoloOpera" value="<?php echo sanitize_input($opera['TitoloOpera']); ?>" required>

        <label for="artista">Artista:</label>
        <input type="text" id="artista" name="artista" value="<?php echo sanitize_input($opera['Artista']); ?>" required>

        <label for="annoCreazione">Anno Creazione:</label>
        <input type="number" id="annoCreazione" name="annoCreazione" value="<?php echo intval($opera['AnnoCreazione']); ?>" required>

        <label for="materialeTecnica">Materiale e Tecnica:</label>
        <input type="text" id="materialeTecnica" name="materialeTecnica" value="<?php echo sanitize_input($opera['MaterialeTecnica']); ?>" required>

        <button type="submit">Salva Modifiche</button>
    </form>

    <div class="source-button">
        <a href="visualizza_opere.php">Torna alla lista delle opere</a>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
