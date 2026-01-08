// reservationUser.js

// 1. Definiamo tutti gli slot orari possibili del tuo centro sportivo
const availableTimeSlots = [
    "8:00-9:30",
    "9:30-11:00",
    "11:00-12:30",
    "13:00-14:30",
    "14:30-16:00",
    "16:00-17:30",
    "17:30-19:00",
    "19:00-20:30",
    "20:30-22:00"
];

/**
 * Genera l'HTML per la tabella oraria.
 * @param {Array} bookedSlots - Array di stringhe con gli orari già prenotati (es. ["9:30-11:00"])
 */
function renderTimetable(bookedSlots = []) {

    alert("Funzione renderTimetable chiamata con slot prenotati: " + bookedSlots.join(", "));

    const container = document.getElementById('timetable-container');
    
    // Puliamo il contenitore per evitare duplicati se la funzione viene richiamata
    container.innerHTML = '';

    availableTimeSlots.forEach(time => {
        // 2. Controlliamo se l'orario attuale è presente nella lista di quelli prenotati
        const isBooked = bookedSlots.includes(time);

        // 3. Creiamo gli elementi HTML
        const label = document.createElement('label');
        const input = document.createElement('input');
        const span = document.createElement('span'); // Utile per lo stile CSS del testo
        
        // Configuriamo l'input radio
        input.type = 'radio';
        input.name = 'timetable';
        input.value = time;
        
        // Se è prenotato, lo disabilitiamo
        if (isBooked) {
            input.disabled = true;
            label.classList.add('disabled'); // Aggiungiamo una classe per lo stile (es. grigio/barrato)
        } else {
            // Se vuoi che sia required (basta metterlo su uno del gruppo, ma qui lo mettiamo condizionale)
            input.required = true;
        }

        // Configuriamo il testo
        span.textContent = ` ${time}`; // Lo spazio iniziale è per separarlo dal radio button visivamente

        // 4. Assembliamo l'elemento: <label><input> <span>Testo</span></label>
        label.appendChild(input);
        label.appendChild(span);

        // 5. Inseriamo nel contenitore principale
        container.appendChild(label);
    });
}

// ESEMPIO DI UTILIZZO (per testare subito se funziona):
// Immagina che dal database arrivi che le 9:30 e le 19:00 sono occupate.
// Scommenta la riga sotto per vedere l'effetto:

// renderTimetable(["9:30-11:00", "19:00-20:30"]);