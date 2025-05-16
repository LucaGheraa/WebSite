<?php
session_start();
session_unset();
session_destroy();

if (isset($_COOKIE["login_token"])) {
    setcookie("login_token", "", time() - 3600, "/");
}

if (isset($_COOKIE["visite"])) {
    setcookie("visite", "", time() - 3600, "/");
}

header("Location: login.php");
exit();
?>
