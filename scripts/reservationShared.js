// scripts/reservationShared.js

// Variabili Globali
let reservation = null;
let currentRow = null;
let currentPage = 1;
let rowsPerPage = 10;
let allRows = [];

// Configurazione di default (sarà sovrascritta dalla funzione init)
let tableConfig = {
    colIndices: {
        name: null,   // Indice colonna nome (null se non esiste)
        sport: 0,     // Indice colonna sport
        court: 1,     // Indice colonna campo
        date: 2,      // Indice colonna data
        time: 3       // Indice colonna orario
    },
    hasNameSearch: false // Se vero, attiva il filtro sul nome
};

/**
 * Funzione di inizializzazione da chiamare nei file specifici
 * @param {Object} config - La configurazione delle colonne
 */
function initReservations(config) {
    // Uniamo la configurazione passata con quella di default
    tableConfig = { ...tableConfig, ...config };
    
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('tableBody');
        if (tbody) {
            allRows = Array.from(tbody.querySelectorAll('tr'));
            updatePagination();
        }
    });
}

/* =========================================
   LOGICA PAGINAZIONE
   ========================================= */

function updatePagination() {
    const filteredRows = allRows.filter((row) => row.style.display !== 'none');
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

    allRows.forEach((row) => row.classList.add('pagination-hidden'));

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const rowsToShow = filteredRows.slice(start, end);
    rowsToShow.forEach((row) => row.classList.remove('pagination-hidden'));

    const showingFromFn = document.getElementById('showingFrom');
    if(showingFromFn) showingFromFn.textContent = filteredRows.length > 0 ? start + 1 : 0;
    
    const showingToFn = document.getElementById('showingTo');
    if(showingToFn) showingToFn.textContent = Math.min(end, filteredRows.length);
    
    const totalRecordsFn = document.getElementById('totalRecords');
    if(totalRecordsFn) totalRecordsFn.textContent = filteredRows.length;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if(prevBtn) prevBtn.disabled = currentPage === 1;
    if(nextBtn) nextBtn.disabled = currentPage === totalPages || totalPages === 0;

    generatePageNumbers(totalPages);
}

function generatePageNumbers(totalPages) {
    const pageNumbersDiv = document.getElementById('pageNumbers');
    if(!pageNumbersDiv) return;
    
    pageNumbersDiv.innerHTML = '';

    if (totalPages <= 7) {
        for (let i = 1; i <= totalPages; i++) {
            pageNumbersDiv.appendChild(createPageButton(i));
        }
    } else {
        pageNumbersDiv.appendChild(createPageButton(1));
        if (currentPage > 3) pageNumbersDiv.appendChild(createDots());

        let startPage = Math.max(2, currentPage - 1);
        let endPage = Math.min(totalPages - 1, currentPage + 1);

        for (let i = startPage; i <= endPage; i++) {
            pageNumbersDiv.appendChild(createPageButton(i));
        }

        if (currentPage < totalPages - 2) pageNumbersDiv.appendChild(createDots());
        if (totalPages > 1) pageNumbersDiv.appendChild(createPageButton(totalPages));
    }
}

