<?php

// Wyświetlenie informacji o błędach
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Rozpoczęcie sesji
session_start();

// Załączanie pliku konfiguracyjnego i klasy pacjenta
require_once '../../config/database.php';
require_once '../models/patient.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$patient = new Patient($db);

$firstName = $lastName = $email = "";
$update_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Walidacja danych...
    if (empty(trim($_POST["first_name"]))) {
        $update_err = "Proszę podać imię.";
    } else {
        $firstName = trim($_POST["first_name"]);
    }

    if (empty(trim($_POST["last_name"]))) {
        $update_err .= "\nProszę podać nazwisko.";
    } else {
        $lastName = trim($_POST["last_name"]);
    }

    if (empty(trim($_POST["email"]))) {
        $update_err .= "\nProszę podać email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($update_err)) {
        if ($patient->isEmailUsedByAnotherPatient($_SESSION['user_id'], $email)) {
            $_SESSION['update_err'] = "Podany adres email jest używany.";
            header("location: ../views/patient_panel.php");
            exit;
        }

        if ($patient->updateProfile($_SESSION['user_id'], $firstName, $lastName, $email)) {
            // Aktualizacja danych w sesji
            $_SESSION["first_name"] = $firstName;
            $_SESSION["last_name"] = $lastName;
            $_SESSION["email"] = $email;

            // Ustawienie komunikatu o sukcesie
            $_SESSION['update_success'] = "Dane zostały pomyślnie zaktualizowane.";
            
            // Przekierowanie do panelu pacjenta
            header("location: ../views/patient_panel.php");
            exit;
        } else {
            $_SESSION['update_err'] = "Wystąpił błąd podczas aktualizacji danych.";
            header("location: ../views/patient_panel.php");
            exit;
        }
    } else {
        // Przekazanie błędów walidacji do sesji
        $_SESSION['update_err'] = $update_err;
        header("location: ../views/patient_panel.php");
        exit;
    }
}

// Zamykanie połączenia z bazą danych
unset($db);
