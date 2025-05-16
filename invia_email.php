<?php
session_start();

if (!isset($_SESSION["email"]) || !filter_var($_SESSION["email"], FILTER_VALIDATE_EMAIL)) {
    die("Email mittente non valida o non trovata in sessione.");
}

$mittente = $_SESSION["email"];

$messaggio_risultato = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destinatario = $_POST["destinatario"] ?? '';
    $oggetto = $_POST["oggetto"] ?? '';
    $messaggio = $_POST["messaggio"] ?? '';

    if (!filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
        $messaggio_risultato = "<p style='color: #e74c3c; font-weight: bold;'>Email destinatario non valida.</p>";
    } else {
        $headers = "From: $mittente\r\n";
        $headers .= "Reply-To: $mittente\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($destinatario, $oggetto, $messaggio, $headers)) {
            $messaggio_risultato = "<p style='color: #27ae60; font-weight: bold;'>Email inviata con successo!</p>";
        } else {
            $messaggio_risultato = "<p style='color: #e74c3c; font-weight: bold;'>Errore durante l'invio dell'email.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Invia Email</title>
</head>
<body style="font-family: 'Poppins', sans-serif; background: linear-gradient(to right, #1f4037, #99f2c8); color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; padding: 20px;">
    <div style="background: white; padding: 40px; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); width: 100%; max-width: 450px; text-align: center;">
        <h2 style="font-size: 28px; margin-bottom: 25px; color: #1f4037; font-weight: bold;">Invia Email</h2>
        <?= $messaggio_risultato ?>
        <form method="POST" style="display: flex; flex-direction: column; gap: 15px; text-align: left;">
            <input 
                type="email" 
                name="destinatario" 
                placeholder="Email destinatario" 
                required 
                style="padding: 12px; font-size: 16px; border: 1px solid #ccc; border-radius: 8px; outline: none; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#4A90E2';" 
                onblur="this.style.borderColor='#ccc';"
            >
            <input 
                type="text" 
                name="oggetto" 
                placeholder="Oggetto" 
                required 
                style="padding: 12px; font-size: 16px; border: 1px solid #ccc; border-radius: 8px; outline: none; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#4A90E2';" 
                onblur="this.style.borderColor='#ccc';"
            >
            <textarea 
                name="messaggio" 
                placeholder="Messaggio" 
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
                Invia Email
            </button>
        </form>
        <p style="margin-top: 20px; font-size: 14px; color: #555;">Email mittente: <strong><?= htmlspecialchars($mittente) ?></strong></p>
    </div>
</body>
</html>
