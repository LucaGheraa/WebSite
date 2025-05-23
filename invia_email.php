<?php
session_start();

if (!isset($_SESSION["email"]) || !filter_var($_SESSION["email"], FILTER_VALIDATE_EMAIL)) {
    die("Email non valida o non trovata in sessione.");
}

$email_utente = $_SESSION["email"];
$destinatario = "gherardi.luca.studente@itispaleocapa.it";

$messaggio_risultato = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oggetto = trim($_POST["oggetto"] ?? '');
    $messaggio = trim($_POST["messaggio"] ?? '');

    if (empty($oggetto) || empty($messaggio)) {
        $messaggio_risultato = "<p style='color: #e74c3c; font-weight: bold;'>Tutti i campi sono obbligatori.</p>";
    } else {
        $headers = "From: sito-feedback@tuodominio.altervista.org\r\n";
        $headers .= "Reply-To: $email_utente\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $corpo_mail = "Hai ricevuto un nuovo feedback dal sito:\n\n";
        $corpo_mail .= "Email utente: $email_utente\n\n";
        $corpo_mail .= "Messaggio:\n$messaggio";

        if (mail($destinatario, $oggetto, $corpo_mail, $headers)) {
            $messaggio_risultato = "<p style='color: #27ae60; font-weight: bold;'>Feedback inviato con successo!</p>";
        } else {
            $messaggio_risultato = "<p style='color: #e74c3c; font-weight: bold;'>Errore durante l'invio del feedback.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
</head>
<body style="font-family: 'Poppins', sans-serif; background: linear-gradient(to right, #1f4037, #99f2c8); color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; padding: 20px;">
    <div style="background: white; padding: 40px; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); width: 100%; max-width: 450px; text-align: center;">
        <h2 style="font-size: 28px; margin-bottom: 25px; color: #1f4037; font-weight: bold;">Inviaci un Feedback!</h2>
        <?= $messaggio_risultato ?>
        <form method="POST" style="display: flex; flex-direction: column; gap: 15px; text-align: left;">
            <input 
                type="text" 
                name="oggetto" 
                placeholder="Oggetto del feedback" 
                required 
                style="padding: 12px; font-size: 16px; border: 1px solid #ccc; border-radius: 8px; outline: none; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#4A90E2';" 
                onblur="this.style.borderColor='#ccc';"
            >
            <textarea 
                name="messaggio" 
                placeholder="Scrivi il tuo feedback..." 
                required 
                rows="6" 
                style="padding: 12px; font-size: 16px; border: 1px solid #ccc; border-radius: 8px; resize: vertical; outline: none; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#4A90E2';" 
                onblur="this.style.borderColor='#ccc';"
            ></textarea>
            <button 
                type="submit" 
                style="padding: 12px; font-size: 18px; font-weight: bold; color: white; background-color: #4A90E2; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 6px 18px rgba(74,144,226,0.3); transition: background-color 0.3s, transform 0.3s;"
                onmouseover="this.style.backgroundColor='#357ABD'; this.style.transform='scale(1.05)';"
                onmouseout="this.style.backgroundColor='#4A90E2'; this.style.transform='scale(1)';"
            >
                Invia Feedback
            </button>
        </form>
        <p style="margin-top: 20px; font-size: 14px; color: #555;">Email inviata da: <strong><?= htmlspecialchars($email_utente) ?></strong></p>
        <a href="index.php" style="display: inline-block; margin-top: 20px; text-decoration: none; color: #4A90E2; font-weight: bold;">← Torna all'Home</a>
    </div>
</body>
</html>
