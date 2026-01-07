let reservation = null;
let currentRow = null;
let currentPage = 1;
let rowsPerPage = 10;
let allRows = [];

document.addEventListener('DOMContentLoaded', function () {
	allRows = Array.from(document.querySelectorAll('#tableBody tr'));
	updatePagination();
});

function updatePagination() {
	const filteredRows = allRows.filter((row) => row.style.display !== 'none');
	const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

	allRows.forEach((row) => row.classList.add('pagination-hidden'));

	const start = (currentPage - 1) * rowsPerPage;
	const end = start + rowsPerPage;
	const rowsToShow = filteredRows.slice(start, end);
	rowsToShow.forEach((row) => row.classList.remove('pagination-hidden'));

	document.getElementById('showingFrom').textContent =
		filteredRows.length > 0 ? start + 1 : 0;
	document.getElementById('showingTo').textContent = Math.min(
		end,
		filteredRows.length
	);
	document.getElementById('totalRecords').textContent = filteredRows.length;

	document.getElementById('prevBtn').disabled = currentPage === 1;
	document.getElementById('nextBtn').disabled =
		currentPage === totalPages || totalPages === 0;

	generatePageNumbers(totalPages);
}

function generatePageNumbers(totalPages) {
	const pageNumbersDiv = document.getElementById('pageNumbers');
	pageNumbersDiv.innerHTML = '';

	if (totalPages <= 7) {
		for (let i = 1; i <= totalPages; i++) {
			pageNumbersDiv.appendChild(createPageButton(i));
		}
	} else {
		pageNumbersDiv.appendChild(createPageButton(1));

		if (currentPage > 3) {
			pageNumbersDiv.appendChild(createDots());
		}

		let startPage = Math.max(2, currentPage - 1);
		let endPage = Math.min(totalPages - 1, currentPage + 1);

		for (let i = startPage; i <= endPage; i++) {
			pageNumbersDiv.appendChild(createPageButton(i));
		}

		if (currentPage < totalPages - 2) {
			pageNumbersDiv.appendChild(createDots());
		}

		if (totalPages > 1) {
			pageNumbersDiv.appendChild(createPageButton(totalPages));
		}
	}
}

function createPageButton(pageNum) {
	const button = document.createElement('button');
	button.className = 'pagination-btn page-number';
	button.textContent = pageNum;
	button.onclick = () => goToPage(pageNum);

	if (pageNum === currentPage) {
		button.classList.add('active');
	}

	return button;
}

function createDots() {
	const span = document.createElement('span');
	span.className = 'pagination-dots';
	span.textContent = '...';
	return span;
}

function goToPage(page) {
	currentPage = page;
	updatePagination();
}

function goToNextPage() {
	const filteredRows = allRows.filter((row) => row.style.display !== 'none');
	const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

	if (currentPage < totalPages) {
		currentPage++;
		updatePagination();
	}
}

function goToPreviousPage() {
	if (currentPage > 1) {
		currentPage--;
		updatePagination();
	}
}

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
			location.reload();
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
	updatePagination();
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
	changeDateDialog();
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
	changeDateDialog();
}

function changeDateDialog() {
	const filterDate = document.getElementById('editData').value;
	const sport = document.getElementById('editSport').value;
	const court = document.getElementById('editCampo').value;

	const rows = document.querySelectorAll('#tableBody tr');
	const timeOptions = document.querySelectorAll('#editOrario option');

	const bookedTimes = new Set();

	rows.forEach((row) => {
		if (row !== currentRow) {
			const rowCourt = row.cells[2].textContent;
			const rowSport = row.cells[1].textContent;
			const date = row.cells[3].textContent;
			const time = row.cells[4].textContent;
			const matchSport = !sport || rowSport.includes(sport);
			const matchCourt = !court || rowCourt.includes(court);
			const matchDate = !filterDate || convertDate(date) === filterDate;

			if (filterDate && matchSport && matchCourt && matchDate) {
				bookedTimes.add(time);
			}
		}
	});

	timeOptions.forEach((option) => {
		if (bookedTimes.has(option.value)) {
			option.disabled = true;
		} else {
			option.disabled = false;
		}
	});

	if (bookedTimes.has(document.getElementById('editOrario').value)) {
		document.getElementById('editOrario').value = '';
	}
}
