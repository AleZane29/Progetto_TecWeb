const aNav = document.getElementById('navButtonPrenota');
const spanPage = document.createElement('span');
spanPage.innerHTML = aNav.innerHTML;

spanPage.className = aNav.className + ' subscribe-button';

spanPage.id = aNav.id;
spanPage.setAttribute('aria-current', 'page');
aNav.replaceWith(spanPage);


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
