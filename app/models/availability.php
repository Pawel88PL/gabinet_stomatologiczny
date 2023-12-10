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
            return true;
        }

        return false;
    }
}
