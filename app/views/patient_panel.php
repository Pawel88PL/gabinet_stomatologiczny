<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Tutaj możesz załadować dane pacjenta z bazy danych
// $upcomingAppointments = loadUpcomingAppointments($_SESSION["id"]);
// $pastAppointments = loadPastAppointments($_SESSION["id"]);

?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel pacjenta</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/patient_panel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <h1>Panel pacjenta</h1>
            <p>Witaj, <strong><?php echo htmlspecialchars($_SESSION["first_name"]); ?></strong>!</p>
            <a href="../controllers/logout_controller.php" class="btn btn-danger">Wyloguj się</a>
        </div>

        <div class="card">
            <h2>Twój profil</h2>
            <!-- Profil pacjenta -->
            <!-- ... -->
        </div>

        <div class="card">
            <h2>Zaplanowane wizyty</h2>
            <!-- Upcoming appointments -->
            <?php //echo $upcomingAppointments; 
            ?>
        </div>

        <div class="card">
            <h2>Historia wizyt</h2>
            <!-- Past appointments -->
            <?php //echo $pastAppointments; 
            ?>
        </div>

        <!-- Other sections -->
        <!-- ... -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>