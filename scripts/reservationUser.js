
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

/**
 * 
 * @param {Array} bookedSlots 
 */
function renderTimetable(bookedSlots = []) {


  const container = document.getElementById("timetable-container");

  container.innerHTML = "";

  availableTimeSlots.forEach((time) => {
    const isBooked = bookedSlots.includes(time);

    const label = document.createElement("label");
    const input = document.createElement("input");
    const span = document.createElement("span"); 

    input.type = "radio";
    input.name = "timetable";
    input.value = time;

    if (isBooked) {
      input.disabled = true;
      label.classList.add("disabled"); 
    } else {
      input.required = true;
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

  if (!sportSelezionato || !campoSelezionato || !dataSelezionata) {
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
        console.log("Cosa ha risposto il server:", this.responseText);
        alert("Errore nel caricamento dati. Apri la console (F12) per i dettagli.");
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
  if (app != "none") {
    let campi_label = document.getElementsByClassName("campo-label");
    let campi_input = document.getElementsByName("campo");
    let j = 0;

    for (var i = 0; i < campi_input.length; i++) {
      campi_input[i].checked = false;

      campi_input[i].disabled = true;
      campi_label[i].classList.add("disabled");
    }

    switch (app) {
      case "tennis":
        j = 3; 
        break;

      case "basket":
        j = 1;
        break;

      case "calcetto":
        j = 2; 
        break;

      default:
        console.log("Errore: sport non riconosciuto");
        return; 
    }

    for (var i = 0; i < j; i++) {
        campi_input[i].disabled = false;
        campi_label[i].classList.remove("disabled");
      
    }
  }

  // Resetta la data
  document.getElementById("date").value = "";
}
