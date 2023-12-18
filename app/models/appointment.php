<?php
class Appointment
{
    private $conn;
    private $table_name = "appointments";

    public $appointment_id;
    public $patient_id;
    public $dentist_id;
    public $appointment_date;
    public $status;
    public $notes;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (patient_id, dentist_id, appointment_date, status) VALUES (:patient_id, :dentist_id, :appointment_date, :status)";

        $stmt = $this->conn->prepare($query);

        // Oczyszczenie i przypisanie wartości
        $this->patient_id = htmlspecialchars(strip_tags($this->patient_id));
        $this->dentist_id = htmlspecialchars(strip_tags($this->dentist_id));
        $this->appointment_date = htmlspecialchars(strip_tags($this->appointment_date));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Przypisanie parametrów
        $stmt->bindParam(":patient_id", $this->patient_id);
        $stmt->bindParam(":dentist_id", $this->dentist_id);
        $stmt->bindParam(":appointment_date", $this->appointment_date);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            error_log("Umówiono wizytę: Pacjent ID " . $this->patient_id . ", Dentysta ID " . $this->dentist_id . ", Data: " . $this->appointment_date);
            return true;
        }

        return false;
    }

    public function getFutureAppointments()
    {
        $currentDate = date('Y-m-d H:i:s');

        $query = "SELECT * FROM " . $this->table_name . " WHERE appointment_date >= :currentDate ORDER BY appointment_date ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":currentDate", $currentDate);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
