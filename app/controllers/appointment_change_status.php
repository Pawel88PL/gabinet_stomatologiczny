<?php
session_start();

require_once '../../config/database.php';
require_once '../models/appointment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
        echo json_encode(["error" => "Nieautoryzowany dostęp"]);
        exit;
    }

    $appointmentId = $_POST['appointment_id'];
    $newStatus = $_POST['new_status']; // 'no_show' dla "Pacjent nie stawił się"

    $database = new Database();
    $db = $database->getConnection();

    $appointment = new Appointment($db);
    $result = $appointment->changeStatus($appointmentId, $newStatus);

    if ($result) {
        echo json_encode(['success' => true, 'message' => "Status wizyty został zmieniony."]);
    } else {
        echo json_encode(['error' => 'Nie udało się zmienić statusu wizyty']);
    }
} else {
    echo json_encode(['error' => 'Nieobsługiwana metoda żądania']);
}
