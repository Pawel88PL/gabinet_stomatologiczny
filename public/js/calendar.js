
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var patientId = calendarEl.getAttribute('data-patient-id');
    var calendar = new FullCalendar.Calendar(calendarEl, {
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
                        }).then(()=> {
                            refreshCalendar();
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
    calendar.render();

    // Funkcja do odświeżenia kalendarza
    function refreshCalendar() {
        calendar.refetchEvents();
    }
});