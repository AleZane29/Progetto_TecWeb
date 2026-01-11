/* FILE: reservationAdmin.js
   Questo file configura la gestione prenotazioni per l'ADMIN.
   L'Admin ha una colonna in più all'inizio (Cliente).
*/

// Chiamiamo la funzione init definita in reservationShared.js
initReservations({
    // Mappatura delle colonne (0 è la prima colonna a sinistra)
    colIndices: {
        name: 0,    // Colonna "Cliente" (indice 0)
        sport: 1,   // Colonna "Sport"   (indice 1)
        court: 2,   // Colonna "Campo"   (indice 2)
        date: 3,    // Colonna "Data"    (indice 3)
        time: 4     // Colonna "Orario"  (indice 4)
    },
    // L'Admin ha la casella di ricerca per nome? SI
    hasNameSearch: true
});
