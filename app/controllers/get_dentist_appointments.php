<?php
session_start();

require_once '../../config/database.php';
require_once '../models/appointment.php';

// Sprawdź, czy użytkownik jest zalogowany i ma odpowiednią rolę (np. dentysta)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'dentist') {
    // Jeśli użytkownik nie jest zalogowany lub nie ma odpowiedniej roli, zwróć błąd
    echo json_encode(["error" => "Nie masz uprawnień"]);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$appointment = new Appointment($db);

// Pobierz wizyty dla zalogowanego dentysty
$dentistAppointments = $appointment->getAppointmentsByDentist($_SESSION['user_id']);

echo json_encode($dentistAppointments);
