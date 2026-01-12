const availableTimeSlots = [
    "08:00-09:30",
    "09:30-11:00",
    "11:00-12:30",
    "13:00-14:30",
    "14:30-16:00",
    "16:00-17:30",
    "17:30-19:00",
    "19:00-20:30",
    "20:30-22:00",
];

document.addEventListener('DOMContentLoaded', function() {
    setMinDate();
});

/**
 * Imposta la data minima del calendario a DOMANI.
 * Poiché serve un preavviso di 24h, oggi non è mai prenotabile.
 */
function setMinDate() {
    const dateInput = document.getElementById("date");
    if (dateInput) {
        const today = new Date();
        
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);

        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');
        
        dateInput.min = `${yyyy}-${mm}-${dd}`;
    }
}

/**
 * Renderizza la tabella orari controllando prenotazioni e limite 24h
 * @param {Array} bookedSlots 
 */
function renderTimetable(bookedSlots = []) {
    const container = document.getElementById("timetable-container");
    const dateValue = document.getElementById("date").value; 
    
    container.innerHTML = "";

    const now = new Date();
    const limitTime = new Date(now.getTime() + (24 * 60 * 60 * 1000));

    availableTimeSlots.forEach((time) => {
        const isBooked = bookedSlots.includes(time);
        let isTooSoon = false;

        if (dateValue) {
            const startTime = time.split('-')[0];
            
            const slotDate = new Date(`${dateValue}T${startTime}:00`);

            if (slotDate < limitTime) {
                isTooSoon = true;
            }
        }

        const label = document.createElement("label");
        const input = document.createElement("input");
        const span = document.createElement("span"); 

        input.type = "radio";
        input.name = "timetable";
        input.value = time;

        // LOGICA DI DISABILITAZIONE
        // 1. Se è già prenotato (dal DB) -> Disabilita
        // 2. Se è troppo presto (< 24h) -> Disabilita
        // 3. Se non ha selezionato una data -> Disabilita tutto (per sicurezza)
        if (isBooked || isTooSoon || !dateValue) {
            input.disabled = true;
            label.classList.add("disabled");
            
            // Feedback visivo (opzionale, utile per debugging)
            if(isTooSoon) {
                label.style.opacity = "0.5"; 
                label.title = "Prenotabile solo con 24h di anticipo";
            }
        } else {
            input.required = true;
            label.style.cursor = "pointer"; // Cursore mano se attivo
        }

        span.textContent = ` ${time}`;

        label.appendChild(input);
        label.appendChild(span);

        container.appendChild(label);
    });
}

function getReservations() {
    let sportSelezionato = document.querySelector('input[name="sport"]:checked');
    let campoSelezionato = document.querySelector('input[name="campo"]:checked');
    let dataSelezionata = document.getElementById("date").value;

    // Se manca qualcosa, pulisci la tabella e esci
    if (!sportSelezionato || !campoSelezionato || !dataSelezionata) {
        renderTimetable([]); // Ridisegna vuoto/disabilitato
        return;
    }

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                renderTimetable(response);
            } catch (e) {
                console.error("ERRORE DI PARSING JSON:", e);
                alert("Errore nel caricamento dati.");
            }
        } else {
            console.error("Errore del server. Codice:", this.status);
        }
    };

    xhttp.open(
        "GET",
        "../controller/reservationUserController.php?sport=" +
        sportSelezionato.value +
        "&campo=" +
        campoSelezionato.value +
        "&data=" +
        dataSelezionata
    );
    xhttp.send();
}

function clearInput(app) {
    setMinDate();

    if (app != "none") {
        let campi_label = document.getElementsByClassName("campo-label");
        let campi_input = document.getElementsByName("campo");
        let j = 0;

        // Disabilita tutto prima di riabilitare quelli giusti
        for (var i = 0; i < campi_input.length; i++) {
            campi_input[i].checked = false;
            campi_input[i].disabled = true;
            campi_label[i].classList.add("disabled");
        }

        switch (app) {
            case "tennis": j = 3; break;
            case "basket": j = 2; break;
            case "calcetto": j = 1; break;
            default: console.log("Errore: sport non riconosciuto"); return; 
        }

        for (var i = 0; i < j; i++) {
            campi_input[i].disabled = false;
            campi_label[i].classList.remove("disabled");
        }
    }

    document.getElementById("date").value = "";
    
    renderTimetable([]); 
}