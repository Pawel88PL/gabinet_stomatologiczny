<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'patient') {
    header("location: patient_login.php");
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
    <title>Panel pacjenta</title>
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
                    <h2>Cześć <strong><?php echo htmlspecialchars($_SESSION["first_name"]); ?></strong>, oto twój panel pacjenta!</h2>
                    <div class="row justify-content-center">
                        <p class="col-md-8">
                            Możesz przeglądać w nim swoje wizyty, historie odbytych wizyt jak również możesz zmienić swoje dane osobowe i hasło.
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
                        <!-- Komunikat o błędzie edycji danych -->
                        <?php if (!empty($update_err)) : ?>
                            <div class="alert alert-danger">
                                <?php echo $update_err; ?></div>
                        <?php endif; ?>

                        <!-- Komunikat o błędzie zmiany hasła -->
                        <?php if (isset($_SESSION['password_err'])) : ?>
                            <div class="alert alert-danger">
                                <?php
                                echo $_SESSION['password_err'];
                                unset($_SESSION['password_err']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Komunikat o sukcesie -->
                        <?php if (isset($_SESSION['update_success'])) : ?>
                            <div class="alert alert-success">
                                <?php
                                echo $_SESSION['update_success'];
                                unset($_SESSION['update_success']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <h2 class="card-title">Twoje dane:</h2>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <p><strong>Imię:</strong> <?php echo htmlspecialchars($_SESSION["first_name"]); ?></p>
                                <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($_SESSION["last_name"]); ?></p>
                                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                            </div>
                            <div class="col-md-8 text-md-end">
                                <button onclick="toggleSection('edit-profile', true);" class="btn btn-info m-1">Edytuj profil</button>
                                <button onclick="toggleSection('change-password', true);" class="btn btn-warning m-1">Zmień hasło</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="edit-profile" style="display:none;">
                    <form action="../controllers/patient_update_controller.php" method="post" class="row g-3">
                        <div class="col-lg-4">
                            <label for="first_name" class="form-label">Imię</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION["first_name"]); ?>" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="last_name" class="form-label">Nazwisko</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION["last_name"]); ?>" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION["email"]); ?>" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success m-1">Zapisz zmiany</button>
                            <button type="button" onclick="toggleSection('edit-profile', false);" class="btn btn-secondary m-1">Anuluj</button>
                        </div>
                    </form>
                </div>

                <div class="card" id="change-password" style="display:none;">
                    <form action="../controllers/patient_change_password_controller.php" method="post">
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
                            <button type="button" onclick="toggleSection('change-password', false);" class="btn btn-secondary m-2">Anuluj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSection(sectionId, show) {
            var section = document.getElementById(sectionId);
            if (section) {
                section.style.display = show ? 'block' : 'none';

                if (show) {
                    section.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>