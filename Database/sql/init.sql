USE dbPHP;

-- 1. Tabella Utenti
CREATE TABLE Utenti (
    Utente_Id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    pwd TEXT NOT NULL,
    materia VARCHAR(20)
);

-- 2. Tabella Argomenti
CREATE TABLE Argomenti (
    Categoria_Id INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(30) NOT NULL
);

-- 3. Tabella Classi
CREATE TABLE Classi (
    Classe_Id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    link VARCHAR(32) UNIQUE NOT NULL,
    materia VARCHAR(20) NOT NULL,
    Id_Utente_Respo INT NOT NULL,
    FOREIGN KEY (Id_Utente_Respo) REFERENCES Utenti(Utente_Id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

-- 4. Tabella Videogiochi
CREATE TABLE Videogiochi (
    Videogioco_Id INT PRIMARY KEY AUTO_INCREMENT,
    titolo VARCHAR(160) NOT NULL,
    desc_breve VARCHAR(160) NOT NULL,
    descrizione VARCHAR(500) NOT NULL,
    num_monete INT DEFAULT 0 NOT NULL,
    link VARCHAR(30) NOT NULL,
    CHECK(num_monete >= 0) 
);

-- 5. Tabella Immagini
CREATE TABLE Immagini (
    Immagine_Id INT PRIMARY KEY AUTO_INCREMENT,
    valore VARCHAR(100) NOT NULL
);

-- 6. Tabella di Giunzione: Imm_Videog (Videogiochi <-> Immagini)
CREATE TABLE Imm_Videog (
    Id_Videogioco INT NOT NULL,
    Id_Immagine INT NOT NULL,
    PRIMARY KEY (Id_Videogioco, Id_Immagine),
    FOREIGN KEY (Id_Videogioco) REFERENCES Videogiochi(Videogioco_Id)
        ON DELETE CASCADE,
    FOREIGN KEY (Id_Immagine) REFERENCES Immagini(Immagine_Id)
        ON DELETE CASCADE
);

-- 7. Tabella di Giunzione: Contiene (Videogiochi <-> Argomenti)
CREATE TABLE Contiene (
    Id_Videogioco INT NOT NULL,
    Id_Argomento INT NOT NULL,
    PRIMARY KEY (Id_Videogioco, Id_Argomento),
    FOREIGN KEY (Id_Videogioco) REFERENCES Videogiochi(Videogioco_Id)
        ON DELETE CASCADE,
    FOREIGN KEY (Id_Argomento) REFERENCES Argomenti(Categoria_Id)
        ON DELETE CASCADE
);

-- 8. Tabella di Giunzione: Appartiene (Videogiochi <-> Classi)
CREATE TABLE Appartiene (
    Id_Videogioco INT NOT NULL,
    Id_Classe INT NOT NULL,
    PRIMARY KEY (Id_Videogioco, Id_Classe),
    FOREIGN KEY (Id_Videogioco) REFERENCES Videogiochi(Videogioco_Id)
        ON DELETE CASCADE,
    FOREIGN KEY (Id_Classe) REFERENCES Classi(Classe_Id)
        ON DELETE CASCADE
);

-- 9. Tabella di Giunzione: Frequenta (Utenti <-> Classi)
CREATE TABLE Frequenta (
    Id_Utente INT NOT NULL,
    Id_Classe INT NOT NULL,
    PRIMARY KEY (Id_Utente, Id_Classe),
    FOREIGN KEY (Id_Utente) REFERENCES Utenti(Utente_Id)
        ON DELETE CASCADE,
    FOREIGN KEY (Id_Classe) REFERENCES Classi(Classe_Id)
        ON DELETE CASCADE
);

-- 10. Tabella di Giunzione: Gioca (Utenti <-> Videogiochi)
CREATE TABLE Gioca (
    Id_Utente INT NOT NULL,
    Id_Videogioco INT NOT NULL,
    moneteOttenute INT NOT NULL,
    PRIMARY KEY (Id_Utente, Id_Videogioco),
    FOREIGN KEY (Id_Utente) REFERENCES Utenti(Utente_Id)
        ON DELETE CASCADE,
    FOREIGN KEY (Id_Videogioco) REFERENCES Videogiochi(Videogioco_Id)
        ON DELETE CASCADE,
    CHECK(moneteOttenute >= 0)
);