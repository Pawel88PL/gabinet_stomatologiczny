
var calendar;

function toggleSection(sectionId, show) {
    var section = document.getElementById(sectionId);
    if (section) {
        section.style.display = show ? 'block' : 'none';

        if (show) {
            setTimeout(() => {
                initializeCalendar();
            }, 500);
            section.scrollIntoView({
                behavior: 'smooth'
            });
        }
    }
}


function initializeCalendar() {
    var calendarEl = document.getElementById('calendar');
    var patientId = calendarEl.getAttribute('data-patient-id');
    if (!calendar) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function (fetchInfo, successCallback, failureCallback) {
                fetch('/gabinet/app/controllers/get_availability.php')
                    .then(response => response.json())
                    .then(data => {
                        const events = data.map(item => {
                            return {
                                title: item.first_name + ' ' + item.last_name,
                                start: item.start_time,
                                end: item.end_time,
                                color: 'green',
                                extendedProps: {
                                    dentist_id: item.dentist_id
                                }
                            };
                        });
                        successCallback(events);
                    })
                    .catch(error => failureCallback(error));
            },
            eventClick: function (info) {
                const prettyDate = new Date(info.event.start).toLocaleString('pl-PL', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                Swal.fire({
                    title: 'Potwierdź rezerwację',
                    text: 'Czy chcesz zarezerwować wizytę u ' + info.event.title + ' dnia ' + prettyDate + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tak, zarezerwuj',
                    cancelButtonText: 'Anuluj'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Logika rezerwacji
                        const appointmentData = {
                            dentist_id: info.event.extendedProps.dentist_id,
                            appointment_date: info.event.startStr,
                            patient_id: patientId
                        };

                        fetch('/gabinet/app/controllers/create_appointment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(appointmentData)
                        }).then(response => response.json()).then(data => {
                            // Tu obsługa odpowiedzi od serwera
                            Swal.fire({
                                title: 'Rezerwacja potwierdzona',
                                text: 'Twoja wizyta została zarezerwowana.',
                                icon: 'success'
                            }).then(() => {
                                refreshCalendar();
                                loadAppointments();
                            });
                        }).catch(error => {
                            // Tu obsługa błędów
                            Swal.fire({
                                title: 'Błąd rezerwacji',
                                text: 'Nie udało się zarezerwować wizyty.',
                                icon: 'error'
                            });
                        });
                    }
                });
            }
        });
    }
    calendar.render();
}

function refreshCalendar() {
    if (calendar) {
        calendar.refetchEvents();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    loadAppointments(undefined, true);
});

function cancelAppointment(appointmentId) {
    Swal.fire({
        title: 'Czy na pewno chcesz odwołać zaplanowaną wizytę?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak, odwołaj',
        cancelButtonText: 'Nie'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/gabinet/app/controllers/patient_cancel_appointment.php',
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
                    refreshCalendar();
                },
                error: function (error) {
                    Swal.fire(
                        'Błąd!',
                        'Nie udało się anulować wizyty.',
                        'error'
                    );
                }
            });
        }
    });
}

var globalAppointments = []; // Przechowuje wszystkie wizyty

function loadAppointments(filterStatus = 'scheduled', isInitialLoad = false, filterText = 'zaplanowane:') {
    document.getElementById('appointmentsHeader').textContent = 'Wizyty ' + filterText;
    $.ajax({
        url: '/gabinet/app/controllers/get_patient_appointments.php',
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
        } else if (sortKey === 'dentist') {
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
            } else {
                html += '<td></td>';
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

function refreshCalendar() {
    if (calendar) {
        calendar.refetchEvents();
    }
}