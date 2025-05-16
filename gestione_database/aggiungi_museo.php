<?php
$success_msg = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ISIL = trim($_POST["ISIL"] ?? '');
	$NomeMuseo = trim($_POST["NomeMuseo"] ?? '');
	$Indirizzo = trim($_POST["Indirizzo"] ?? '');
	$Citta = trim($_POST["Citta"] ?? '');
	$Stato = trim($_POST["Stato"] ?? '');
	$AnnoFondazione = trim($_POST["AnnoFondazione"] ?? '');
	$Telefono = trim($_POST["Telefono"] ?? '');
	$SitoWeb = trim($_POST["SitoWeb"] ?? '');


    $immagine_path = '';
        if (isset($_FILES["Immagine"]) && $_FILES["Immagine"]["error"] === 0) {
    		$target_dir = "uploads/";
    		$file_name = uniqid() . "_" . basename($_FILES["Immagine"]["name"]);

    		if (!file_exists($target_dir)) {
        		mkdir($target_dir, 0777, true);
    		}

          if (move_uploaded_file($_FILES["Immagine"]["tmp_name"], $target_dir . $file_name)) {
              $immagine_path = $file_name; 
          } else {
              $error_msg = "Errore durante il caricamento dell'immagine.";
          }
      }

    if (empty($error_msg)) {
        if (
    		empty($ISIL) || empty($NomeMuseo) || empty($Indirizzo) ||
    		empty($Citta) || empty($Stato) || empty($AnnoFondazione) ||
    		empty($Telefono) || empty($SitoWeb)
		) {
    		$error_msg = "Tutti i campi sono obbligatori.";
		} elseif (!preg_match('/^[A-Z]{2}-[A-Z]{2,3}[0-9]{3}$/', $ISIL)) {
    		$error_msg = "Il codice ISIL non è valido. Deve essere nel formato XX-XXX999 (es: IT-RM456).";
        } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s\'\-]{2,100}$/u', $NomeMuseo)) {
    		$error_msg = "Il nome del museo deve contenere solo lettere, spazi, apostrofi o trattini (max 100 caratteri).";
		} elseif (!preg_match('/^[A-Za-zÀ-ÿ0-9\s,\.\'\-°]{5,100}$/u', $Indirizzo)) {
    		$error_msg = "L'indirizzo non è valido. Usa solo lettere, numeri, spazi, virgole, punti, apostrofi, trattini e simbolo ° (max 100 caratteri).";
		} elseif (!preg_match('/^[A-Za-zÀ-ÿ\'\-\s]{2,80}$/u', $Citta)) {
    		$error_msg = "Il nome della città non è valido. Usa solo lettere, spazi, apostrofi e trattini (min 2, max 80 caratteri).";
		} elseif (!preg_match('/^[A-Za-zÀ-ÿ\'\-\s]{2,80}$/u', $Stato)) {
    		$error_msg = "Il nome dello stato non è valido. Usa solo lettere, spazi, apostrofi e trattini (min 2, max 80 caratteri).";
   		} elseif (!preg_match('/^\d{4}$/', $AnnoFondazione) || $AnnoFondazione < 1000 || $AnnoFondazione > date("Y")) {
        	$error_msg = "L'anno di fondazione deve essere un numero di 4 cifre valido, compreso tra 1000 e l'anno corrente.";
		}elseif (!preg_match('/^\+\d{1,3}(?:[ ]?\d+)+$/', $Telefono)) {
    		$error_msg = "Il numero di telefono non è valido. Usa il formato internazionale con + prefisso e numeri, es: +39 06 69884676.";
		}elseif (!filter_var($SitoWeb, FILTER_VALIDATE_URL) || !(substr($SitoWeb, 0, 7) === 'http://' || substr($SitoWeb, 0, 8) === 'https://')) {
    		$error_msg = "L'URL del sito web non è valido. Deve iniziare con http:// o https://";
		} else {

            $conn = new mysqli("localhost", "root", "", "my_gherardiluca");
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO Museo (ISIL, NomeMuseo, Indirizzo, Citta, Stato, AnnoFondazione, Telefono, SitoWeb, Immagine) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssssssss", $ISIL, $NomeMuseo, $Indirizzo, $Citta, $Stato, $AnnoFondazione, $Telefono, $SitoWeb, $immagine_path);
                if ($stmt->execute()) {
                    $success_msg = "Museo aggiunto con successo!";
                } else {
                    $error_msg = "Errore nell'inserimento: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_msg = "Errore nella preparazione della query: " . $conn->error;
            }

            $conn->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Museo</title>

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Applicazione Musei Gherardi Luca</h1>

    <br><br>
    <div class="menu">
        <a href="index1.php"><i class="fas fa-home"></i> Home</a>
        <a href="visualizza_musei.php"><i class="fas fa-landmark"></i> Visualizza Musei</a>
        <a href="visualizza_opere.php"><i class="fas fa-palette"></i> Visualizza Opere</a>
        <a href="aggiungi_museo.php"><i class="fas fa-plus-square"></i> Aggiungi Museo</a>
        <a href="aggiungi_opera.php"><i class="fas fa-plus-circle"></i> Aggiungi Opera</a>
    </div>

    <h2>Aggiungi un Nuovo Museo</h2>

    <?php if (!empty($success_msg)): ?>
        <p class="success"><?= htmlspecialchars($success_msg) ?></p>
    <?php elseif (!empty($error_msg)): ?>
        <p class="error"><?= htmlspecialchars($error_msg) ?></p>
    <?php endif; ?>

    <form class="form-style" method="POST" enctype="multipart/form-data">
        <label for="ISIL">ISIL*</label><br>
        <input type="text" name="ISIL" required><br><br>

        <label for="NomeMuseo">Nome Museo*</label><br>
        <input type="text" name="NomeMuseo" required><br><br>

        <label for="Indirizzo">Indirizzo*</label><br>
        <input type="text" name="Indirizzo" required><br><br>

        <label for="Citta">Città*</label><br>
        <input type="text" name="Citta" required><br><br>

        <label for="Stato">Stato*</label><br>
        <input type="text" name="Stato" required><br><br>

        <label for="AnnoFondazione">Anno di Fondazione*</label><br>
        <input type="number" name="AnnoFondazione" required><br><br>

        <label for="Telefono">Telefono*</label><br>
        <input type="text" name="Telefono" required><br><br>

        <label for="SitoWeb">Sito Web*</label><br>
        <input type="url" name="SitoWeb" required><br><br>
        
        <label for="Immagine">Immagine Museo</label><br>
    	<input type="file" name="Immagine" accept="image/*"><br><br>

        <input type="submit" value="Aggiungi Museo">
    </form>

    <div class="footer">
        <p>© Creato da Gherardi Luca 2024</p>
    </div>
</div>

</body>
</html>
