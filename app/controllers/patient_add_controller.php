<?php
// register_controller.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/database.php'; // Dołączanie pliku konfiguracyjnego bazy danych

$database = new Database();
$db = $database->getConnection();

// Definiowanie zmiennych i inicjalizacja pustymi wartościami
$first_name = $last_name = $email = $password = "";
$first_name_err = $last_name_err = $email_err = $password_err = "";

// Przetwarzanie danych formularza po jego wysłaniu
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Walidacja imienia
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Proszę podać imię.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Walidacja nazwiska
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Proszę podać nazwisko.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Walidacja adresu email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Proszę podać adres email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Walidacja hasła
    if (empty(trim($_POST["password"]))) {
        $password_err = "Proszę wpisać hasło.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Sprawdzenie błędów przed dodaniem do bazy danych
    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err)) {

        // Przygotowanie zapytania SQL do wstawienia danych
        $sql = "INSERT INTO patients (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";

        if ($stmt = $db->prepare($sql)) {
            // Przypisywanie zmiennych do przygotowanego zapytania jako parametrów
            $stmt->bindParam(":first_name", $param_first_name);
            $stmt->bindParam(":last_name", $param_last_name);
            $stmt->bindParam(":email", $param_email);
            $stmt->bindParam(":password", $param_password);

            // Ustawienie parametrów
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hashowanie hasła

            // Wykonanie zapytania
            if ($stmt->execute()) {
                // Przekierowanie do strony logowania
                header("Location: ../views/patient_login.php");
                exit(); // Zakończenie skryptu
            } else {
                echo "Execute failed: " . $stmt->errorInfo()[2];
            }

            // Zamknięcie zapytania
            $stmt = null;
        }
    }

    // Zamknięcie połączenia
    $db = null;

    // Przekazywanie danych do widoku
    $viewData = [
        'first_name' => $first_name,
        'first_name_err' => $first_name_err,
        'last_name' => $last_name,
        'last_name_err' => $last_name_err,
        'email' => $email,
        'email_err' => $email_err,
        'password_err' => $password_err,
    ];

    // Załadowanie widoku i przekazanie danych
    require_once '../views/patient_register.php';
}
