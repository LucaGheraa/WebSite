<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_gherardiluca";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO utenti (nome, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $password_hash);

    if ($stmt->execute()) {
        $messaggio = "<p class='success'>Registrazione completata! <a href='login.php'>Accedi</a></p>";
    } else {
        $messaggio = "<p class='error'>Errore: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to right, #1f4037, #99f2c8);
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #1f4037;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #4A90E2;
            outline: none;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background-color: #4A90E2;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0px 6px 18px rgba(74, 144, 226, 0.3);
            transition: background-color 0.3s, transform 0.3s;
        }

        .register-btn:hover {
            background-color: #357ABD;
            transform: scale(1.05);
        }

        .login-btn {
            display: block;
            margin-top: 18px;
            font-size: 16px;
            color: #F39C12;
            text-decoration: none;
            font-weight: 600;
        }

        .error, .success {
            margin-top: 15px;
            font-weight: bold;
        }

        .error {
            color: #E74C3C;
        }

        .success {
            color: #2ECC71;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 24px;
            }

            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Registrati</h2>
    <form method="POST">
        <div class="input-group">
            <input type="text" name="nome" placeholder="Nome" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="register-btn">Registrati</button>
    </form>
    <a href="login.php" class="login-btn">Hai già un account? Accedi</a>
    <?= $messaggio ?>
</div>

</body>
</html>
