USE Project_WorkDB;
CREATE TABLE Attributi_Articoli (
    Attributo_Id INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(100) NOT NULL,
    PRIMARY KEY (Attributo_Id)
);

CREATE TABLE Articoli_Regionali (
    Codice_ArReg VARCHAR(50) NOT NULL,
    Descrizione VARCHAR(255) NULL,
    PRIMARY KEY (Codice_ArReg)
);

CREATE TABLE Operatori_Economici (
    Ragione_Sociale VARCHAR(150) NOT NULL,
    PRIMARY KEY (Ragione_Sociale)
);

CREATE TABLE Armadi (
    Armadio_Id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (Armadio_Id)
);

CREATE TABLE Tipologie (
    Tipologia_Id INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(100) NOT NULL UNIQUE,
    Descrizione VARCHAR(255) NOT NULL,
    PRIMARY KEY (Tipologia_Id)
);


CREATE TABLE Articoli_Operatori_Economici (
    Codice_ArOpEc VARCHAR(50) NOT NULL,
    Descrizione VARCHAR(255) NULL,
    Ragione_Sociale VARCHAR(150) NOT NULL,
    PRIMARY KEY (Codice_ArOpEc),
    FOREIGN KEY (Ragione_Sociale) REFERENCES Operatori_Economici(Ragione_Sociale) ON DELETE CASCADE
);

CREATE TABLE Scaffali (
    Numero INT NOT NULL AUTO_INCREMENT,
    Armadio_Id INT NOT NULL,
    PRIMARY KEY (Numero, Armadio_Id),
    FOREIGN KEY (Armadio_Id) REFERENCES Armadi(Armadio_Id) ON DELETE CASCADE
);

CREATE TABLE Articoli (
    Articolo_Id INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    Codice_ArReg VARCHAR(50),
    Codice_ArOpEc VARCHAR(50),
    Tipologia_Id INT NOT NULL,
    PRIMARY KEY (Articolo_Id),
    FOREIGN KEY (Codice_ArReg) REFERENCES Articoli_Regionali(Codice_ArReg),
    FOREIGN KEY (Codice_ArOpEc) REFERENCES Articoli_Operatori_Economici(Codice_ArOpEc),
    FOREIGN KEY (Tipologia_Id) REFERENCES Tipologie(Tipologia_Id) ON DELETE CASCADE
);


CREATE TABLE Attributi_Associati (
    Articolo_Id INT NOT NULL,
    Attributo_Id INT NOT NULL,
    Valore VARCHAR(255) NOT NULL,
    PRIMARY KEY (Articolo_Id, Attributo_Id),
    FOREIGN KEY (Articolo_Id) REFERENCES Articoli(Articolo_Id) ON DELETE CASCADE,
    FOREIGN KEY (Attributo_Id) REFERENCES Attributi_Articoli(Attributo_Id) ON DELETE CASCADE
);

CREATE TABLE Articoli_Scaffali (
    Articolo_Id INT NOT NULL,
    Numero INT NOT NULL,
    Armadio_Id INT NOT NULL,
    Quantita INT NOT NULL,
    PRIMARY KEY (Articolo_Id, Numero, Armadio_Id),
    FOREIGN KEY (Articolo_Id) REFERENCES Articoli(Articolo_Id) ON DELETE CASCADE,
    FOREIGN KEY (Numero, Armadio_Id) REFERENCES Scaffali(Numero, Armadio_Id) ON DELETE CASCADE,
    CHECK (Quantita >= 0),
);
CREATE TABLE Log_Modifiche_Quantita (
    Log_Id INT NOT NULL AUTO_INCREMENT,
    Data_Ora_Modifica DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Articolo_Id INT NOT NULL,
    Numero_Scaffale INT NOT NULL,
    Armadio_Id INT NOT NULL,
    QtaPrecedente INT NOT NULL,
    QtaAggiornata INT NOT NULL,
    Attributi TEXT NOT NULL,
    PRIMARY KEY (Log_Id)
);

DELIMITER //

CREATE TRIGGER trg_log_modifica_quantita
AFTER UPDATE ON Articoli_Scaffali
FOR EACH ROW
BEGIN
    DECLARE v_attributi TEXT;

    -- Eseguiamo il log SOLO se c'è stata una reale variazione nelle quantità
    IF (OLD.Quantita <> NEW.Quantita) THEN
        
        SELECT GROUP_CONCAT(CONCAT(attr.Nome, ': ', assoc.Valore) SEPARATOR ' | ')
        INTO v_attributi
        FROM Attributi_Associati AS assoc
        JOIN Attributi_Articoli attr ON assoc.Attributo_Id = attr.Attributo_Id
        WHERE assoc.Articolo_Id = NEW.Articolo_Id;

        IF v_attributi IS NULL THEN
            SET v_attributi = 'Nessun attributo associato';
        END IF;

        -- Inseriamo il record nello storico
        INSERT INTO Log_Modifiche_Quantita (
            Articolo_Id,
            Numero_Scaffale,
            Armadio_Id,
            QtaPrecedente,
            QtaAggiornata,
            Attributi
        ) VALUES (
            NEW.Articolo_Id,
            NEW.Numero,
            NEW.Armadio_Id,
            OLD.Quantita,
            NEW.Quantita,
            v_attributi
        );
        
    END IF;
END;
//

DELIMITER ;

CREATE TRIGGER trg_log_inserimento_articoli
AFTER INSERT ON Articoli_Scaffali
FOR EACH ROW
BEGIN
    DECLARE v_attributi TEXT;

    SELECT GROUP_CONCAT(CONCAT(attr.Nome, ': ', assoc.Valore) SEPARATOR ' | ')
    INTO v_attributi
    FROM Attributi_Associati assoc
    JOIN Attributi_Articoli attr ON assoc.Attributo_Id = attr.Attributo_Id
    WHERE assoc.Articolo_Id = NEW.Articolo_Id;

    IF v_attributi IS NULL THEN
        SET v_attributi = 'Nessun attributo associato';
    END IF;

    INSERT INTO Log_Modifiche_Quantita (
        Articolo_Id,
        Numero_Scaffale,
        Armadio_Id,
        QtaPrecedente,
        QtaAggiornata,
        Attributi
    ) VALUES (
        NEW.Articolo_Id,
        NEW.Numero,
        NEW.Armadio_Id,
        0,
        NEW.Quantita,
        v_attributi
    );
END;
//

DELIMITER ;
