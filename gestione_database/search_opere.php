<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_gherardiluca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); // Sanitizzazione dell'input

$sql = "SELECT * FROM Opera WHERE 
        TitoloOpera LIKE ? OR 
        Artista LIKE ? OR 
        AnnoCreazione LIKE ? OR 
        MaterialeTecnica LIKE ?";

$stmt = $conn->prepare($sql);
$search_param = "%" . $search . "%"; 
$stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()):
?>
    <tr>
        <td><?= htmlspecialchars($row['TitoloOpera'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['Artista'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['AnnoCreazione'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['MaterialeTecnica'], ENT_QUOTES, 'UTF-8') ?></td>
    </tr>
<?php
endwhile;

$stmt->close();
$conn->close();
?>
