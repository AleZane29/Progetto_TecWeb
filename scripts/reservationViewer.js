/* FILE: reservationViewer.js
   Questo file configura la gestione prenotazioni per l'UTENTE (Account).
   L'Utente NON vede il proprio nome nella tabella, quindi le colonne scalano.
*/

// Chiamiamo la funzione init definita in reservationShared.js
initReservations({
    // Mappatura delle colonne
    colIndices: {
        name: null, // L'utente non ha la colonna "Cliente"
        sport: 0,   // Colonna "Sport" ora è la prima (indice 0)
        court: 1,   // Colonna "Campo" (indice 1)
        date: 2,    // Colonna "Data"  (indice 2)
        time: 3     // Colonna "Orario" (indice 3)
    },
    // L'Utente ha la casella di ricerca per nome? NO
    hasNameSearch: false
});