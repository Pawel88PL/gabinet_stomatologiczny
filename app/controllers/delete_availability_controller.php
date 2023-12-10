<?php
// delete_availability_controller.php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

if (isset($_GET['availability_id'])) {
    require_once '../../config/database.php';
    require_once '../models/availability.php';

    $database = new Database();
    $db = $database->getConnection();
    $availability = new Availability($db);

    $availability->availability_id = $_GET['availability_id'];

    if ($availability->delete()) {
        $_SESSION['success_message'] = "Dostępność została usunięta.";
    } else {
        $_SESSION['error_message'] = "Nie udało się usunąć dostępności.";
    }
}

header("location: ../views/dentist_panel.php");
exit;
