document.getElementById('navMenuAccount').classList.add('active');

document.addEventListener('DOMContentLoaded', function () {
	const editBtn = document.getElementById('editBtn');
	const saveBtn = document.getElementById('saveBtn');

	const editableInputs = document.querySelectorAll(
		'#profileForm input:not(#email, #username)'
	);

	if (editBtn) {
		editBtn.addEventListener('click', function () {
			editableInputs.forEach((input) => {
				input.removeAttribute('readonly');
			});
			const nameInput = document.getElementById('name');
			if (nameInput) nameInput.focus();

			editBtn.style.display = 'none';
			saveBtn.style.display = 'block';
		});
	}
});
