document.getElementById('navButtonPrenota').removeAttribute('href');
document.getElementById('navButtonPrenota').removeAttribute('aria-label');


const availableTimeSlots = [
	'08:00 - 09:30',
	'09:30 - 11:00',
	'11:00 - 12:30',
	'13:00 - 14:30',
	'14:30 - 16:00',
	'16:00 - 17:30',
	'17:30 - 19:00',
	'19:00 - 20:30',
	'20:30 - 22:00'
];

const ore_preavviso = 24;

function dataOraMinima() {
	const now = new Date();

	return new Date(now.getTime() + 24 * 60 * 60 * 1000);
}

document.addEventListener('DOMContentLoaded', function () {
	setDataMinima();
});

function setDataMinima() {
	const date = document.getElementById('date');

	if (date) {
		const minDateTime = dataOraMinima();

		const m = minDateTime.getMonth() + 1;
		const d = minDateTime.getDate();

		const year = minDateTime.getFullYear();
		const month = m < 10 ? `0${m}` : m;
		const day = d < 10 ? `0${d}` : d;

		date.min = `${year}-${month}-${day}`;
	}
}

function renderTimetable(bookedSlots = []) {
	const container = document.getElementById('timetable-container');
	const date = document.getElementById('date').value;

	container.innerHTML = '';

	const minDateTime = dataOraMinima();

	availableTimeSlots.forEach((time) => {
		const isBooked = bookedSlots.includes(time);
		let isTooSoon = false;

		if (date) {
			const startTime = time.split(' - ')[0];
			const timeSlot = new Date(`${date}T${startTime}:00`);

			if (timeSlot < minDateTime) {
				isTooSoon = true;
			}
		}

		const label = document.createElement('label');
		const input = document.createElement('input');
		const span = document.createElement('span');

		input.type = 'radio';
		input.name = 'timetable';
		input.value = time;

		if (isBooked || isTooSoon || !date) {
			input.disabled = true;
			label.classList.add('disabled');

			if (isTooSoon) {
				label.style.opacity = '0.5';
				label.title = 'Prenotabile solo con 24h di anticipo';
			}
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
	let dataSelezionata = document.getElementById('date').value;

	if (!sportSelezionato || !campoSelezionato || !dataSelezionata) {
		renderTimetable([]);
		return;
	}

	const xhttp = new XMLHttpRequest();

	xhttp.onload = function () {
		if (this.status === 200) {
			try {
				const response = JSON.parse(this.responseText);

				renderTimetable(response);
			} catch (e) {
				console.error('ERRORE DI PARSING JSON:', e);
				console.log('Cosa ha risposto il server:', this.responseText);
				alert('Errore nel caricamento dati.');
			}
		} else {
			console.error('Errore del server. Codice:', this.status);
		}
	};

	xhttp.open(
		'GET',
		'../controller/reservationUserController.php?sport=' +
			sportSelezionato.value +
			'&campo=' +
			campoSelezionato.value +
			'&data=' +
			dataSelezionata
	);
	xhttp.send();
}

function clearInput(app) {
	setDataMinima();

	if (app != 'none') {
		let campi_label = document.getElementsByClassName('campo-label');
		let campi_input = document.getElementsByName('campo');
		let j = 0;

		for (var i = 0; i < campi_input.length; i++) {
			campi_input[i].checked = false;

			campi_input[i].disabled = true;
			campi_label[i].classList.add('disabled');
		}

		switch (app) {
			case 'tennis':
				j = 3;
				break;

			case 'basket':
				j = 1;
				break;

			case 'calcetto':
				j = 2;
				break;
			default:
				console.log('Errore: sport non riconosciuto');
				return;
		}

		for (var i = 0; i < j; i++) {
			campi_input[i].disabled = false;
			campi_label[i].classList.remove('disabled');
		}
	}

	document.getElementById('date').value = '';

	renderTimetable([]);
}
