<?php
session_start();

require_once '../../config/database.php';
require_once '../models/availability.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$availability = new Availability($db);

// Pobierz dane do eksportu
$data = $availability->getAllAvailability($_SESSION['user_id']);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="dostepnosc.csv"');

$output = fopen('php://output', 'w');

// Nagłówki kolumn
fputcsv($output, array('ID Dostępności', 'Dentysta ID', 'Czas Rozpoczęcia', 'Czas Zakończenia'));

// Wprowadzenie danych do CSV
foreach ($data as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
