<?php
class Patient
{
    private $db;
    private $table_name = "patients";

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login($email, $password)
    {
        $query = "SELECT patient_id, first_name, last_name, email, password FROM " . $this->table_name . " WHERE email = :email";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);

        // Oczyszczenie i bindowanie
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $patient_id = $row['patient_id'];
            $hashed_password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];

            if (password_verify($password, $hashed_password)) {
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

    public function isEmailUsedByAnotherPatient($patientId, $email)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " 
                  WHERE email = :email AND patient_id != :patient_id";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':patient_id', $patientId);

        // Wykonanie zapytania
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }


    public function updateProfile($patientId, $firstName, $lastName, $email)
    {
        if ($this->isEmailUsedByAnotherPatient($patientId, $email)) {
            return false; // Email jest już używany
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET first_name = :first_name, 
                      last_name = :last_name, 
                      email = :email 
                  WHERE patient_id = :patient_id";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);

        // Oczyszczenie i bindowanie
        $firstName = htmlspecialchars(strip_tags($firstName));
        $lastName = htmlspecialchars(strip_tags($lastName));
        $email = htmlspecialchars(strip_tags($email));
        $patientId = htmlspecialchars(strip_tags($patientId));

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':patient_id', $patientId);

        // Wykonanie zapytania
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword($patientId, $currentPassword, $newPassword)
    {
        // Pobranie aktualnego hasła użytkownika
        $query = "SELECT password FROM " . $this->table_name . " WHERE patient_id = :patient_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Sprawdzenie, czy aktualne hasło jest prawidłowe
            if (password_verify($currentPassword, $hashed_password)) {
                // Aktualizacja hasła
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE " . $this->table_name . " SET password = :new_password WHERE patient_id = :patient_id";
                $updateStmt = $this->db->prepare($updateQuery);

                $updateStmt->bindParam(':new_password', $newHashedPassword);
                $updateStmt->bindParam(':patient_id', $patientId);

                if ($updateStmt->execute()) {
                    return true; // Hasło zostało pomyślnie zaktualizowane
                }
                if (!$updateStmt->execute()) {
                    error_log("Błąd aktualizacji hasła: " . implode(";", $updateStmt->errorInfo()));
                }
            }
        }
        return false; // Aktualne hasło jest nieprawidłowe lub wystąpił inny błąd
    }
}
