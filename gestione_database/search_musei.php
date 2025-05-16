<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_gherardiluca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); 

$sql = "SELECT * FROM Museo WHERE 
        NomeMuseo LIKE ? OR 
        Citta LIKE ? OR 
        Stato LIKE ? OR 
        AnnoFondazione LIKE ? OR 
        Telefono LIKE ?";

$stmt = $conn->prepare($sql);
$search_param = "%" . $search . "%";
$stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()):
?>
    <tr>
        <td><?= htmlspecialchars($row['NomeMuseo'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['Citta'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['Stato'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['AnnoFondazione'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['Telefono'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><a href="<?= htmlspecialchars($row['SitoWeb'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Visita sito</a></td>
    </tr>
<?php
endwhile;

$stmt->close();
$conn->close();
?>
