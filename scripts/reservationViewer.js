document.getElementById('navMenuPersonalReservations').classList.add('active');
document.getElementById('navMenuPersonalReservations').removeAttribute('href');
document.getElementById('navMenuPersonalReservations').removeAttribute('aria-label');




initReservations({
    colIndices: {
        name: null,
        sport: 0, 
        court: 1, 
        date: 2,   
        time: 3   
    },
    hasNameSearch: false,
    isAdmin: false
});