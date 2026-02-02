DROP TABLE IF EXISTS Annunci;
DROP TABLE IF EXISTS Prenotazione;
DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Campo;
DROP TABLE IF EXISTS TipoCampo;


CREATE TABLE Utente (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    data_nascita DATE NOT NULL,
    password TEXT NOT NULL,
    ruolo ENUM('Cliente', 'Admin') DEFAULT 'Cliente'
);

CREATE TABLE TipoCampo (
    nome VARCHAR(20) PRIMARY KEY,
    costo_orario INTEGER NOT NULL CHECK(costo_orario > 0)
);

CREATE TABLE Campo (
    numero INT NOT NULL AUTO_INCREMENT,
    tipo VARCHAR(20),
    PRIMARY KEY (numero, tipo),
    FOREIGN KEY (tipo) REFERENCES TipoCampo(nome) ON DELETE CASCADE
);


CREATE TABLE Prenotazione (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    utente INTEGER NOT NULL,
    numero_campo INTEGER NOT NULL,
    tipo_campo VARCHAR(20) NOT NULL,
    data DATE NOT NULL,
    ora_inizio TIME NOT NULL,
    ora_fine TIME NOT NULL,
    prezzo DECIMAL(10, 2) NOT NULL,

    FOREIGN KEY (utente) REFERENCES Utente(id) ON DELETE CASCADE,
    FOREIGN KEY (numero_campo, tipo_campo) REFERENCES Campo(numero, tipo) ON DELETE CASCADE
);

CREATE TABLE Annunci (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idAdmin INT NOT NULL,
    titolo VARCHAR(100) NOT NULL,
    descrizione TEXT NOT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idAdmin) REFERENCES Utente(id) ON DELETE CASCADE
);


INSERT INTO Utente (nome, cognome, username, email, data_nascita, password, ruolo) VALUES
('User', 'User', 'user', 'user@email.com', '1990-01-01', '$2y$10$VbcD96fPgs3zxKy0GxRg9.gEaFQ2.kNGRl9n3OoLP0aVG8VbdbDyO', 'Cliente'),
('Admin', 'Admin', 'admin', 'admin@email.com', '1985-05-10', '$2y$10$HOmpkThH3.WDpI9OmDqW3uU/NYq7p9JhT3maUZg5QjIM1HH.Shivm', 'Admin');

INSERT INTO TipoCampo (nome, costo_orario) VALUES
('Tennis', 25),
('Basket', 70),
('Calcio5', 60);

INSERT INTO Campo (numero, tipo) VALUES
(1, 'Tennis'),
(2, 'Tennis'),
(3, 'Tennis'),
(1, 'Calcio5'),
(2, 'Calcio5'),
(1, 'Basket');

INSERT INTO `Prenotazione` (`id`, `utente`, `numero_campo`, `tipo_campo`, `data`, `ora_inizio`, `ora_fine`, `prezzo`) VALUES 
(NULL, '1', '1', 'Tennis', '2026-02-10', '08:00:00', '09:30:00', '25');
INSERT INTO `Prenotazione` (`id`, `utente`, `numero_campo`, `tipo_campo`, `data`, `ora_inizio`, `ora_fine`, `prezzo`) VALUES 
(NULL, '1', '2', 'Tennis', '2026-02-11', '08:00:00', '09:30:00', '25');
INSERT INTO `Prenotazione` (`id`, `utente`, `numero_campo`, `tipo_campo`, `data`, `ora_inizio`, `ora_fine`, `prezzo`) VALUES 
(NULL, '1', '1', 'Basket', '2026-02-11', '11:00:00', '12:30:00', '70');
INSERT INTO `Prenotazione` (`id`, `utente`, `numero_campo`, `tipo_campo`, `data`, `ora_inizio`, `ora_fine`, `prezzo`) VALUES 
(NULL, '1', '1', 'Calcio5', '2026-02-11', '11:00:00', '12:30:00', '60');



INSERT INTO Annunci (idAdmin, titolo, descrizione) VALUES 
('2','Torneo Estivo di Calcetto', 'Sono aperte le iscrizioni per il torneo di calcio a 5. Squadre da minimo 5 giocatori, premi per i primi classificati. Iscrizioni in segreteria entro il 20.'),
('2','Promo Abbonamento Annuale', 'Sconto del 20% se rinnovi o sottoscrivi un abbonamento annuale entro la fine del mese. Non perdere l’occasione di allenarti a un prezzo vantaggioso!'),
('2','Nuovo Corso di Pilates', 'Da settembre parte il nuovo corso di Pilates: ogni martedì e giovedì alle 18:30. Migliora postura e flessibilità con i nostri istruttori certificati.'),
('2','Visita Nutrizionale', 'Prenota il tuo primo controllo gratuito con il nutrizionista del centro. Disponibilità limitata per questo venerdì, contatta la reception per fissare l’orario.'),
('2','Chiusura per Festività', 'Il centro sportivo rimarrà chiuso per l’intera giornata del 15 agosto. Le attività riprenderanno regolarmente il giorno successivo con i soliti orari.')
;


