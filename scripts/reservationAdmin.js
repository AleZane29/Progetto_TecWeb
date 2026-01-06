let reservation = null;
function openDeleteDialog(id) {
	reservation = id;
	document.getElementById('dialogDelete').classList.add('active');

	document
		.getElementById('dialogDelete')
		.addEventListener('click', function (e) {
			if (e.target === this) {
				closeDeleteDialog();
			}
		});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeDeleteDialog();
		}
	});
}

function closeDeleteDialog() {
	document.getElementById('dialogDelete').classList.remove('active');
	reservation = null;
}

function deleteReservation() {
	fetch('../controller/deleteReservation.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: 'idPrenotazione=' + encodeURIComponent(reservation)
	})
		.then(() => {
			location.reload();
			alert('Prenotazione eliminata con successo!');
		})
		.catch((error) => {
			console.error('Error:', error);
		});
	closeDeleteDialog();
}

function editReservation(sport, court, date, timeStart, timeEnd) {
	const data = new URLSearchParams();
	data.append('idPrenotazione', reservation);
	data.append('sport', sport);
	data.append('court', court);
	data.append('date', date);
	data.append('timeStart', timeStart);
	data.append('timeEnd', timeEnd);

	fetch('../controller/editReservation.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: data
	})
		.then(() => {
			// location.reload();
			alert('Prenotazione modificata con successo!');
		})
		.catch((error) => {
			console.error('Error:', error);
		});
}

function filterTable() {
	const searchName = document.getElementById('searchName').value.toLowerCase();
	const filterSport = document
		.getElementById('filterSport')
		.value.toLowerCase();
	const filterCourt = document
		.getElementById('filterCourt')
		.value.toLowerCase();
	const filterDate = document.getElementById('filterDate').value;

	const rows = document.querySelectorAll('#tableBody tr');
	rows.forEach((row) => {
		const name = row.cells[0].textContent.toLowerCase();
		const sport = row.cells[1].textContent.toLowerCase();
		const court = row.cells[2].textContent.toLowerCase();
		const date = row.cells[3].textContent;

		const matchName = name.includes(searchName);
		const matchSport = !filterSport || sport.includes(filterSport);
		const matchCourt = !filterCourt || court.includes(filterCourt);
		const matchDate = !filterDate || convertDate(date) === filterDate;

		if (matchName && matchSport && matchCourt && matchDate) {
			row.style.display = '';
		} else {
			row.style.display = 'none';
		}
	});
}

function convertDate(dateStr) {
	const parts = dateStr.split('/');
	if (parts.length === 3) {
		return `${parts[2]}-${parts[1]}-${parts[0]}`;
	}
	return dateStr;
}

function resetFilters() {
	document.getElementById('searchName').value = '';
	document.getElementById('filterSport').value = '';
	document.getElementById('filterCourt').value = '';
	document.getElementById('filterDate').value = '';
	filterTable();
}

function changeSport() {
	const sportSelect = document.getElementById('filterSport');
	const courtsSelect = document.getElementById('filterCourt');
	const sport = sportSelect.value;
	const court = document.querySelectorAll('#filterCourt option');

	courtsSelect.value = '';
	court.forEach((court) => {
		if (court.id == '' || (sport != '' && court.id.includes(sport))) {
			court.style.display = '';
		} else {
			court.style.display = 'none';
		}
	});
	filterTable();
}

let currentRow = null;
function openEditDialog(id, button) {
	reservation = id;
	currentRow = button.closest('tr');
	const cells = currentRow.cells;

	document.getElementById('editCliente').value = cells[0].textContent;
	document.getElementById('editSport').value = cells[1].textContent;
	changeSportDialog();
	document.getElementById('editCampo').value =
		cells[2].textContent.split(' ')[1];
	// const dateIta = cells[3].textContent;
	// const dateParts = dateIta.split('/');
	// if (dateParts.length === 3) {
	// 	document.getElementById(
	// 		'editData'
	// 	).value = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
	// }
	document.getElementById('editData').value = cells[3].textContent;

	document.getElementById('editOrario').value = cells[4].textContent;

	document.getElementById('editDialog').classList.add('active');

	document.getElementById('editDialog').addEventListener('click', function (e) {
		if (e.target === this) {
			closeEditDialog();
		}
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeEditDialog();
		}
	});
}

function closeEditDialog() {
	document.getElementById('editDialog').classList.remove('active');
	currentRow = null;
	reservation = null;
}

function confirmEdit() {
	const sport = document.getElementById('editSport').value;
	const court = document.getElementById('editCampo').value;
	const data = document.getElementById('editData').value;

	// const dateInput = document.getElementById('editData').value;
	// const dateParts = dateInput.split('-');
	// if (dateParts.length === 3) {
	// 	cells[3].textContent = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
	// }

	const timeStart = document.getElementById('editOrario').value.split(' - ')[0];
	const timeEnd = document.getElementById('editOrario').value.split(' - ')[1];
	editReservation(sport, court, data, timeStart, timeEnd);
	closeEditDialog();
}

function changeSportDialog() {
	const sportSelect = document.getElementById('editSport');
	const courtsSelect = document.getElementById('editCampo');
	const sport = sportSelect.value;
	const courts = document.querySelectorAll('#editCampo option');
	courtsSelect.value = '';

	courts.forEach((court) => {
		if (court.id == '' || (sport != '' && court.id.includes(sport))) {
			court.style.display = '';
		} else {
			court.style.display = 'none';
		}
	});
}
