let reservation = null;
function openDeleteDialog(id) {
	reservation = id;
	document.getElementById('overlay').classList.add('active');
}
function closeDeleteDialog() {
	document.getElementById('overlay').classList.remove('active');
	reservation = null;
}
document.getElementById('overlay').addEventListener('click', function (e) {
	if (e.target === this) {
		closeDeleteDialog();
	}
});
document.addEventListener('keydown', function (e) {
	if (e.key === 'Escape') {
		closeDeleteDialog();
	}
});

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
