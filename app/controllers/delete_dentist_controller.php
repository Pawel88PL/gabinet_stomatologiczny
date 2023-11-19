<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany i ma uprawnienia administracyjne
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    header("location: login.php");
    exit;
}

require_once '../../config/database.php';
require_once '../models/dentist.php';

$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

// Sprawdzenie, czy ID dentysty zostało przekazane
if (isset($_GET["dentist_id"]) && !empty(trim($_GET["dentist_id"]))) {
    $dentist_id = trim($_GET["dentist_id"]);

    // Sprawdzenie, czy dentysta jest administratorem
    if ($dentist->isAdministrator($dentist_id)) {
        // Ustawienie komunikatu o błędzie i przekierowanie z powrotem do panelu
        $_SESSION['error_message'] = "Nie można usunąć dentysty z rolą administratora.";
        header("location: ../views/admin_panel.php");
        exit;
    }

    // Usunięcie dentysty
    if ($dentist->delete($dentist_id)) {
        // Przekierowanie do panelu administracyjnego
        error_log("Usunięto dentystę o ID: $dentist_id");
        $_SESSION['success_message'] = "Pomyślnie usnięto dentystę.";
        header("location: ../views/admin_panel.php");
        exit;
    } else {
        // Ustawienie komunikatu o błędzie i przekierowanie z powrotem do panelu
        $_SESSION['error_message'] = "Wystąpił błąd podczas usuwania dentysty.";
        header("location: ../views/admin_panel.php");
        exit;
    }
} else {
    // Jeśli nie przekazano ID dentysty, przekieruj z powrotem do panelu administracyjnego
    error_log("Nie przekazano ID dentysty");
    header("location: ../views/admin_panel.php");
    exit;
}
