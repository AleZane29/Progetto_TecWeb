USE DATABASE alzanell;
DROP TABLE IF EXISTS Campo;
DROP TABLE IF EXISTS TipoCampo;
DROP TABLE IF EXISTS Prenotazione;
DROP TABLE IF EXISTS Utente;

CREATE TABLE Utente (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
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
    data_ DATE NOT NULL,
    ora_inizio DATETIME NOT NULL,
    ora_fine DATETIME NOT NULL,

    FOREIGN KEY (utente) REFERENCES Utente(id) ON DELETE CASCADE,
    FOREIGN KEY (numero_campo, tipo_campo) REFERENCES Campo(numero, tipo) ON DELETE CASCADE
);


INSERT INTO Utente (nome, cognome, email, data_nascita, password, ruolo) VALUES
('User', 'User', 'user@email.com', '1990-01-01', 'user', 'Cliente'),
('Admin', 'Admin', 'admin@email.com', '1985-05-10', 'admin', 'Admin');

INSERT INTO TipoCampo (nome, costo_orario) VALUES
('Tennis', 10),
('Basket', 12),
('Calcio5', 20);

INSERT INTO Campo (numero, tipo) VALUES
(1, 'Tennis'),
(2, 'Tennis'),
(3, 'Tennis'),
(1, 'Calcio5'),
(1, 'Basket'),
(2, 'Basket');



-- CREATE TABLE Prenotazione (
--     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
--     utente INTEGER NOT NULL,
--     numero_campo INTEGER NOT NULL,
--     tipo_campo VARCHAR(20) NOT NULL,
--     dataora_inizio DATETIME NOT NULL,
--     dataora_fine DATETIME NOT NULL,

--     FOREIGN KEY (utente) REFERENCES Utente(id) ON DELETE CASCADE,
--     FOREIGN KEY (numero_campo, tipo_campo) REFERENCES Campo(numero, tipo) ON DELETE CASCADE
-- );