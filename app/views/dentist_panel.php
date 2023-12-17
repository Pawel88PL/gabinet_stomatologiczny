<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany i ma rolę dentysty
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

require_once '../../config/database.php';
require_once '../models/dentist.php';
require_once '../models/availability.php';

// Utworzenie obiektu bazy danych i dentysty
$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);
$availability = new Availability($db);

$availabilityData = $availability->getAllAvailability($_SESSION['user_id']);
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

// Przywitanie lekarza
$firstName = htmlspecialchars($_SESSION["first_name"]);
$lastChar = strtolower(substr($firstName, -1)); // Pobiera ostatni znak imienia

// Przykładowa logika do określenia płci na podstawie ostatniej litery imienia
if (in_array($lastChar, ['a', 'e', 'i', 'o', 'u', 'y'])) {
    // Prawdopodobnie kobieta
    $greeting = "Witam Pani doktor!";
} else {
    // Prawdopodobnie mężczyzna
    $greeting = "Witam Pana doktora!";
}
?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel dentysty</title>
    <link rel="stylesheet" href="../../public/css/patient_panel.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php include 'shared_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center" id="profile-section">
                    <h2><?php echo $greeting; ?> <strong>Dr. <?php echo $firstName; ?></strong>, oto twój panel!</h2>
                    <p>Twój numer identyfikacyjny w naszym gabinecie stomatologicznym to: <strong><?php echo htmlspecialchars($_SESSION["user_id"]); ?></strong></p>
                </div>

                <div class="card">
                    <?php if (!empty($_SESSION['start_time_err'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['start_time_err']; ?></div>
                        <?php unset($_SESSION['start_time_err']); ?>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['end_time_err'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['end_time_err']; ?></div>
                        <?php unset($_SESSION['end_time_err']); ?>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success_message'])) : ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <div class="availability-section">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Twoja aktualna dostępność:</h2>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="../controllers/export_availability_controller.php" class="btn btn-secondary w-100">Ekspor do CSV</a>
                                    </div>
                                    <div class="col-6">
                                        <button onclick="toggleSection('add-availability-section', true)" class="btn btn-primary w-100">Dodaj nową</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Czas rozpoczęcia</th>
                                        <th>Czas zakończenia</th>
                                        <th>Usuń</th>
                                        <th>Edytuj</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($availability as $slot) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($slot['start_time']); ?></td>
                                            <td><?php echo htmlspecialchars($slot['end_time']); ?></td>
                                            <td>
                                                <a href="#" data-id="<?php echo $slot['availability_id']; ?>" class="btn btn-sm btn-danger delete-availability-btn"><i class="bi bi-trash"></i></a>
                                            </td>
                                            <td>
                                                <a href="#" data-id="<?php echo $slot['availability_id']; ?>" class="btn btn-sm btn-primary edit-availability-btn"><i class="bi bi-pen"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>

                <div class="card" id="edit-availability-section" style="display: none; padding-top:6rem">
                    <h3>Edytuj Dostępność</h3>
                    <form id="edit-availability-form" action="../controllers/dentist_availability_controller.php" method="post">
                        <input type="hidden" id="edit-availability-id" name="availability_id">
                        <div class="mb-3">
                            <label for="edit-start-time">Czas rozpoczęcia:</label>
                            <input type="datetime-local" id="edit-start-time" name="start_time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit-end-time">Czas zakończenia:</label>
                            <input type="datetime-local" id="edit-end-time" name="end_time" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Zapisz Zmiany</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleSection('edit-availability-section', false)">Anuluj</button>
                    </form>
                </div>

                <div class="card" id="add-availability-section" style="display: none; padding-top:6rem">
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
                        <button type="submit" class="btn btn-primary">Dodaj dostępność</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleSection('add-availability-section', false)">Anuluj</button>
                    </form>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('.delete-availability-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const availabilityId = this.getAttribute('data-id');

                Swal.fire({
                    title: "Jesteś pewien?",
                    text: "Tej operacji nie można cofnąć.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Tak, usuń dostępność",
                    cancelButtonText: "Anuluj"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../controllers/delete_availability_controller.php?availability_id=' + availabilityId;
                    }
                });
            });
        });

        document.querySelectorAll('.edit-availability-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const availabilityId = this.getAttribute('data-id');

                // Załaduj dane dostępności
                var availabilityData = <?php echo json_encode($availabilityData); ?>;

                // Znajdź dane dla wybranego ID
                var slotData = availabilityData.find(slot => slot.availability_id == availabilityId);

                if (slotData) {
                    document.getElementById('edit-availability-id').value = slotData.availability_id;
                    document.getElementById('edit-start-time').value = slotData.start_time;
                    document.getElementById('edit-end-time').value = slotData.end_time;

                    toggleSection('edit-availability-section', true);
                }
            });
        });

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