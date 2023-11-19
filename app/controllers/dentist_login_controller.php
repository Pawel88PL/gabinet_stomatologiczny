<?php
error_log("Formularz logowania został wysłany.");
error_log("Email: " . $_POST['email']);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Załączanie pliku konfiguracyjnego i klasy użytkownika
require_once '../../config/database.php';
require_once '../models/dentist.php';

$database = new Database();
$db = $database->getConnection();

$email = $password = "";
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["email"]))) {
        $email_err = "Proszę podać email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Proszę podać hasło.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Po próbie logowania
    if (empty($email_err) && empty($password_err)) {
        $user = new Dentist($db);
        if ($user->login($email, $password)) {
            // Sprawdzenie roli użytkownika i przekierowanie do odpowiedniego panelu
            if ($_SESSION["role"] == 'dentist') {
                header("location: ../views/dentist_panel.php");
                exit;
            } elseif ($_SESSION["role"] == 'administrator') {
                header("location: ../views/admin_panel.php");
                exit;
            } else {
                // Można dodać obsługę innych ról lub domyślne przekierowanie
            }
        } else {
            // Przekazanie błędu do sesji i przekierowanie z powrotem do formularza logowania
            $_SESSION['login_err'] = "Niepoprawny email lub hasło.";
            header("location: ../views/dentist_login.php");
            exit;
        }
    } else {
        // Przekazanie błędów walidacji do sesji
        $_SESSION['email_err'] = $email_err;
        $_SESSION['password_err'] = $password_err;
        header("location: ../views/dentist_login.php");
        exit;
    }


    // Zamykanie połączenia z bazą danych
    unset($db);
}
