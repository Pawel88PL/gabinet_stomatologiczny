<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

require_once '../../config/database.php';
require_once '../models/availability.php';

$database = new Database();
$db = $database->getConnection();
$availability = new Availability($db);

$start_time = $end_time = "";
$start_time_err = $end_time_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dentist_id = $_SESSION['user_id'];
    $start_time = trim($_POST["start_time"]);
    $end_time = trim($_POST["end_time"]);

    // Walidacja czasów
    if (empty($start_time)) {
        $start_time_err = "Proszę podać czas rozpoczęcia.";
    }

    if (empty($end_time)) {
        $end_time_err = "Proszę podać czas zakończenia.";
    }

    if (!empty($start_time) && !empty($end_time) && strtotime($start_time) >= strtotime($end_time)) {
        $end_time_err = "Czas zakończenia musi być późniejszy niż czas rozpoczęcia.";
    }

    if (empty($start_time_err) && empty($end_time_err)) {
        $availability->dentist_id = $dentist_id;
        $availability->start_time = $start_time;
        $availability->end_time = $end_time;

        if (isset($_POST['availability_id']) && !empty($_POST['availability_id'])) {
            $availability->availability_id = $_POST['availability_id'];
            $success = $availability->update();
        } else {
            $success = $availability->create();
        }

        if ($success) {
            $_SESSION['success_message'] = "Dostępność została pomyślnie zaktualizowana!";
            header("location: ../views/dentist_panel.php");
            exit;
        } else {
            echo "Wystąpił błąd podczas aktualizacji dostępności.";
        }
    } else {
        $_SESSION['start_time_err'] = $start_time_err;
        $_SESSION['end_time_err'] = $end_time_err;
        header("location: ../views/dentist_panel.php");
        exit;
    }
}

unset($db);
