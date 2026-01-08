// reservationUser.js

// 1. Definiamo tutti gli slot orari possibili del tuo centro sportivo
const availableTimeSlots = [
  "08:00:00-09:30:00",
  "09:30:00-11:00:00",
  "11:00:00-12:30:00",
  "13:00:00-14:30:00",
  "14:30:00-16:00:00",
  "16:00:00-17:30:00",
  "17:30:00-19:00:00",
  "19:00:00-20:30:00",
  "20:30:00-22:00:00",
];

/**
 * Genera l'HTML per la tabella oraria.
 * @param {Array} bookedSlots - Array di stringhe con gli orari già prenotati (es. ["9:30-11:00"])
 */
function renderTimetable(bookedSlots = []) {
//   alert("Risposta ricevuta dal server: " + bookedSlots);
//   alert(
//     "Funzione renderTimetable chiamata con slot prenotati: " +
//       bookedSlots.join(", ")
//   );

  const container = document.getElementById("timetable-container");

  // Puliamo il contenitore per evitare duplicati se la funzione viene richiamata
  container.innerHTML = "";

  availableTimeSlots.forEach((time) => {
    // 2. Controlliamo se l'orario attuale è presente nella lista di quelli prenotati
    const isBooked = bookedSlots.includes(time);

    // 3. Creiamo gli elementi HTML
    const label = document.createElement("label");
    const input = document.createElement("input");
    const span = document.createElement("span"); // Utile per lo stile CSS del testo

    // Configuriamo l'input radio
    input.type = "radio";
    input.name = "timetable";
    input.value = time;

    // Se è prenotato, lo disabilitiamo
    if (isBooked) {
    //   alert("L'orario " + time + " è prenotato e sarà disabilitato.");
      input.disabled = true;
      label.classList.add("disabled"); // Aggiungiamo una classe per lo stile (es. grigio/barrato)
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

function getReservations() {
  let sportSelezionato = document.querySelector('input[name="sport"]:checked');
  let campoSelezionato = document.querySelector('input[name="campo"]:checked');
  let dataSelezionata = document.getElementById("date").value;

  if (!sportSelezionato || !campoSelezionato || !dataSelezionata) {
    alert("Per favore, seleziona sport, campo e data prima di procedere.");
    return;
  }

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    renderTimetable(JSON.parse(this.responseText));
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
  if (app != "none") {
    // Usa 'let' o 'const' per definire le variabili (evita variabili globali)
    let campi = document.getElementsByName("campo");
    let j = 0; // Inizializziamo j

    // PRIMA FASE: Resetta tutto (disabilita e toglie la spunta)
    for (var i = 0; i < campi.length; i++) {
      campi[i].checked = false;

      // ERRORE 1: .classList.add cambia solo l'estetica.
      // Devi usare la proprietà .disabled per bloccare il click vero e proprio.
      campi[i].disabled = true;
      // Se vuoi anche cambiare l'aspetto visivo mantieni pure la classe:
      campi[i].classList.add("disabled");
    }

    switch (app) {
      case "tennis":
        j = 3; // Abiliterà campi[0] e campi[1]
        break;

      case "basket":
        j = 1; // ATTENZIONE: Con 0, il ciclo sotto non parte mai. Nessun campo si attiverà.
        break;

      case "calcetto":
        j = 2; // Abiliterà campi[0]
        break;

      default:
        console.log("Errore: sport non riconosciuto");
        return; // Esce dalla funzione se c'è un errore
    }

    // SECONDA FASE: Riabilita solo quelli necessari
    for (var i = 0; i < j; i++) {
      // Controllo di sicurezza: verifichiamo che il campo esista
      if (campi[i]) {
        campi[i].disabled = false; // Riattiva il click
        campi[i].classList.remove("disabled"); // Ripristina l'estetica
      }
    }
  }

  // Resetta la data
  document.getElementById("date").value = "";
}
