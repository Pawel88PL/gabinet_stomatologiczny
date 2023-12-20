<?php

class Availability
{
    private $conn;
    private $table_name = "availability";

    public $availability_id;
    public $dentist_id;
    public $start_time;
    public $end_time;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Dodawanie nowej dostępności
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (dentist_id, start_time, end_time) VALUES (:dentist_id, :start_time, :end_time)";

        $stmt = $this->conn->prepare($query);

        // Oczyszczanie danych
        $this->dentist_id = htmlspecialchars(strip_tags($this->dentist_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));

        // Przypisywanie danych do zapytania
        $stmt->bindParam(':dentist_id', $this->dentist_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);

        if ($stmt->execute()) {
            error_log("Nowa dostępność została dodana: Dentysta ID " . $this->dentist_id . ", Czas rozpoczęcia: " . $this->start_time . ", Czas zakończenia: " . $this->end_time);
            return true;
        }

        return false;
    }

    // Aktualizacja istniejącej dostępności
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET start_time = :start_time, end_time = :end_time WHERE availability_id = :availability_id";

        $stmt = $this->conn->prepare($query);

        // Oczyszczanie danych
        $this->availability_id = htmlspecialchars(strip_tags($this->availability_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));

        // Przypisywanie danych do zapytania
        $stmt->bindParam(':availability_id', $this->availability_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);

        if ($stmt->execute()) {
            error_log("Dostępność została zaktualizowana: Dentysta ID " . $this->dentist_id . ", Czas rozpoczęcia: " . $this->start_time . ", Czas zakończenia: " . $this->end_time);
            return true;
        }

        return false;
    }

    public function getAllAvailability($dentist_id)
    {
        // Pobierz bieżącą datę i godzinę
        $currentDateTime = date('Y-m-d H:i:s');
        error_log($currentDateTime);

        $query = "SELECT * FROM " . $this->table_name . " WHERE dentist_id = :dentist_id AND end_time >= :currentDateTime ORDER BY start_time ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dentist_id', $dentist_id);
        $stmt->bindParam(':currentDateTime', $currentDateTime);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFutureAvailability()
    {
        $currentDate = date('Y-m-d H:i:s');

        $query = "SELECT a.availability_id, a.dentist_id, a.start_time, a.end_time, d.first_name, d.last_name 
              FROM " . $this->table_name . " a 
              JOIN dentists d ON a.dentist_id = d.dentist_id 
              WHERE a.start_time > :currentDate 
              ORDER BY a.start_time ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE availability_id = :availability_id";

        $stmt = $this->conn->prepare($query);

        $this->availability_id = htmlspecialchars(strip_tags($this->availability_id));

        $stmt->bindParam(':availability_id', $this->availability_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}
