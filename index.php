<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["inizio_sessione"])) {
    $_SESSION["inizio_sessione"] = time();
}

$inizio_sessione = $_SESSION["inizio_sessione"];

$cookie_name = "visite";
$cookie_time = time() + (86400 * 30); // 30 giorni

if (!isset($_COOKIE[$cookie_name])) {
    setcookie($cookie_name, 1, $cookie_time, "/");
    $visite = 1;
} else {
    $visite = $_COOKIE[$cookie_name] + 1;
    setcookie($cookie_name, $visite, $cookie_time, "/");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Benvenuto - Dashboard Applicazioni</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 10px;
            color: #fff;
        }

        p {
            font-size: 18px;
            margin-bottom: 15px;
            color: #f0f0f0;
        }

        .session-time, .visit-count {
            margin-top: 10px;
            color: #e0e0e0;
            font-size: 16px;
        }

        .menu {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin: 40px 0 20px;
        }

        .menu a {
            text-decoration: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            background-color: #4A90E2;
            border-radius: 10px;
            box-shadow: 0px 6px 18px rgba(74, 144, 226, 0.3);
            transition: background-color 0.3s, transform 0.3s;
        }

        .menu a:hover {
            background-color: #357ABD;
            transform: scale(1.05);
        }

        .email-button, .logout, .github-button {
            margin-top: 20px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
        }

        .email-button {
            background-color: #27AE60;
        }

        .email-button:hover {
            background-color: #2ECC71;
            transform: scale(1.05);
        }

        .logout {
            background-color: #E74C3C;
        }

        .logout:hover {
            background-color: #C0392B;
            transform: scale(1.05);
        }

        .github-button {
            background-color: #333;
            margin-bottom: 30px;
        }

        .github-button:hover {
            background-color: #555;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .menu {
                flex-direction: column;
                gap: 15px;
            }

            h1 {
                font-size: 28px;
            }

            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <h1>Benvenuto, <?php echo htmlspecialchars($_SESSION["nome"]); ?>!</h1>
    <p>Scegli quale applicazione utilizzare:</p>

    <div class="session-time">
        <p>Durata della tua sessione: <span id="session-duration">Caricamento...</span></p>
    </div>

    <div class="visit-count">
        <p>Numero di visite: <?php echo $visite; ?></p>
    </div>

    <div class="menu">
        <a href="gestione_database/index1.php">📁 CRUD Database</a>
        <a href="api/index.html">🔗 API RESTful</a>
    </div>

    <a class="email-button" href="invia_email.php">📧 Invia Email</a>
    <a class="logout" href="logout.php">🚪 Logout</a>

    <a class="github-button" href="https://github.com/LucaGheraa/WebSite" target="_blank" rel="noopener noreferrer">
        🐙 GitHub Repository
    </a>

    <script>
        const inizioSessione = <?php echo $inizio_sessione; ?>;

        function aggiornaDurata() {
            const tempoTrascorso = Math.floor((new Date().getTime() / 1000) - inizioSessione);

            const ore = Math.floor(tempoTrascorso / 3600);
            const minuti = Math.floor((tempoTrascorso % 3600) / 60);
            const secondi = tempoTrascorso % 60;

            document.getElementById("session-duration").textContent = `Ore: ${ore} | Minuti: ${minuti} | Secondi: ${secondi}`;
        }

        setInterval(aggiornaDurata, 1000);
    </script>

</body>
</html>
