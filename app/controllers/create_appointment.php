<?php
session_start();

require_once '../../config/database.php';
require_once '../models/appointment.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "Musisz być zalogowany, aby dokonać rezerwacji."]);
        exit;
    }

    $patient_id = $_SESSION['user_id'];
    $dentist_id = $data['dentist_id'] ?? null;
    $appointment_date = $data['appointment_date'] ?? null;

    try {
        $database = new Database();
        $db = $database->getConnection();

        $appointment = new Appointment($db);
        $appointment->patient_id = $patient_id;
        $appointment->dentist_id = $dentist_id;
        $appointment->appointment_date = $appointment_date;
        $appointment->status = 'scheduled';

        if ($appointment->create()) {
            echo json_encode(["status" => "success", "message" => "Wizyta została pomyślnie zarezerwowana!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nie udało się zarezerwować wizyty."]);
        }
    } catch (PDOException $e) {
        error_log('Błąd przy tworzeniu wizyty: ' . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Wystąpił błąd przy rezerwacji wizyty."]);
    }
}
