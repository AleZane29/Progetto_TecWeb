CREATE TABLE utente (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    data_nascita DATE NOT NULL,
    password TEXT NOT NULL,
    ruolo ENUM('Cliente', 'Admin') DEFAULT 'Cliente'
);

CREATE TABLE tipocampo (
    nome VARCHAR(20) PRIMARY KEY,
    costo_orario INTEGER NOT NULL CHECK(costo_orario > 0)
);

CREATE TABLE campo (
    numero SERIAL NOT NULL,
    tipo VARCHAR(20),
    PRIMARY KEY (numero, tipo),
    FOREIGN KEY (tipo) REFERENCES tipocampo(nome) ON DELETE CASCADE
);

CREATE EXTENSION IF NOT EXISTS btree_gist;

CREATE TABLE prenotazione (
    id SERIAL PRIMARY KEY,
    utente VARCHAR(50) NOT NULL,
    numero_campo INTEGER NOT NULL,
    tipo_campo VARCHAR(20) NOT NULL,
    dataora_inizio TIMESTAMP NOT NULL,
    dataora_fine TIMESTAMP NOT NULL,

    EXCLUDE USING gist (
        numero_campo WITH =,
        tipo_campo WITH =,
        tstzrange(dataora_inizio, dataora_fine) WITH &&
    ),

    FOREIGN KEY (utente) REFERENCES utente(username) ON DELETE CASCADE,
    FOREIGN KEY (numero_campo, tipo_campo) REFERENCES campo(numero, tipo) ON DELETE CASCADE
);


INSERT INTO utente (username, nome, cognome, email, data_nascita, password, ruolo) VALUES
('user', 'User', 'User', 'user@email.com', '1990-01-01', 'password', 'Cliente'),
('admin', 'Admin', 'Admin', 'admin@email.com', '1985-05-10', 'password', 'Admin');

INSERT INTO tipocampo (nome, costo_orario) VALUES
('Tennis', 10),
('Beach volley', 20),
('Padel', 12);

INSERT INTO campo (numero, tipo) VALUES
(1, 'Tennis'),
(2, 'Tennis'),
(3, 'Tennis'),
(1, 'Padel'),
(1, 'Beach volley'),
(2, 'Beach volley');
