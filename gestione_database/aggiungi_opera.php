<?php
$success_msg = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TitoloOpera = $_POST["TitoloOpera"] ?? '';
    $Artista = $_POST["Artista"] ?? '';
    $AnnoCreazione = $_POST["AnnoCreazione"] ?? '';
    $MaterialeTecnica = $_POST["MaterialeTecnica"] ?? '';
    $Descrizione = $_POST["Descrizione"] ?? '';
    $ISILMuseo = $_POST["ISILMuseo"] ?? '';

    if (
        empty($TitoloOpera) || empty($Artista) || empty($AnnoCreazione) ||
        empty($MaterialeTecnica) || empty($Descrizione) || empty($ISILMuseo)
    ) {
        $error_msg = "Tutti i campi sono obbligatori.";
    } elseif (!isset($_POST['TitoloOpera']) || !preg_match("/^[A-Za-z0-9À-ÿ\s'’\-.,!?]{2,150}$/u", $_POST['TitoloOpera'])) {
    	$error_msg = "Il titolo dell'opera deve contenere solo lettere, numeri, spazi e alcuni segni di punteggiatura (max 150 caratteri).";
	} elseif (!isset($_POST['Artista']) || !preg_match("/^[A-Za-zÀ-ÿ\s'’\-]{2,100}$/u", $_POST['Artista'])) {
    	$error_msg = "Il nome dell'artista deve contenere solo lettere, spazi, apostrofi o trattini (max 100 caratteri).";
	} elseif (intval($AnnoCreazione) < -999 || intval($AnnoCreazione) > date("Y")) {
    	$error_msg = "L'anno di creazione deve essere compreso tra -999 e " . date("Y") . ".";
	} elseif (!preg_match('/^[A-Za-zÀ-ÿ\s]{2,100}$/u', $MaterialeTecnica)) {
    	$error_msg = "Il campo Materiale e Tecnica deve contenere solo lettere e spazi (2-100 caratteri).";
	} elseif (!preg_match("/^[A-Za-zÀ-ÿ0-9\s\.,!?:;'\"\-\(\)\[\]]{0,500}$/u", $Descrizione)) {
    	$error_msg = "La descrizione può contenere lettere, numeri, spazi e punteggiatura (max 500 caratteri).";
	} elseif (!preg_match('/^[A-Z]{2}-[A-Z]{2,3}[0-9]{3}$/', $ISILMuseo)) {
    	$error_msg = "Il codice ISIL non è valido. Deve essere nel formato XX-XXX999 (es: IT-RM456).";
    }

        $conn = new mysqli("localhost", "username", "password", "my_gherardiluca");
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        $immagine_path = '';
        if (isset($_FILES["Immagine"]) && $_FILES["Immagine"]["error"] === 0) {
    		$target_dir = "uploads/";
    		$file_name = uniqid() . "_" . basename($_FILES["Immagine"]["name"]);

    		if (!file_exists($target_dir)) {
        		mkdir($target_dir, 0777, true);
    		}

          if (move_uploaded_file($_FILES["Immagine"]["tmp_name"], $target_dir . $file_name)) {
              $immagine_path = $file_name;  // <-- salva SOLO il nome del file, non la cartella
          } else {
              $error_msg = "Errore durante il caricamento dell'immagine.";
          }
      }


        if (empty($error_msg)) {
            $stmt = $conn->prepare("INSERT INTO Opera (TitoloOpera, Artista, AnnoCreazione, MaterialeTecnica, Descrizione, ISILMuseo, Immagine) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssissss", $TitoloOpera, $Artista, $AnnoCreazione, $MaterialeTecnica, $Descrizione, $ISILMuseo, $immagine_path);
                if ($stmt->execute()) {
                    $success_msg = "Opera aggiunta con successo!";
                } else {
                    $error_msg = "Errore nell'inserimento: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_msg = "Errore nella preparazione della query: " . $conn->error;
            }
        }

        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicazione Musei Gherardi Luca</title>

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

    <h2>Aggiungi una Nuova Opera</h2>

    <?php if (!empty($success_msg)): ?>
        <p class="success"><?= htmlspecialchars($success_msg) ?></p>
    <?php elseif (!empty($error_msg)): ?>
        <p class="error"><?= htmlspecialchars($error_msg) ?></p>
    <?php endif; ?>

    <form class="form-style" method="POST" enctype="multipart/form-data">
        <label for="TitoloOpera">Titolo Opera*</label><br>
        <input type="text" name="TitoloOpera" required><br><br>

        <label for="Artista">Artista*</label><br>
        <input type="text" name="Artista" required><br><br>

        <label for="AnnoCreazione">Anno di Creazione*</label><br>
        <input type="number" name="AnnoCreazione" required><br><br>

        <label for="MaterialeTecnica">Materiale e Tecnica*</label><br>
        <textarea name="MaterialeTecnica" required></textarea><br><br>

        <label for="Descrizione">Descrizione*</label><br>
        <textarea name="Descrizione" required></textarea><br><br>

        <label for="ISILMuseo">ISIL del Museo*</label><br>
        <input type="text" name="ISILMuseo" required><br><br>

        <label for="Immagine">Immagine (opzionale)</label><br>
        <input type="file" name="Immagine" accept="image/*"><br><br>

        <input type="submit" value="Aggiungi Opera">
    </form>

    <div class="footer">
        <p>© Creato da Gherardi Luca 2024</p>
    </div>
</div>

</body>
</html>
