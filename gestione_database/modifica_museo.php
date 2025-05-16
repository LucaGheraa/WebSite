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
    $sql = "SELECT * FROM Museo WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $museo = $result->fetch_assoc();

    if (!$museo) {
        echo "Museo non trovato.";
        exit;
    }
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeMuseo = sanitize_input($_POST['nomeMuseo']);
    $indirizzo = sanitize_input($_POST['indirizzo']);
    $citta = sanitize_input($_POST['citta']);
    $stato = sanitize_input($_POST['stato']);
    $annoFondazione = intval($_POST['annoFondazione']);
    $telefono = sanitize_input($_POST['telefono']);
    $sitoWeb = filter_var($_POST['sitoWeb'], FILTER_SANITIZE_URL);
    $isil = sanitize_input($_POST['isil']);

    if (empty($nomeMuseo) || empty($indirizzo) || empty($citta) || empty($stato) || empty($annoFondazione) || empty($isil)) {
        echo "Tutti i campi obbligatori devono essere compilati.";
        exit;
    }

    if (!preg_match('/^[A-Z0-9-]{4,}$/', $isil)) {
        echo "ISIL non valido.";
        exit;
    }

    $sql = "UPDATE Museo SET NomeMuseo = ?, Indirizzo = ?, Citta = ?, Stato = ?, AnnoFondazione = ?, Telefono = ?, SitoWeb = ?, ISIL = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisssi", $nomeMuseo, $indirizzo, $citta, $stato, $annoFondazione, $telefono, $sitoWeb, $isil, $id);
    $stmt->execute();

    header("Location: visualizza_musei.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Museo</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Modifica Museo</h1>

    <form class="form-style" method="POST">
        <label for="nomeMuseo">Nome Museo:</label>
        <input type="text" id="nomeMuseo" name="nomeMuseo" value="<?php echo sanitize_input($museo['NomeMuseo']); ?>" required>

        <label for="indirizzo">Indirizzo:</label>
        <input type="text" id="indirizzo" name="indirizzo" value="<?php echo sanitize_input($museo['Indirizzo']); ?>" required>

        <label for="citta">Città:</label>
        <input type="text" id="citta" name="citta" value="<?php echo sanitize_input($museo['Citta']); ?>" required>

        <label for="stato">Stato:</label>
        <input type="text" id="stato" name="stato" value="<?php echo sanitize_input($museo['Stato']); ?>" required>

        <label for="annoFondazione">Anno Fondazione:</label>
        <input type="number" id="annoFondazione" name="annoFondazione" value="<?php echo intval($museo['AnnoFondazione']); ?>" required>

        <label for="telefono">Telefono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo sanitize_input($museo['Telefono']); ?>">

        <label for="sitoWeb">Sito Web:</label>
        <input type="url" id="sitoWeb" name="sitoWeb" value="<?php echo sanitize_input($museo['SitoWeb']); ?>">

        <label for="isil">ISIL:</label>
        <input type="text" id="isil" name="isil" value="<?php echo sanitize_input($museo['ISIL']); ?>" readonly required>

        <button type="submit">Salva Modifiche</button>
    </form>

    <div class="source-button">
        <a href="visualizza_musei.php">Torna alla lista dei musei</a>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
