document.getElementById('navMenuAdminAnnouncements').classList.add('active');
document.getElementById('navMenuAdminAnnouncements').removeAttribute('href');
document.getElementById('navMenuAdminAnnouncements').removeAttribute('aria-label');


let announcement = null;

const form = document.getElementById('editAnnouncementForm');

  form.onsubmit = function(event) {
    event.preventDefault(); 
	const data = new FormData(event.target);
	const title = data.get('title');
	const description = data.get('description');
    editAnnouncement(title, description); 
  };

function openDeleteDialog(id) {
	announcement = id;
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
	announcement = null;
}

function deleteAnnouncement() {

	fetch('../controller/deleteAnnouncementController.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: 'idAnnuncio=' + encodeURIComponent(announcement)
	})
		.then(() => {
			location.reload();
		})
		.catch((error) => {
			console.error('Error:', error);
		});
	closeDeleteDialog();
}


function openEditDialog(id) {
	
	announcement = id;
	document.getElementById('editDialog').classList.add('active');

	document
		.getElementById('editDialog')
		.addEventListener('click', function (e) {
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
	announcement = null;
}

function editAnnouncement(title, description) {


	fetch('../controller/editAnnouncementController.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: 'idAnnuncio=' + encodeURIComponent(announcement) + "&title=" + encodeURIComponent(title) + "&description=" + encodeURIComponent(description)
	})
		.then(() => {
			location.reload();
		})
		.catch((error) => {
			console.error('Error:', error);
		});
	closeDeleteDialog();
}





