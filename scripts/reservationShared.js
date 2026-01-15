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

    disableLateReservations();
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


function filterTable() {
    // Gestione input Search Name 
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

function openDeleteDialog(id) {
    reservation = id;
    const dialog = document.getElementById('dialogDelete');
    dialog.classList.add('active');


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
    document.onkeydown = null;
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

function openEditDialog(id, button) {
    const row = button.closest('tr');
    const cells = row.cells;
    const indices = tableConfig.colIndices;

    // Se NON Admin, non puoi aprire una prenotazione che inizia entro 24 ore
    if (!tableConfig.isAdmin) {
        const dateStrCheck = cells[indices.date].textContent;
        const timeStrCheck = cells[indices.time].textContent.split(' - ')[0];
        const isoDateCheck = convertDate(dateStrCheck);
        const reservationDate = new Date(`${isoDateCheck}T${timeStrCheck}:00`);
        const now = new Date();
        const limitMs = 24 * 60 * 60 * 1000;

        if ((reservationDate - now) < limitMs) {
            alert("⚠️ Non modificabile: mancano meno di 24 ore all'evento.");
            return; // Blocca l'apertura
        }
    }

    reservation = id;
    currentRow = row;

    
    const today = new Date();
    let minDateObj = new Date(today);

    if (tableConfig.isAdmin) {
        // ADMIN: Può selezionare da OGGI in poi
    } else {
        // UTENTE: Può selezionare solo da DOMANI in poi
        minDateObj.setDate(today.getDate() + 1);
    }
    
    const minDateString = minDateObj.toISOString().split('T')[0];
    
    const dateInput = document.getElementById('editData');
    if (dateInput) {
        dateInput.setAttribute('min', minDateString);
    }

    const editCliente = document.getElementById('editCliente');
    if (editCliente && indices.name !== null) {
        editCliente.value = cells[indices.name].textContent;
    }

    document.getElementById('editSport').value = cells[indices.sport].textContent;
    changeSportDialog(); 

    document.getElementById('editCampo').value = cells[indices.court].textContent.split(' ')[1];

    const dateStr = cells[indices.date].textContent;
    const dateForInput = convertDate(dateStr); 
    document.getElementById('editData').value = dateForInput;

    document.getElementById('editOrario').value = cells[indices.time].textContent;

    changeDateDialog(); 

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
    const dateInput = document.getElementById('editData').value;
    const courtInput = document.getElementById('editCampo').value;
    const timeSelect = document.getElementById('editOrario');
    
    // Se non ho data o campo, resetto tutto
    if (!dateInput || !courtInput) {
        Array.from(timeSelect.options).forEach(opt => {
            opt.disabled = false;
            opt.hidden = false;
            opt.style.display = '';
        });
        return;
    }

    const now = new Date(); 
    const limitTime = new Date(now.getTime() + (24 * 60 * 60 * 1000)); 


    const formData = new FormData();
    formData.append('action', 'get_slots');
    formData.append('court', courtInput);
    formData.append('date', dateInput);
    
    if (reservation) {
        formData.append('excludeId', reservation);
    }

    fetch('../controller/editReservation.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(bookedSlots => {

        Array.from(timeSelect.options).forEach((option) => {
            
            const isBooked = bookedSlots.some(slot => slot.trim() === option.value.trim());

            let isTooSoon = false;
            const startTimeString = option.value.split(' - ')[0]; 
            if (startTimeString) {

                const slotDate = new Date(`${dateInput}T${startTimeString}`);
                
                // Se la data dello slot è precedente al limite delle 24 ore, è troppo presto
                if (slotDate < limitTime) {
                    isTooSoon = true;
                }
            }

            // SE È OCCUPATO OPPURE È TROPPO PRESTO -> NASCONDI
            if (isBooked || isTooSoon) {
                option.disabled = true;         
                option.style.display = ''; 

            } else {
                // LIBERO E VALIDO
                option.disabled = false;
                option.hidden = false;
                option.style.display = ''; 
            }
        });
    })
    .catch(err => console.error("Errore recupero orari:", err));
}

function resetTime() {
    document.getElementById('editOrario').value = '';
    changeDateDialog();
}

function confirmEdit() {
    const sport = document.getElementById('editSport').value;
    const court = document.getElementById('editCampo').value;
    const data = document.getElementById('editData').value;
    const timeVal = document.getElementById('editOrario').value;
    
    if(!timeVal) return false; 

    const timeStart = timeVal.split(' - ')[0];
    const timeEnd = timeVal.split(' - ')[1];
    
    editReservation(sport, court, data, timeStart, timeEnd);
    return false; 
}

function editReservation(sport, court, date, timeStart, timeEnd) {
    const data = new URLSearchParams();
    data.append('action', 'save');
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
    .then(async (response) => {
        if (response.ok) {
            location.reload();
            alert('Prenotazione modificata con successo!');
        } else {
            try {
                const errorData = await response.json();
                alert("IMPOSSIBILE MODIFICARE: " + (errorData.message || "Errore sconosciuto"));
            } catch(e) {
                alert("Errore server generico.");
            }
        }
    })
    .catch((error) => console.error('Error:', error));
}

/**
 * Scansiona la tabella e disabilita i bottoni modifica in base alle regole:
 * - ADMIN: Disabilita solo se l'evento è già passato.
 * - UTENTE: Disabilita se mancano meno di 24 ore.
 */
function disableLateReservations() {
    // Se non ci sono righe, non fare nulla
    if (!allRows || allRows.length === 0) return;

    const indices = tableConfig.colIndices;
    const now = new Date();

    // CALCOLO DEL LIMITE
    // Se isAdmin è true -> limite 0 (basta che non sia passato)
    // Se isAdmin è false -> limite 86400000 ms (24 ore)
    const limitMs = tableConfig.isAdmin ? 0 : (24 * 60 * 60 * 1000);

    allRows.forEach((row) => {
        const dateStr = row.cells[indices.date].textContent; 
        const timeStr = row.cells[indices.time].textContent.split(' - ')[0];

        const isoDate = convertDate(dateStr); 
        const reservationDate = new Date(`${isoDate}T${timeStr}:00`);
        const diff = reservationDate - now;
        const editBtn = row.querySelector('.btn-edit');

        if (editBtn) {
            // SE SIAMO SOTTO IL LIMITE (Troppo tardi o già passato)
            if (diff < limitMs) {
                editBtn.disabled = true;
                
                editBtn.style.opacity = "0.4";
                editBtn.style.cursor = "not-allowed"; 
                
                editBtn.title = tableConfig.isAdmin 
                    ? "Evento già terminato" 
                    : "Non modificabile";
            } else {
                editBtn.disabled = false;
                editBtn.style.opacity = "1";
                editBtn.style.cursor = "pointer";
                editBtn.removeAttribute('title');
            }
        }
    });
}