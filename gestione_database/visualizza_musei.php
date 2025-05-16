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
    $id_museo = $_GET['delete'];
 
    $check_opere_sql = "SELECT COUNT(*) as opere_count FROM Opera WHERE ISILMuseo = (SELECT ISIL FROM Museo WHERE ID = $id_museo)";
    $check_opere_result = $conn->query($check_opere_sql);
    $opere_count = $check_opere_result->fetch_assoc()['opere_count'];
    
    if ($opere_count > 0) {
        echo "<script>alert('Non è possibile eliminare il museo: ci sono opere associate!');</script>";
    } else {
        $delete_sql = "DELETE FROM Museo WHERE ID = $id_museo";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<script>alert('Museo eliminato con successo');</script>";
        } else {
            echo "<script>alert('Errore durante l\'eliminazione del museo');</script>";
        }
    }
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM Museo WHERE 
        NomeMuseo LIKE '%$search%' OR 
        Citta LIKE '%$search%' OR 
        Stato LIKE '%$search%' OR 
        AnnoFondazione LIKE '%$search%' OR 
        Telefono LIKE '%$search%' OR 
        SitoWeb LIKE '%$search%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza Musei</title>
    
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Visualizza Musei</h1>
    </header>

    <nav class="menu">
    <a href="index1.php"><i class="fas fa-home"></i> Home</a>
    <a href="visualizza_musei.php"><i class="fas fa-landmark"></i> Visualizza Musei</a>
    <a href="visualizza_opere.php"><i class="fas fa-palette"></i> Visualizza Opere</a>
    <a href="aggiungi_museo.php"><i class="fas fa-plus-square"></i> Aggiungi Museo</a>
    <a href="aggiungi_opera.php"><i class="fas fa-plus-circle"></i> Aggiungi Opera</a>
	</nav>
	

    <div class="search-bar">
        <input type="text" id="search" placeholder="Cerca museo..." onkeyup="searchMusei()">
    </div>

    <table class="musei-table">
        <thead>
            <tr>
                <th>ISIL</th>
                <th>Nome Museo</th>
                <th>Indirizzo</th>
                <th>Città</th>
                <th>Stato</th>
                <th>Anno Fondazione</th>
                <th>Telefono</th>
                <th>Sito Web</th>
                <th>Immagine</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody id="museiTable">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['ISIL']."</td>";
                    echo "<td>".$row['NomeMuseo']."</td>";
                    echo "<td>".$row['Indirizzo']."</td>";
                    echo "<td>".$row['Citta']."</td>";
                    echo "<td>".$row['Stato']."</td>";
                    echo "<td>".$row['AnnoFondazione']."</td>";
                    echo "<td>".$row['Telefono']."</td>";
                    echo "<td><a href='".$row['SitoWeb']."' target='_blank'>".$row['SitoWeb']."</a></td>";
                    echo "<td>";
					if (!empty($row['Immagine'])) {
    					echo "<img src='./uploads/" . htmlspecialchars($row['Immagine']) . "' alt='Immagine Museo' style='width:200px; height:auto; border-radius:6px;'>";
					} else {
    					echo "Nessuna immagine";
					}
					echo "</td>";
                    echo "<td>
                            <a href='modifica_museo.php?id=".$row['ID']."'>Modifica</a>
                            <a href='?delete=".$row['ID']."' onclick='return confirm(\"Sei sicuro di voler eliminare questo museo?\");'>Elimina</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Nessun museo trovato</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function searchMusei() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("museiTable");
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
