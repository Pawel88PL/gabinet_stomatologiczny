<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jeśli nie jest ustawiona zmienna $_SESSION["loggedin"] lub nie jest równa true,
    // przekieruj do strony logowania
    header("location: login.php");
    exit;
}

echo "Witaj, " . $_SESSION["first_name"] . "!";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel pacjenta</title>
</head>

<body>
    <h1>Panel pacjenta</h1>
</body>

</html>