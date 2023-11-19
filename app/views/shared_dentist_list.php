<?php
// Załączenie pliku klasy Dentist i inicjalizacja obiektu
require_once '../../config/database.php';
require_once '../models/dentist.php';
$database = new Database();
$db = $database->getConnection();
$dentist = new Dentist($db);

// Pobranie listy dentystów
$stmt = $dentist->readAll();

// Rozpoczęcie kontenera responsywnego
echo "<div class='table-responsive'>";
echo "<table class='table table-striped'>";
echo "<thead class='thead-dark'>";
echo "<tr><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Specjalizacja</th><th>Akcje</th></tr>";
echo "</thead>";
echo "<tbody>";

// Iterowanie przez wyniki i wyświetlanie każdego dentysty
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    echo "<tr>";
    echo "<td>{$dentist_id}</td>";
    echo "<td>{$first_name}</td>";
    echo "<td>{$last_name}</td>";
    echo "<td>{$email}</td>";
    echo "<td>{$specialization}</td>";
    echo "<td>";
    echo "<a href='edit_dentist.php?id={$dentist_id}' class='btn btn-primary'>Edytuj</a>";
    echo " <a href='../controllers/delete_dentist_controller.php?dentist_id={$dentist_id}' class='btn btn-danger' onclick='return confirmDeletion()'>Usuń</a>";

    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>"; // Zakończenie kontenera responsywnego

?>

<script>
    function confirmDeletion() {
        return confirm("Czy na pewno chcesz usunąć tego dentystę?");
    }
</script>