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
    <link rel="stylesheet" href="../../public/css/patient_panel.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1>Witaj, <strong><?php echo htmlspecialchars($_SESSION["first_name"]); ?></strong>!</h1>
                </div>

                <div class="card">
                    <h2>Zaplanowane wizyty:</h2>
                    <!-- Upcoming appointments -->
                    <?php //echo $upcomingAppointments; 
                    ?>
                </div>

                <div class="card">
                    <h2>Historia wizyt:</h2>
                    <!-- Past appointments -->
                    <?php //echo $pastAppointments; 
                    ?>
                </div>

                <div class="card">
                    <h2>Twoje dane:</h2>
                    <div class="row">
                        <div class="col-md-3">
                            <p>Imię:</p>
                            <p>Nazwizko:</p>
                            <p>E-mail:</p>
                        </div>
                        <div class="col-md-6">
                            <p><?php echo htmlspecialchars($_SESSION["first_name"]); ?></p>
                            <p><?php echo htmlspecialchars($_SESSION["last_name"]); ?></p>
                            <p><?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                        </div>
                        <div class="col-md-3">
                            <button onclick="document.getElementById('edit-profile').style.display='block';document.getElementById('profile-info').style.display='none';" class="btn btn-info m-2">Edytuj profil</button>
                            <button onclick="document.getElementById('change-password').style.display='block';" class="btn btn-warning m-2">Zmień hasło</button>
                        </div>
                    </div>

                    <div id="edit-profile" style="display:none;">
                        <form action="path_to_your_update_script.php" method="post">
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($_SESSION["first_name"]); ?>" required />
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($_SESSION["last_name"]); ?>" required />
                            <input type="text" name="email" value="<?php echo htmlspecialchars($_SESSION["email"]); ?>" required />
                            <input type="submit" value="Zapisz zmiany" class="btn btn-success m-2" />
                            <button type="button" onclick="document.getElementById('edit-profile').style.display='none';document.getElementById('profile-info').style.display='block';" class="btn btn-secondary m-2">Anuluj</button>
                        </form>
                    </div>
                </div>

                <div class="card" id="change-password" style="display:none;">
                    <form action="path_to_password_update_script.php" method="post">
                        <div class="form-group">
                            <label for="current_password">Aktualne hasło</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="new_password">Nowe hasło</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password">Potwierdź nowe hasło</label>
                            <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Zapisz nowe hasło" class="btn btn-success" />
                            <button type="button" onclick="document.getElementById('change-password').style.display='none';" class="btn btn-secondary">Anuluj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>