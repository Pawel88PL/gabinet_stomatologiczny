document.addEventListener('DOMContentLoaded', async function () {
    await updateAppointmentsStatus();
    loadAppointments(undefined, true);
});

function cancelAppointment(appointmentId) {
    Swal.fire({
        title: 'Czy na pewno chcesz odwołać wizytę?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak, odwołaj',
        cancelButtonText: 'Nie'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/gabinet/app/controllers/dentist_cancel_appointment.php',
                type: 'POST',
                data: { appointment_id: appointmentId },
                success: function (response) {
                    const data = JSON.parse(response);
                    Swal.fire(
                        'Odwołano!',
                        data.message,
                        'success'
                    );
                    loadAppointments();
                },
                error: function (error) {
                    Swal.fire(
                        'Błąd!',
                        'Nie udało się odwołać wizyty.',
                        'error'
                    );
                }
            });
        }
    });
}

function changeAppointmentStatus(appointmentId, newStatus) {
    Swal.fire({
        title: 'Zmień status wizyty',
        text: `Potwierdź, że pacjent nie stawił się na wizytę`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak, pacjenta nie było',
        cancelButtonText: 'Nie'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/gabinet/app/controllers/appointment_change_status.php',
                type: 'POST',
                data: { appointment_id: appointmentId, new_status: newStatus },
                success: function (response) {
                    const data = JSON.parse(response);
                    Swal.fire(
                        'Zmieniono!',
                        data.message,
                        'success'
                    );
                    loadAppointments();
                },
                error: function (error) {
                    Swal.fire(
                        'Błąd!',
                        'Nie udało się zmienić statusu wizyty.',
                        'error'
                    );
                }
            });
        }
    });
}


var globalAppointments = [];

function loadAppointments(filterStatus = 'scheduled', isInitialLoad = false) {
    $.ajax({
        url: '/gabinet/app/controllers/get_dentist_appointments.php',
        type: 'GET',
        success: function (response) {
            globalAppointments = JSON.parse(response);
            var statusToFilter = isInitialLoad ? 'scheduled' : filterStatus;
            renderTable(globalAppointments, statusToFilter);
        },
        error: function (error) {
            console.log('Błąd podczas ładowania wizyt', error);
        }
    });
}

function sortAppointments(sortKey) {
    var sortedAppointments = [...globalAppointments];
    sortedAppointments.sort(function (a, b) {
        if (sortKey === 'date') {
            return new Date(a.appointment_date) - new Date(b.appointment_date);
        } else if (sortKey === 'patient') {
            return a.first_name.localeCompare(b.first_name) || a.last_name.localeCompare(b.last_name);
        }
    });
    renderTable(sortedAppointments);
}

function renderTable(appointments, filterStatus = 'scheduled') {
    var html = '';
    appointments.forEach(function (appointment) {
        // Jeśli filterStatus jest pusty lub równy statusowi wizyty, dodaj wizytę do tabeli
        if (filterStatus === '' || appointment.status === filterStatus) {
            html += '<tr id="appointment-row-' + appointment.appointment_id + '">';
            html += '<td>' + appointment.appointment_date + '</td>';
            html += '<td>' + appointment.first_name + ' ' + appointment.last_name + '</td>';
            html += '<td>' + formatAppointmentStatus(appointment.status) + '</td>';

            if (appointment.status === 'scheduled') {
                html += '<td><button class="btn btn-danger" onclick="cancelAppointment(' + appointment.appointment_id + ')">Odwołaj</button></td>';
            } else if (appointment.status === 'completed') {
                html += '<td><button class="btn btn-warning" onclick="changeAppointmentStatus(' + appointment.appointment_id + ', \'no_show\')">Pacjent nie stawił się</button></td>';
            } else {
                html += '<td></td>'; // Puste pole dla pozostałych statusów
            }

            html += '</tr>';
        }
    });
    $('#appointments-table tbody').html(html);
}


// Funkcja pomocnicza do formatowania statusu wizyty
function formatAppointmentStatus(status) {
    switch (status) {
        case 'scheduled':
            return 'Zaplanowana';
        case 'completed':
            return 'Wykonana';
        case 'no_show':
            return 'Pacjent nie stawił się';
        case 'cancelled_by_patient':
            return 'Odwołana przez pacjenta';
        case 'cancelled_by_dentist':
            return 'Odwołana przez dentystę';
        default:
            return 'Inny status';
    }
}

async function updateAppointmentsStatus() {
    try {
        const response = await fetch('/gabinet/app/controllers/update_appointments_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            console.log(data.message);
        } else {
            console.error(data.error || 'Nieznany błąd');
        }
    } catch (error) {
        console.error('Wystąpił błąd podczas komunikacji z serwerem:', error);
    }
}
