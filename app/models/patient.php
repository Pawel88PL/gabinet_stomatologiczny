<?php
class Patient
{
    private $db;
    private $table_name = "patients";

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Metoda do logowania pacjenta
    public function login($email, $password)
    {
        $query = "SELECT patient_id, first_name, last_name, email, password FROM " . $this->table_name . " WHERE email = :email";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);

        // Oczyszczenie i bindowanie
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);

        // Wykonanie zapytania
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $patient_id = $row['patient_id'];
            $hashed_password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];

            // Weryfikacja hasła
            if (password_verify($password, $hashed_password)) {
                // Jeśli hasło się zgadza, zainicjuj sesję
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["patient_id"] = $patient_id;
                $_SESSION["email"] = $email;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Tutaj możesz dodać więcej metod dotyczących pacjentów
}
