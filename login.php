<?php
session_start();

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
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password_hash, nome FROM utenti WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_hash, $nome);
        $stmt->fetch();
       	if (password_verify($password, $password_hash)) {
          $_SESSION["user_id"] = $user_id;
          $_SESSION["nome"] = $nome;
          $_SESSION["email"] = $email;  // <-- aggiungi questa riga
          header("Location: index.php");
          exit;
      	}else {
            $messaggio = "<p class='error'>Password errata.</p>";
        }
    } else {
        $messaggio = "<p class='error'>Email non registrata.</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

        .login-btn {
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

        .login-btn:hover {
            background-color: #357ABD;
            transform: scale(1.05);
        }

        .register-link {
            display: block;
            margin-top: 18px;
            font-size: 16px;
            color: #F39C12;
            text-decoration: none;
            font-weight: 600;
        }

        .error {
            color: #E74C3C;
            margin-top: 15px;
            font-weight: bold;
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
    <h2>Accedi</h2>
    <form method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="login-btn">Login</button>
    </form>
    <a href="register.php" class="register-link">Non hai un account? Registrati</a>
    <?= $messaggio ?>
</div>

</body>
</html>
