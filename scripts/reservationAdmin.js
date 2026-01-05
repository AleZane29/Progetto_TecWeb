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
		})
		.catch((error) => {
			console.error('Error:', error);
		});
	closeDeleteDialog();
}

function editReservation(id) {
	fetch('../controller/editReservation.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: 'idPrenotazione=' + encodeURIComponent(id)
	})
		.then(() => {
			location.reload();
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
	console.log(rows);
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
