<?php
session_start();

// Sprawdzanie, czy użytkownik ma uprawnienia administracyjne
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

$update_err = "";
if (isset($_SESSION['update_err'])) {
    $update_err = $_SESSION['update_err'];
    unset($_SESSION['update_err']); // Czyszczenie błędu z sesji
}

$password_err = "";
if (isset($_SESSION['$password_err'])) {
    $password_err = $_SESSION['$password_err'];
    unset($_SESSION['$password_err']); // Czyszczenie błędu z sesji
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
    <title>Panel dentysty</title>
    <link rel="stylesheet" href="../../public/css/patient_panel.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php include 'shared_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center" id="profile-section">
                    <h2>Witam doktorze <strong><?php echo htmlspecialchars($_SESSION["first_name"]); ?></strong>, oto twój panel!</h2>
                    <div class="row justify-content-center">
                        <p class="col-md-8">
                            Możesz przeglądać w nim zaplanowane wizyty jak i historię przeprowadzonych wizyt.
                        </p>
                    </div>
                </div>

                <div class="card">
                    <h2>Zaplanowane wizyty:</h2>
                    <p>Coś tutaj pusto :)</p>
                    <?php //echo $upcomingAppointments; 
                    ?>
                </div>

                <div class="card">
                    <h2>Historia wizyt:</h2>
                    <p>Coś tutaj pusto :)</p>
                    <?php //echo $pastAppointments; 
                    ?>
                </div>

                <div class="card">
                    <div class="card-body">


                        <h2 class="card-title">Twoje dane:</h2>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <p><strong>Imię:</strong> <?php echo htmlspecialchars($_SESSION["first_name"]); ?></p>
                                <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($_SESSION["last_name"]); ?></p>
                                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                            </div>
                            <div>
                                <p><strong>W celu zmiany danych osobowych skontaktuj się z administratorem systemu.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>