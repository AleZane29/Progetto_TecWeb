USE alzanell;
DROP TABLE IF EXISTS Campo;
DROP TABLE IF EXISTS TipoCampo;
DROP TABLE IF EXISTS Prenotazione;
DROP TABLE IF EXISTS Utente;

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
    numero INT NOT NULL AUTO_INCREMENT NOT NULL,
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

INSERT INTO `prenotazione` (`id`, `utente`, `numero_campo`, `tipo_campo`, `data`, `ora_inizio`, `ora_fine`, `prezzo`) VALUES 
(NULL, '1', '1', 'Tennis', '2026-02-10', '08:00:00', '09:30:00', '25');
(NULL, '1', '2', 'Tennis', '2026-02-11', '08:00:00', '09:30:00', '25');
(NULL, '1', '1', 'Basket', '2026-02-11', '11:00:00', '12:30:00', '70');
(NULL, '1', '1', 'Calcio5', '2026-02-11', '11:00:00', '12:30:00', '60');
