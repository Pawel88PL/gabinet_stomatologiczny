<?php
session_start();

require_once '../../config/database.php';
require_once '../models/appointment.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    $database = new Database();
    $db = $database->getConnection();

    $appointment = new Appointment($db);
    $appointment->appointment_id = $appointment_id;

    if ($appointment->cancelByDentist()) {
        echo json_encode(["message" => "Zaplanowana wizyta została odwołana"]);
    } else {
        echo json_encode(["message" => "Wystąpił błąd. Nie udało się odwołać wizyty"]);
    }
}
