<?php  
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_gherardiluca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $id_opera = $_GET['delete'];

    $delete_sql = "DELETE FROM Opera WHERE ID = $id_opera";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Opera eliminata con successo');</script>";
    } else {
        echo "<script>alert('Errore durante l\'eliminazione dell\'opera');</script>";
    }
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT Opera.*, Museo.NomeMuseo 
        FROM Opera 
        LEFT JOIN Museo ON Opera.ISILMuseo = Museo.ISIL
        WHERE 
            TitoloOpera LIKE '%$search%' OR 
            Artista LIKE '%$search%' OR 
            AnnoCreazione LIKE '%$search%' OR 
            MaterialeTecnica LIKE '%$search%'";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza Opere</title>
    
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Visualizza Opere</h1>
    </header>

    <nav class="menu">
    <a href="index1.php"><i class="fas fa-home"></i> Home</a>
    <a href="visualizza_musei.php"><i class="fas fa-landmark"></i> Visualizza Musei</a>
    <a href="visualizza_opere.php"><i class="fas fa-palette"></i> Visualizza Opere</a>
    <a href="aggiungi_museo.php"><i class="fas fa-plus-square"></i> Aggiungi Museo</a>
    <a href="aggiungi_opera.php"><i class="fas fa-plus-circle"></i> Aggiungi Opera</a>
	</nav>

    <div class="search-bar">
        <input type="text" id="search" placeholder="Cerca opera..." onkeyup="searchOpere()">
    </div>

    <table class="opere-table">
        <thead>
            <tr>
                <th>Titolo</th>
                <th>Artista</th>
                <th>Anno Creazione</th>
                <th>Materiale / Tecnica</th>
                <th>Museo</th>
                <th>Immagine</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody id="opereTable">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['TitoloOpera']."</td>";
                    echo "<td>".$row['Artista']."</td>";
                    echo "<td>".$row['AnnoCreazione']."</td>";
                    echo "<td>".$row['MaterialeTecnica']."</td>";
                    echo "<td>" . ($row['NomeMuseo'] ?? 'N/A') . "</td>";
                    echo "<td>";
					if (!empty($row['Immagine'])) {
    					echo "<img src='./uploads/" . htmlspecialchars($row['Immagine']) . "' alt='Immagine Museo' style='width:200px; height:auto; border-radius:6px;'>";
					} else {
    					echo "Nessuna immagine";
					}
					echo "</td>";
                    echo "<td>
                            <a href='modifica_opera.php?id=".$row['ID']."'>Modifica</a>
                            <a href='?delete=".$row['ID']."' onclick='return confirm(\"Sei sicuro di voler eliminare questa opera?\");'>Elimina</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nessuna opera trovata</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function searchOpere() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("opereTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            let found = false;
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }
            }
            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
