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
    data DATE NOT NULL,
    ora_inizio TIME NOT NULL,
    ora_fine TIME NOT NULL,
    prezzo DECIMAL(10, 2) NOT NULL,

    FOREIGN KEY (utente) REFERENCES Utente(id) ON DELETE CASCADE,
    FOREIGN KEY (numero_campo, tipo_campo) REFERENCES Campo(numero, tipo) ON DELETE CASCADE
);


INSERT INTO Utente (nome, cognome, email, data_nascita, password, ruolo) VALUES
('User', 'User', 'user@email.com', '1990-01-01', '$2y$10$Br3zzgw8yid4tWA906YI8OPGRaeAjAU6GFjVi9GHdVu6GA2MXY00m', 'Cliente'),
('Admin', 'Admin', 'admin@email.com', '1985-05-10', '$2y$10$7z0CA06KVMGqMUCaD8HfAOP.wHDdk6pkcyCN8r3DTK5IbCcCOOVMK', 'Admin');
-- psw di admin: Admin
-- psw di user: User

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


-- -- Prenotazione per Tennis (Campo 1, 2 o 3)
-- INSERT INTO prenotazione (utente, numero_campo, tipo_campo, data, ora_inizio, ora_fine, prezzo) 
-- VALUES (1, 3, 'Tennis', '2024-06-15', '8:00:00', '9:30:00', 12.00);

-- -- Prenotazione per Calcio5 (Campo 1 o 2)
-- INSERT INTO prenotazione (utente, numero_campo, tipo_campo, data, ora_inizio, ora_fine, prezzo) 
-- VALUES (2, 1, 'Calcio5', '2024-06-15', '9:30:00', '11:00:00', 20.00);

-- -- Prenotazione per Basket (Solo Campo 1)
-- INSERT INTO prenotazione (utente, numero_campo, tipo_campo, data, ora_inizio, ora_fine, prezzo) 
-- VALUES (3, 1, 'Basket', '2024-06-16', '11:00:00', '12:30:00', 10.00);


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