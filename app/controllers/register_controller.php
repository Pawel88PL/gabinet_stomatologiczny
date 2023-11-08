<?php
// register_controller.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/database.php'; // Ustaw prawidłową ścieżkę do pliku z klasą Database.

$database = new Database();
$db = $database->getConnection();

// Define variables and initialize with empty values
$first_name = $last_name = $email = $password = "";
$first_name_err = $last_name_err = $email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter a first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter a last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO patients (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";

        if ($stmt = $db->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":first_name", $param_first_name);
            $stmt->bindParam(":last_name", $param_last_name);
            $stmt->bindParam(":email", $param_email);
            $stmt->bindParam(":password", $param_password);

            // Set parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Tuż po wywołaniu execute()
            if ($stmt->execute()) {
                // Redirect to login page
                header("Location: login.php");
                exit(); // Zatrzymaj wykonanie skryptu
            } else {
                echo "Execute failed: " . $stmt->errorInfo()[2];
            }


            // Close statement
            $stmt = null;
        }
    }

    // Close connection
    $db = null;

    // Na końcu register_controller.php, przed załadowaniem widoku

    // Przekazanie zmiennych do widoku
    $viewData = [
        'first_name' => $first_name,
        'first_name_err' => $first_name_err,
        'last_name' => $last_name,
        'last_name_err' => $last_name_err,
        'email' => $email,
        'email_err' => $email_err,
        'password_err' => $password_err,
        // ... inne potrzebne zmienne
    ];

    // Załaduj widok i przekaż dane
    require_once '../Views/register_view.php';
}
