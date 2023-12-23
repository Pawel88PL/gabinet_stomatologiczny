<?php
session_start();

require_once '../../config/database.php';
require_once '../models/appointment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
        echo json_encode(["error" => "Nieautoryzowany dostęp"]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $appointments = new Appointment($db);

    $updatedRows = $appointments->updateStatusToCompleted();

    echo json_encode(['success' => true, 'message' => "Zaktualizowano status dla $updatedRows wizyt."]);
} else {
    echo json_encode(['error' => 'Nieobsługiwana metoda żądania']);
}
