<?php
require_once '../../config/database.php';
require_once '../models/availability.php'; // Zaktualizuj ścieżkę

$database = new Database();
$db = $database->getConnection();

$availability = new Availability($db);
$data = $availability->getFutureAvailability();

echo json_encode($data);
