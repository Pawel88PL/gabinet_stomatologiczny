<?php

class Dentist
{
    private $db;
    private $table_name = "dentists";


    // Atrybuty klasy odpowiadające kolumnom w tabeli 'dentists'
    public $dentist_id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $specialization;
    public $role;


    // Konstruktor z połączeniem do bazy danych
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Metody CRUD

    // Create (dodawanie nowego dentysty)
    public function create()
    {
        // Zapytanie SQL do wstawienia nowego rekordu
        $query = "INSERT INTO " . $this->table_name . "
              SET first_name=:first_name, last_name=:last_name, email=:email, password=:password, specialization=:specialization";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);

        // Oczyszczenie i bindowanie danych
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        // Hashowanie hasła
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->specialization = htmlspecialchars(strip_tags($this->specialization));

        // Bindowanie zmiennych
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":specialization", $this->specialization);

        // Wykonanie zapytania
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Logowanie dentysty
    public function login($email, $password)
    {
        $query = "SELECT dentist_id, first_name, last_name, email, password, role FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $dentist_id = $row['dentist_id'];
            $hashed_password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $role = $row['role'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $dentist_id;
                $_SESSION["email"] = $email;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["role"] = $role;

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    // Read (odczytywanie danych dentysty)
    public function read()
    {
        // Tutaj należy dodać logikę odczytu danych dentysty
    }

    public function readAll()
    {
        $query = "SELECT dentist_id, first_name, last_name, email, role, specialization FROM " . $this->table_name . " ORDER BY last_name, first_name";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);

        // Wykonanie zapytania
        $stmt->execute();

        return $stmt;
    }

    // Update (aktualizacja danych dentysty)
    public function update()
    {
        // Tutaj należy dodać logikę aktualizacji danych dentysty
    }

    // Delete (usuwanie dentysty)
    public function delete($dentistId)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE dentist_id = :dentist_id";

        // Przygotowanie zapytania
        $stmt = $this->db->prepare($query);

        // Oczyszczenie i bindowanie danych
        $this->dentist_id = htmlspecialchars(strip_tags($dentistId));
        $stmt->bindParam(':dentist_id',
            $this->dentist_id
        );

        // Wykonanie zapytania
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function isAdministrator($dentist_id)
    {
        $query = "SELECT role FROM " . $this->table_name . " WHERE dentist_id = :dentist_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dentist_id', $dentist_id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['role'] === 'administrator') {
                return true;
            }
        }
        return false;
    }

    public function isEmailExists($email)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            return true; // Email już istnieje
        } else {
            return false; // Email nie istnieje
        }
    }


    // Dodatkowe metody, np. do obsługi specjalizacji

    // ...
}

?>