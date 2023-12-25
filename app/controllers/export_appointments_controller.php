<?php
session_start();

require_once '../../config/database.php';
require_once '../models/appointment.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$appointments = new Appointment($db);

// Pobierz dane do eksportu
$data = $appointments->getAppointmentsByDentist($_SESSION['user_id']);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="wizyty.csv"');

$output = fopen('php://output', 'w');

// Nagłówki kolumn
fputcsv($output, array('ID wizyty', 'Data i czas', 'Status', 'Imię', 'Nazwisko'));

// Wprowadzenie danych do CSV
foreach ($data as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
