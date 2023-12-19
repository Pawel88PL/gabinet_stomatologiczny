<?php
require_once '../../config/database.php';
require_once '../models/availability.php';
require_once '../models/appointment.php';

$database = new Database();
$db = $database->getConnection();

$availability = new Availability($db);
$appointments = new Appointment($db);

// Pobierz przyszłą dostępność
$availableSlots = $availability->getFutureAvailability();

// Pobierz przyszłe rezerwacje
$bookedAppointments = $appointments->getFutureAppointments();

// Podziel dostępność na mniejsze sesje i filtruj
$filteredAvailability = [];
foreach ($availableSlots as $slot) {
    $startTime = new DateTime($slot['start_time']);
    $endTime = new DateTime($slot['end_time']);

    while ($startTime < $endTime) {
        $sessionEnd = clone $startTime;
        $sessionEnd->add(new DateInterval('PT55M')); // 50 minut czas trwania wizyty

        $nextSessionStart = clone $sessionEnd;
        $nextSessionStart->add(new DateInterval('PT5M')); // 5 minut przerwy

        $isBooked = false;
        foreach ($bookedAppointments as $appointment) {
            $appointmentTime = new DateTime($appointment['appointment_date']);

            if (
                $slot['dentist_id'] == $appointment['dentist_id'] &&
                $appointmentTime >= $startTime && $appointmentTime < $sessionEnd &&
                $appointment['status'] === 'scheduled'
            ) {
                $isBooked = true;
                break;
            }
        }

        if (!$isBooked) {
            $filteredAvailability[] = [
                'dentist_id' => $slot['dentist_id'],
                'start_time' => $startTime->format('Y-m-d H:i:s'),
                'end_time' => $sessionEnd->format('Y-m-d H:i:s'),
                'first_name' => $slot['first_name'],
                'last_name' => $slot['last_name']
            ];
        }

        $startTime = $nextSessionStart; // Przejdź do następnej sesji, uwzględniając przerwę
    }
}

echo json_encode($filteredAvailability);
