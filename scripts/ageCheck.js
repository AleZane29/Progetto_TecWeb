document.addEventListener('DOMContentLoaded', function () {
	const birthInput = document.getElementById('birth');

	if (birthInput) {
		birthInput.addEventListener('change', function () {
			const birthDate = new Date(this.value);
			const today = new Date();

			let age = today.getFullYear() - birthDate.getFullYear();
			const monthDiff = today.getMonth() - birthDate.getMonth();

			if (
				monthDiff < 0 ||
				(monthDiff === 0 && today.getDate() < birthDate.getDate())
			) {
				age--;
			}

			if (age < 14) {
				this.setCustomValidity('Devi avere almeno 14 anni per iscriverti.');
				this.reportValidity();
				this.style.borderColor = 'var(--error)';
			} else {
				this.setCustomValidity('');
				this.style.borderColor = '';
			}
		});
	}
});