function createPageButton(pageNum) {
    const button = document.createElement('button');
    button.className = 'pagination-btn page-number';
    button.textContent = pageNum;
    button.onclick = () => goToPage(pageNum);
    if (pageNum === currentPage) button.classList.add('active');
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

/* =========================================
   LOGICA FILTRI
   ========================================= */

function filterTable() {
    // Gestione input Search Name (solo se esiste nel DOM e nella config)
    let searchName = '';
    const searchInput = document.getElementById('searchName');
    if (tableConfig.hasNameSearch && searchInput) {
        searchName = searchInput.value.toLowerCase();
    }

    const filterSport = document.getElementById('filterSport').value.toLowerCase();
    const filterCourt = document.getElementById('filterCourt').value.toLowerCase();
    const filterDate = document.getElementById('filterDate').value;

    allRows.forEach((row) => {
        // Recupero i dati dalle celle usando gli indici configurati
        const sport = row.cells[tableConfig.colIndices.sport].textContent.toLowerCase();
        const court = row.cells[tableConfig.colIndices.court].textContent.toLowerCase();
        const date = row.cells[tableConfig.colIndices.date].textContent;

        // Gestione Nome (se configurato)
        let matchName = true;
        if (tableConfig.hasNameSearch && tableConfig.colIndices.name !== null) {
            const name = row.cells[tableConfig.colIndices.name].textContent.toLowerCase();
            matchName = name.includes(searchName);
        }

        const matchSport = !filterSport || sport.includes(filterSport);
        const matchCourt = !filterCourt || court.includes(filterCourt);
        const matchDate = !filterDate || convertDate(date) === filterDate;

        if (matchName && matchSport && matchCourt && matchDate) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Reset alla pagina 1 quando si filtra
    currentPage = 1; 
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
    const searchName = document.getElementById('searchName');
    if(searchName) searchName.value = '';
    
    document.getElementById('filterSport').value = '';
    document.getElementById('filterCourt').value = '';
    document.getElementById('filterDate').value = '';
    filterTable();
}

function changeSport() {
    const sportSelect = document.getElementById('filterSport');
    const courtsSelect = document.getElementById('filterCourt');
    const sport = sportSelect.value;
    const courts = document.querySelectorAll('#filterCourt option');

    courtsSelect.value = '';
    courts.forEach((courtOption) => {
        // Ignora la prima option vuota se non ha attributi
        if (!courtOption.getAttribute("data-court-id") && courtOption.value === "") {
             courtOption.style.display = '';
             return;
        }
        
        if (!(courtOption.getAttribute("data-court-id")) || (sport != '' && courtOption.getAttribute("data-court-id").includes(sport))) {
            courtOption.style.display = '';
        } else {
            courtOption.style.display = 'none';
        }
    });
    filterTable();
}

/* =========================================
   LOGICA DIALOG DELETE
   ========================================= */

function openDeleteDialog(id) {
    reservation = id;
    const dialog = document.getElementById('dialogDelete');
    dialog.classList.add('active');

    // Listener click outside (rimuovere listener precedenti per evitare duplicati sarebbe meglio, ma qui semplifichiamo)
    dialog.onclick = function (e) {
        if (e.target === this) closeDeleteDialog();
    };

    document.onkeydown = function (e) {
        if (e.key === 'Escape') closeDeleteDialog();
    };
}

function closeDeleteDialog() {
    document.getElementById('dialogDelete').classList.remove('active');
    reservation = null;
    document.onkeydown = null; // Rimuove listener globale escape
}

function deleteReservation() {
    fetch('../controller/deleteReservation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'idPrenotazione=' + encodeURIComponent(reservation)
    })
    .then(() => {
        location.reload();
        alert('Prenotazione eliminata con successo!');
    })
    .catch((error) => console.error('Error:', error));
    
    closeDeleteDialog();
}

/* =========================================
   LOGICA DIALOG EDIT
   ========================================= */

function openEditDialog(id, button) {
    reservation = id;
    currentRow = button.closest('tr');
    const cells = currentRow.cells;
    const indices = tableConfig.colIndices;

    // Popola campo Cliente (Solo Admin)
    const editCliente = document.getElementById('editCliente');
    if (editCliente && indices.name !== null) {
        editCliente.value = cells[indices.name].textContent;
    }

    // Popola Sport
    document.getElementById('editSport').value = cells[indices.sport].textContent;
    changeSportDialog(); // Aggiorna i campi disponibili in base allo sport

    // Popola Campo (Rimuove "Campo " dalla stringa)
    document.getElementById('editCampo').value = cells[indices.court].textContent.split(' ')[1];

    // Popola Data (Formato DD/MM/YYYY presente in tabella -> input type date vuole YYYY-MM-DD)
    // Se nel tuo HTML hai già la funzione o il valore pronto, usa quello. 
    // Qui assumo che nella cella ci sia DD/MM/YYYY e l'input date voglia YYYY-MM-DD
    const dateStr = cells[indices.date].textContent;
    const dateForInput = convertDate(dateStr); // Riutilizziamo la funzione helper
    document.getElementById('editData').value = dateForInput;

    // Popola Orario
    document.getElementById('editOrario').value = cells[indices.time].textContent;

    changeDateDialog(); // Aggiorna disponibilità orari

    const dialog = document.getElementById('editDialog');
    dialog.classList.add('active');

    dialog.onclick = function (e) {
        if (e.target === this) closeEditDialog();
    };

    document.onkeydown = function (e) {
        if (e.key === 'Escape') closeEditDialog();
    };
}

function closeEditDialog() {
    document.getElementById('editDialog').classList.remove('active');
    currentRow = null;
    reservation = null;
    document.onkeydown = null;
}

function changeSportDialog() {
    const sportSelect = document.getElementById('editSport');
    const courtsSelect = document.getElementById('editCampo');
    const sport = sportSelect.value;
    const courts = document.querySelectorAll('#editCampo option');
    
    // Reset selezione campo se cambia lo sport
    courtsSelect.value = '';

    courts.forEach((court) => {
        if (!(court.getAttribute("data-court-id")) || (sport != '' && court.getAttribute("data-court-id").includes(sport))) {
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
    
    // Qui serve sapere gli indici per scansionare le ALTRE righe
    const indices = tableConfig.colIndices;
    
    const rows = document.querySelectorAll('#tableBody tr');
    const timeOptions = document.querySelectorAll('#editOrario option');
    const bookedTimes = new Set();

    rows.forEach((row) => {
        if (row !== currentRow) { // Non controllo me stesso
            const rowSport = row.cells[indices.sport].textContent;
            // Nota: nella tabella è scritto "Campo 5", nel value del select è "5". 
            // Controllo loose con includes per sicurezza
            const rowCourt = row.cells[indices.court].textContent; 
            const date = row.cells[indices.date].textContent;
            const time = row.cells[indices.time].textContent;

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

    // Se l'orario attualmente selezionato è diventato non disponibile (conflitto generato) resetta
    if (bookedTimes.has(document.getElementById('editOrario').value)) {
        document.getElementById('editOrario').value = '';
    }
}

function confirmEdit() {
    const sport = document.getElementById('editSport').value;
    const court = document.getElementById('editCampo').value;
    const data = document.getElementById('editData').value;
    const timeVal = document.getElementById('editOrario').value;
    
    if(!timeVal) return false; // Sicurezza

    const timeStart = timeVal.split(' - ')[0];
    const timeEnd = timeVal.split(' - ')[1];
    
    editReservation(sport, court, data, timeStart, timeEnd);
    return false; // Previene submit form HTML standard
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
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data
    })
    .then(() => {
        location.reload();
        alert('Prenotazione modificata con successo!');
    })
    .catch((error) => console.error('Error:', error));
}