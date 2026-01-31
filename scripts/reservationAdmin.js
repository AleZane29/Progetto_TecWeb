document.getElementById('navButtonPrenota').removeAttribute('href');
document.getElementById('navButtonPrenota').removeAttribute('aria-label');


initReservations({
	colIndices: {
		name: 0,
		sport: 1,
		court: 2,
		date: 3,
		time: 4
	},
	hasNameSearch: true,
	isAdmin: true
});
