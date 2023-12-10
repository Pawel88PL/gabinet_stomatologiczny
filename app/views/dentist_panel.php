<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany i ma rolę dentysty
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

require_once '../../config/database.php';
require_once '../models/dentist.php';

// Utworzenie obiektu bazy danych i dentysty
$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

// Próba pobrania danych dentysty
$dentist_data = $dentist->getDentistById($_SESSION["user_id"]);

if ($dentist_data === false) {
    // Obsługa błędu, jeśli nie znaleziono danych dentysty
    echo "Błąd: Nie można znaleźć danych dentysty.";
    exit;
}

// Pobieranie danych dostępności
$query = "SELECT * FROM availability WHERE dentist_id = :dentist_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':dentist_id', $_SESSION["user_id"]);
$stmt->execute();
$availability = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    <p>Twój numer identyfikacyjny w bazie danych to: <strong><?php echo htmlspecialchars($_SESSION["user_id"]); ?></strong></p>
                </div>

                <div class="card">
                    <?php if (!empty($_SESSION['start_time_err'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['start_time_err']; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['end_time_err'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['end_time_err']; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success_message'])) : ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
                    <?php endif; ?>

                    <div class="availability-section">
                        <h2>Twoja aktualna dostępność:</h2>
                        <?php foreach ($availability as $slot) : ?>
                            <p><?php echo htmlspecialchars($slot['start_time']); ?> do <?php echo htmlspecialchars($slot['end_time']); ?></p>
                        <?php endforeach; ?>

                        <hr>

                        <h4>Dodaj kolejną dostępność:</h4>
                        <form action="../controllers/dentist_availability_controller.php" method="post">
                            <input type="hidden" name="dentist_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="mb-3">
                                <label for="start_time">Czas rozpoczęcia:</label>
                                <input type="datetime-local" id="start_time" name="start_time" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="end_time">Czas zakończenia:</label>
                                <input type="datetime-local" id="end_time" name="end_time" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Aktualizuj dostępność</button>
                        </form>
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