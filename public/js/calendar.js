
function splitIntoSessionsWithBreaks(data) {
            const sessions = [];
            data.forEach(item => {
                let startTime = new Date(item.start_time);
                const endTime = new Date(item.end_time);
                const dentistName = item.first_name + ' ' + item.last_name;

                while (startTime < endTime) {
                    const sessionEnd = new Date(startTime.getTime() + (50 * 60 * 1000));

                    if (sessionEnd <= endTime) {
                        sessions.push({
                            title: dentistName,
                            start: startTime.toISOString(),
                            end: sessionEnd.toISOString(),
                            color: 'green'
                        });
                    }

                    startTime = new Date(sessionEnd.getTime() + (10 * 60 * 1000));
                }
            });
            return sessions;
        }


        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('/gabinet/app/controllers/get_availability.php')
                        .then(response => response.json())
                        .then(data => {
                            const sessionsWithBreaks = splitIntoSessionsWithBreaks(data);
                            successCallback(sessionsWithBreaks);
                        })
                        .catch(error => failureCallback(error));
                },
                eventClick: function(info) {
                    // Logika rezerwacji dla klikniętej sesji
                    console.log('Kliknięto sesję: ', info.event.startStr, ' Lekarz: ', info.event.title);
                    // Możesz tu otworzyć modal, formularz rezerwacji itp.
                }

            });
            calendar.render();
        });