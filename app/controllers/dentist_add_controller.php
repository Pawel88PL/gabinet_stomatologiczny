<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    header("location: login.php");
    exit;
}

require_once '../../config/database.php';
require_once '../models/dentist.php';

$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

$first_name = $last_name = $email = $password = $specialization = "";
$first_name_err = $last_name_err = $email_err = $password_err = $specialization_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $specialization = trim($_POST["specialization"]);

    // Walidacja adresu e-mail
    if ($dentist->isEmailExists($email)) {
        $email_err = "Adres email jest już używany.";
    }

    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($specialization_err)) {
        $dentist->first_name = $first_name;
        $dentist->last_name = $last_name;
        $dentist->email = $email;
        $dentist->password = $password;
        $dentist->specialization = $specialization;

        if ($dentist->create()) {
            $_SESSION['success_message'] = "Pomyślnie dodano nowego dentystę!";
            header("location: ../views/admin_panel.php");
            exit;
        } else {
            echo "Wystąpił błąd podczas dodawania dentysty.";
        }
    } else {
        $_SESSION['error_message'] = "Nie udało się dodać dentysty. " . $email_err;
        header("location: ../views/admin_panel.php");
        exit;
    }
}
