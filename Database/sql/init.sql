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
    Quantita INT NOT NULL DEFAULT 0,
    Qt_Usata INT NOT NULL DEFAULT 0,
    PRIMARY KEY (Articolo_Id, Numero, Armadio_Id),
    FOREIGN KEY (Articolo_Id) REFERENCES Articoli(Articolo_Id) ON DELETE CASCADE,
    FOREIGN KEY (Numero, Armadio_Id) REFERENCES Scaffali(Numero, Armadio_Id) ON DELETE CASCADE,
    CHECK (Quantita >= 0),
    CHECK (Qt_Usata >= 0)
);

-- Tabella Log modificata: aggiunti i campi per la Qt_Usata
CREATE TABLE Log_Modifiche_Quantita (
    Log_Id INT NOT NULL AUTO_INCREMENT,
    Data_Ora_Modifica DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Nome_Articolo VARCHAR(255) NOT NULL,
    Nome_Tipologia VARCHAR(100) NOT NULL,
    Numero_Scaffale INT NOT NULL,
    Armadio_Id INT NOT NULL,
    QtaPrecedente INT NOT NULL,
    QtaAggiornata INT NOT NULL,
    QtaUsataPrecedente INT NOT NULL DEFAULT 0,
    QtaUsataAggiornata INT NOT NULL DEFAULT 0,
    Attributi TEXT NOT NULL,
    PRIMARY KEY (Log_Id)
);

DELIMITER //

CREATE TRIGGER trg_log_modifica_quantita
AFTER UPDATE ON Articoli_Scaffali
FOR EACH ROW
BEGIN
    DECLARE v_attributi TEXT;
    DECLARE v_nome_articolo VARCHAR(255);
    DECLARE v_nome_tipologia VARCHAR(100);

    -- Eseguiamo il log se c'è stata una variazione nella quantità a scaffale OPPURE nella quantità usata
    IF (OLD.Quantita <> NEW.Quantita OR OLD.Qt_Usata <> NEW.Qt_Usata) THEN
        
        SELECT a.Nome, t.Nome 
        INTO v_nome_articolo, v_nome_tipologia
        FROM Articoli a
        JOIN Tipologie t ON a.Tipologia_Id = t.Tipologia_Id
        WHERE a.Articolo_Id = NEW.Articolo_Id;

        SELECT GROUP_CONCAT(CONCAT(attr.Nome, ': ', assoc.Valore) SEPARATOR ' | ')
        INTO v_attributi
        FROM Attributi_Associati AS assoc
        JOIN Attributi_Articoli attr ON assoc.Attributo_Id = attr.Attributo_Id
        WHERE assoc.Articolo_Id = NEW.Articolo_Id;

        IF v_attributi IS NULL THEN
            SET v_attributi = 'Nessun attributo associato';
        END IF;

        INSERT INTO Log_Modifiche_Quantita (
            Nome_Articolo,
            Nome_Tipologia,
            Numero_Scaffale,
            Armadio_Id,
            QtaPrecedente,
            QtaAggiornata,
            QtaUsataPrecedente,
            QtaUsataAggiornata,
            Attributi
        ) VALUES (
            v_nome_articolo,
            v_nome_tipologia,
            NEW.Numero,
            NEW.Armadio_Id,
            OLD.Quantita,
            NEW.Quantita,
            OLD.Qt_Usata,
            NEW.Qt_Usata,
            v_attributi
        );
        
    END IF;
END;
//

DELIMITER ;

DELIMITER //

CREATE TRIGGER trg_log_inserimento_articoli
AFTER INSERT ON Articoli_Scaffali
FOR EACH ROW
BEGIN
    DECLARE v_attributi TEXT;
    DECLARE v_nome_articolo VARCHAR(255);
    DECLARE v_nome_tipologia VARCHAR(100);

    SELECT a.Nome, t.Nome 
    INTO v_nome_articolo, v_nome_tipologia
    FROM Articoli a
    JOIN Tipologie t ON a.Tipologia_Id = t.Tipologia_Id
    WHERE a.Articolo_Id = NEW.Articolo_Id;

    SELECT GROUP_CONCAT(CONCAT(attr.Nome, ': ', assoc.Valore) SEPARATOR ' | ')
    INTO v_attributi
    FROM Attributi_Associati assoc
    JOIN Attributi_Articoli attr ON assoc.Attributo_Id = attr.Attributo_Id
    WHERE assoc.Articolo_Id = NEW.Articolo_Id;

    IF v_attributi IS NULL THEN
        SET v_attributi = 'Nessun attributo associato';
    END IF;

    INSERT INTO Log_Modifiche_Quantita (
        Nome_Articolo,
        Nome_Tipologia,
        Numero_Scaffale,
        Armadio_Id,
        QtaPrecedente,
        QtaAggiornata,
        QtaUsataPrecedente,
        QtaUsataAggiornata,
        Attributi
    ) VALUES (
        v_nome_articolo,
        v_nome_tipologia,
        NEW.Numero,
        NEW.Armadio_Id,
        0,
        NEW.Quantita,
        0,
        NEW.Qt_Usata,
        v_attributi
    );
END;
//

DELIMITER ;
-- =========================================================================
-- 1. POPOLAMENTO TIPOLOGIE (Univoche)
-- =========================================================================
INSERT IGNORE INTO Tipologie (Tipologia_Id, Nome, Descrizione) VALUES 
(1, 'Vestiario', 'Abbigliamento generale di servizio'),
(2, 'Buffetteria e accessori', 'Accessori, cinturoni e fondine'),
(3, 'Dispositivi', 'Strumenti operativi e di sicurezza'),
(4, 'Calzature', 'Scarpe, stivali e anfibi');

-- =========================================================================
-- 2. CREAZIONE DI 6 ARMADI
-- =========================================================================
INSERT IGNORE INTO Armadi (Armadio_Id) VALUES 
(1), (2), (3), (4), (5), (6);

-- =========================================================================
-- 3. CREAZIONE DI 1 SCAFFALE PER OGNI ARMADIO
-- =========================================================================
-- Lo scaffale numero 1 per gli armadi da 1 a 6
INSERT IGNORE INTO Scaffali (Numero, Armadio_Id) VALUES 
(1, 1), 
(1, 2), 
(1, 3), 
(1, 4), 
(1, 5), 
(1, 6);

INSERT IGNORE INTO Attributi_Articoli (Attributo_Id, Nome) VALUES 
(1, 'Colore'),
(2, 'Stagione'),
(3, 'Materiale'),
(4, 'Genere'),
(5, 'Maniche'),
(6, 'Alamari'),
(7, 'Modello / Tipo'),
(8, 'Categoria / Ruolo'),
(9, 'Rifrangente');

-- =========================================================================
-- 3. CREAZIONE DI TUTTI GLI ARTICOLI (Senza codici)
-- =========================================================================
INSERT IGNORE INTO Articoli (Articolo_Id, Nome, Codice_ArReg, Codice_ArOpEc, Tipologia_Id) VALUES 
-- VESTIARIO (FORINT & KAAMA)
(1, 'Cappotto', NULL, NULL, 1),
(2, 'Mantella', NULL, NULL, 1),
(3, 'Giubba', NULL, NULL, 1),
(4, 'Giubba', NULL, NULL, 1),
(5, 'Giubba', NULL, NULL, 1),
(6, 'Giubba', NULL, NULL, 1),
(7, 'Giubba', NULL, NULL, 1),
(8, 'Giubba', NULL, NULL, 1),
(9, 'Maglione', NULL, NULL, 1),
(10, 'Maglione', NULL, NULL, 1),
(11, 'Maglione', NULL, NULL, 1),
(12, 'Maglione', NULL, NULL, 1),
(13, 'Maglione', NULL, NULL, 1),
(14, 'Maglione', NULL, NULL, 1),
(15, 'Maglione', NULL, NULL, 1),
(16, 'Pullover', NULL, NULL, 1),
(17, 'Pullover', NULL, NULL, 1),
(18, 'Camicia', NULL, NULL, 1),
(19, 'Maglietta', NULL, NULL, 1),
(20, 'Polo', NULL, NULL, 1),
(21, 'Polo', NULL, NULL, 1),
(22, 'Gonna', NULL, NULL, 1),
(23, 'Gonna', NULL, NULL, 1),
(24, 'Pantaloni', NULL, NULL, 1),
(25, 'Pantaloni', NULL, NULL, 1),
(26, 'Cravatta', NULL, NULL, 1),
(27, 'Calze', NULL, NULL, 1),
(28, 'Calze', NULL, NULL, 1),
(29, 'Collant', NULL, NULL, 1),
(30, 'Collant', NULL, NULL, 1),
(31, 'Paracollo', NULL, NULL, 1),
(32, 'Berretto', NULL, NULL, 1),
(33, 'Berretto', NULL, NULL, 1),
(34, 'Berretto', NULL, NULL, 1),
(35, 'Berretto', NULL, NULL, 1),
(36, 'Cuffia', NULL, NULL, 1),
(37, 'Casco', NULL, NULL, 1),
(38, 'Foderina', NULL, NULL, 1),
(39, 'Guanti', NULL, NULL, 1),
(40, 'Guanti', NULL, NULL, 1),
(41, 'Guanti', NULL, NULL, 1),
(42, 'Guanti', NULL, NULL, 1),
(43, 'Kit Gilet', NULL, NULL, 1),
(44, 'Foderina', NULL, NULL, 1),
(45, 'Foderina', NULL, NULL, 1),
(46, 'Manicotti', NULL, NULL, 1),
(47, 'Gilet', NULL, NULL, 1),
(48, 'Pettorina', NULL, NULL, 1),
(49, 'Pettorina', NULL, NULL, 1),
(50, 'Fascia', NULL, NULL, 1),
(51, 'Giacca', NULL, NULL, 1),
(52, 'Giaccone', NULL, NULL, 1),
(53, 'Giubbetto', NULL, NULL, 1),
(54, 'Impermeabile', NULL, NULL, 1),
(55, 'Polo', NULL, NULL, 1),
(56, 'Polo', NULL, NULL, 1),
(57, 'Pantaloni', NULL, NULL, 1),
(58, 'Pantaloni', NULL, NULL, 1),
(59, 'Pantaloni', NULL, NULL, 1),
(60, 'Calze', NULL, NULL, 1),
(61, 'Copripantaloni', NULL, NULL, 1),
(62, 'Giaccone', NULL, NULL, 1),
(63, 'Giubbetto', NULL, NULL, 1),
(64, 'Pantaloni', NULL, NULL, 1),
(65, 'Pantaloni', NULL, NULL, 1),
(66, 'Guanti', NULL, NULL, 1),
(67, 'Guanti', NULL, NULL, 1),
(68, 'Paracollo', NULL, NULL, 1),

-- BUFFETTERIA E ACCESSORI (FORINT & KAAMA)
(69, 'Borsone', NULL, NULL, 2),
(70, 'Cintura', NULL, NULL, 2),
(71, 'Cinturone', NULL, NULL, 2),
(72, 'Cordelline', NULL, NULL, 2),
(73, 'Distanziale', NULL, NULL, 2),
(74, 'Fischietto', NULL, NULL, 2),
(75, 'Fondina', NULL, NULL, 2),
(76, 'Portamanette', NULL, NULL, 2),
(77, 'Portamanette', NULL, NULL, 2),
(78, 'Portacaricatore', NULL, NULL, 2),
(79, 'Tracolla', NULL, NULL, 2),
(80, 'Alamari', NULL, NULL, 2),
(81, 'Crest', NULL, NULL, 2),
(82, 'Distintivo', NULL, NULL, 2),
(83, 'Matricola', NULL, NULL, 2),
(84, 'Mostrine', NULL, NULL, 2),
(85, 'Piastrine', NULL, NULL, 2),
(86, 'Piastrine', NULL, NULL, 2),
(87, 'Placca', NULL, NULL, 2),
(88, 'Placca', NULL, NULL, 2),
(89, 'Targhetta', NULL, NULL, 2),
(90, 'Distintivi', NULL, NULL, 2),
(91, 'Distintivi', NULL, NULL, 2),
(92, 'Distintivi', NULL, NULL, 2),
(93, 'Barretta', NULL, NULL, 2),
(94, 'Galloncini', NULL, NULL, 2),
(95, 'Galloncini', NULL, NULL, 2),
(96, 'Galloncini', NULL, NULL, 2),
(97, 'Greca', NULL, NULL, 2),
(98, 'Ramoscelli', NULL, NULL, 2),
(99, 'Soggolo', NULL, NULL, 2),
(100, 'Soggolo', NULL, NULL, 2),
(101, 'Soggolo', NULL, NULL, 2),
(102, 'Stella', NULL, NULL, 2),
(103, 'Torre', NULL, NULL, 2),
(104, 'Tubolari', NULL, NULL, 2),
(105, 'Casco', NULL, NULL, 2),
(106, 'Marsupio', NULL, NULL, 2),
(107, 'Casco', NULL, NULL, 2),
(108, 'Casco', NULL, NULL, 2),

-- DISPOSITIVI (FREESHOT, DEFENCE SYSTEM, FORINT)
(109, 'Manette', NULL, NULL, 3),
(110, 'Paletta', NULL, NULL, 3),
(111, 'Bastone', NULL, NULL, 3),
(112, 'Spray', NULL, NULL, 3),
(113, 'Ricarica', NULL, NULL, 3),

-- CALZATURE (VOLTA)
(114, 'Scarpe', NULL, NULL, 4),
(115, 'Scarpe', NULL, NULL, 4),
(116, 'Scarpe', NULL, NULL, 4),
(117, 'Scarpe', NULL, NULL, 4),
(118, 'Scarpe', NULL, NULL, 4),
(119, 'Scarpe', NULL, NULL, 4),
(120, 'Scarpe', NULL, NULL, 4),
(121, 'Scarpe', NULL, NULL, 4),
(122, 'Stivaletti', NULL, NULL, 4),
(123, 'Stivaletti', NULL, NULL, 4),
(124, 'Scarponcini', NULL, NULL, 4),
(125, 'Scarpe', NULL, NULL, 4),
(126, 'Stivali', NULL, NULL, 4),
(127, 'Stivali', NULL, NULL, 4),
(128, 'Anfibi', NULL, NULL, 4),
(129, 'Anfibi', NULL, NULL, 4),
(130, 'Anfibi', NULL, NULL, 4),
(131, 'Stivali', NULL, NULL, 4),
(132, 'Anfibi', NULL, NULL, 4);

-- =========================================================================
-- 4. ASSOCIAZIONE DEGLI ATTRIBUTI
-- =========================================================================
INSERT IGNORE INTO Attributi_Associati (Articolo_Id, Attributo_Id, Valore) VALUES 
-- Cappotto cat. D
(1, 1, 'Blu'), (1, 8, 'Dirigenti / Cat. D'), (1, 7, 'Rappresentanza'),
-- Mantella blu
(2, 1, 'Blu'),
-- Giubba estiva blu
(3, 2, 'Estiva'), (3, 1, 'Blu'),
-- Giubba estiva blu con alamari (Cat. D)
(4, 2, 'Estiva'), (4, 1, 'Blu'), (4, 6, 'Si'), (4, 8, 'Dirigenti / Cat. D'),
-- Giubba estiva grigia con alamari (Cat. D)
(5, 2, 'Estiva'), (5, 1, 'Grigio'), (5, 6, 'Si'), (5, 8, 'Dirigenti / Cat. D'),
-- Giubba invernale blu
(6, 2, 'Invernale'), (6, 1, 'Blu'),
-- Giubba invernale blu con alamari (Cat. D)
(7, 2, 'Invernale'), (7, 1, 'Blu'), (7, 6, 'Si'), (7, 8, 'Dirigenti / Cat. D'),
-- Giubba invernale grigia con alamari (Cat. D)
(8, 2, 'Invernale'), (8, 1, 'Grigio'), (8, 6, 'Si'), (8, 8, 'Dirigenti / Cat. D'),
-- Maglione a collo alto, blu
(9, 7, 'Collo alto'), (9, 1, 'Blu'),
-- Maglione da sottogiubba, con maniche, 100% lana, blu
(10, 7, 'Sottogiubba'), (10, 5, 'Lunghe'), (10, 3, 'Lana 100%'), (10, 1, 'Blu'),
-- Maglione da sottogiubba, con maniche, misto lana, blu
(11, 7, 'Sottogiubba'), (11, 5, 'Lunghe'), (11, 3, 'Misto lana'), (11, 1, 'Blu'),
-- Maglione da sottogiubba, senza maniche, 100% lana, blu
(12, 7, 'Sottogiubba'), (12, 5, 'Senza maniche'), (12, 3, 'Lana 100%'), (12, 1, 'Blu'),
-- Maglione da sottogiubba, senza maniche, misto lana, blu
(13, 7, 'Sottogiubba'), (13, 5, 'Senza maniche'), (13, 3, 'Misto lana'), (13, 1, 'Blu'),
-- Maglione in micropile, blu
(14, 3, 'Micropile'), (14, 1, 'Blu'),
-- Maglione in pile pesante, blu
(15, 3, 'Pile pesante'), (15, 1, 'Blu'),
-- Pullover a “V” in misto cotone, blu
(16, 7, 'Scollo a V'), (16, 3, 'Misto cotone'), (16, 1, 'Blu'),
-- Pullover a “V” in misto lana, blu
(17, 7, 'Scollo a V'), (17, 3, 'Misto lana'), (17, 1, 'Blu'),
-- Camicia a maniche lunghe celeste
(18, 5, 'Lunghe'), (18, 1, 'Celeste'),
-- Maglietta a maniche corte in cotone, blu
(19, 5, 'Corte'), (19, 3, 'Cotone'), (19, 1, 'Blu'),
-- Polo a maniche corte in cotone, blu
(20, 5, 'Corte'), (20, 3, 'Cotone'), (20, 1, 'Blu'),
-- Polo a maniche lunghe in cotone blu
(21, 5, 'Lunghe'), (21, 3, 'Cotone'), (21, 1, 'Blu'),
-- Gonna estiva blu
(22, 2, 'Estiva'), (22, 1, 'Blu'), (22, 4, 'Donna'),
-- Gonna invernale blu
(23, 2, 'Invernale'), (23, 1, 'Blu'), (23, 4, 'Donna'),
-- Pantaloni ordinari lunghi estivi, blu
(24, 7, 'Ordinari'), (24, 2, 'Estivi'), (24, 1, 'Blu'),
-- Pantaloni ordinari lunghi invernali, blu
(25, 7, 'Ordinari'), (25, 2, 'Invernali'), (25, 1, 'Blu'),
-- Cravatta blu
(26, 1, 'Blu'),
-- Calze estive blu
(27, 2, 'Estive'), (27, 1, 'Blu'),
-- Calze invernali blu
(28, 2, 'Invernali'), (28, 1, 'Blu'),
-- Collant estivi
(29, 2, 'Estivi'), (29, 4, 'Donna'),
-- Collant invernali
(30, 2, 'Invernali'), (30, 4, 'Donna'),
-- Paracollo in pile blu
(31, 3, 'Pile'), (31, 1, 'Blu'),
-- Berretto di servizio, estivo, blu
(32, 7, 'Di servizio'), (32, 2, 'Estivo'), (32, 1, 'Blu'),
-- Berretto di servizio, invernale, blu
(33, 7, 'Di servizio'), (33, 2, 'Invernale'), (33, 1, 'Blu'),
-- Berretto ordinario, femminile, calotta bianca
(34, 7, 'Ordinario'), (34, 4, 'Donna'), (34, 1, 'Bianco'),
-- Berretto ordinario, maschile, calotta bianca
(35, 7, 'Ordinario'), (35, 4, 'Uomo'), (35, 1, 'Bianco'),
-- Cuffia blu
(36, 1, 'Blu'),
-- Casco tipo coloniale
(37, 7, 'Coloniale'),
-- Foderina per berretto ordinario
(38, 7, 'Per berretto ordinario'),
-- Guanti bianchi, estivi
(39, 1, 'Bianco'), (39, 2, 'Estivi'),
-- Guanti bianchi, invernali
(40, 1, 'Bianco'), (40, 2, 'Invernali'),
-- Guanti in pelle nera, estivi
(41, 3, 'Pelle'), (41, 1, 'Nero'), (41, 2, 'Estivi'),
-- Guanti in pelle nera, invernali
(42, 3, 'Pelle'), (42, 1, 'Nero'), (42, 2, 'Invernali'),
-- Kit gilet rifrangente
(43, 9, 'Si'),
-- Foderina per berretto ordinario in tessuto rifrangente
(44, 7, 'Per berretto ordinario'), (44, 9, 'Si'),
-- Foderina per berretto di servizio in tessuto rifrangente
(45, 7, 'Per berretto di servizio'), (45, 9, 'Si'),
-- Manicotti rinfrangenti
(46, 9, 'Si'),
-- Gilet rinfrangente
(47, 9, 'Si'),
-- Pettorina
(48, 7, 'Standard'),
-- Pettorina per cani
(49, 7, 'Per cani'),
-- Fascia azzurra
(50, 1, 'Azzurro'),
-- Giacca sahariana blu
(51, 7, 'Sahariana'), (51, 1, 'Blu'),
-- Giaccone blu
(52, 1, 'Blu'),
-- Giubbetto blu
(53, 1, 'Blu'),
-- Impermeabile
(54, 3, 'Impermeabile'),
-- Polo maniche corte in tessuto tecnico blu
(55, 5, 'Corte'), (55, 3, 'Tessuto tecnico'), (55, 1, 'Blu'),
-- Polo maniche lunghe in tessuto tecnico blu
(56, 5, 'Lunghe'), (56, 3, 'Tessuto tecnico'), (56, 1, 'Blu'),
-- Pantaloni corti blu
(57, 7, 'Corti'), (57, 1, 'Blu'),
-- Pantaloni di servizio estivi, blu
(58, 7, 'Di servizio'), (58, 2, 'Estivi'), (58, 1, 'Blu'),
-- Pantaloni di servizio invernali, blu
(59, 7, 'Di servizio'), (59, 2, 'Invernali'), (59, 1, 'Blu'),
-- Calze tecniche
(60, 3, 'Tessuto tecnico'),
-- Copripantaloni impermeabili blu
(61, 3, 'Impermeabile'), (61, 1, 'Blu'),
-- Giaccone tecnico blu
(62, 3, 'Tessuto tecnico'), (62, 1, 'Blu'),
-- Giubbetto tecnico blu
(63, 3, 'Tessuto tecnico'), (63, 1, 'Blu'),
-- Pantaloni tecnici, estivi, blu
(64, 3, 'Tessuto tecnico'), (64, 2, 'Estivi'), (64, 1, 'Blu'),
-- Pantaloni tecnici, invernali, blu
(65, 3, 'Tessuto tecnico'), (65, 2, 'Invernali'), (65, 1, 'Blu'),
-- Guanti tecnici, invernali
(66, 3, 'Tessuto tecnico'), (66, 2, 'Invernali'),
-- Guanti tecnici, estivi
(67, 3, 'Tessuto tecnico'), (67, 2, 'Estivi'),
-- Paracollo tecnico
(68, 3, 'Tessuto tecnico'),

-- Borsone operativo
(69, 7, 'Operativo'),
-- Cintura / Cinturone / Cordelline / Distanziale / Fischietto
(70, 7, 'Standard'),
(71, 7, 'Standard'),
(72, 7, 'Standard'),
(73, 7, 'Standard'),
(74, 7, 'Con catenella'),
-- Fondina di sicurezza ad estrazione rapida in polimero
(75, 7, 'Estrazione rapida'), (75, 3, 'Polimero'),
-- Portamanette aperto
(76, 7, 'Aperto'),
-- Portamanette chiuso
(77, 7, 'Chiuso'),
-- Portacaricatore / Tracolla / Alamari
(78, 7, 'Standard'),
(79, 7, 'Standard'),
(80, 7, 'Coppia'),
-- Crest / Distintivo alla spalla / Matricola / Mostrine
(81, 7, 'Standard'),
(82, 7, 'Alla spalla'),
(83, 7, 'Su velcro 5x5'),
(84, 7, 'Coppia'),
-- Piastrine al petto
(85, 7, 'Al petto'),
-- Piastrine al petto di specialità motociclista
(86, 7, 'Specialità motociclista'),
-- Placca di riconoscimento al petto
(87, 7, 'Riconoscimento al petto'),
-- Placca sul copricapo
(88, 7, 'Sul copricapo'),
-- Targhetta cm 10x2 per logotipo PL
(89, 7, 'Logotipo PL'),
-- Distintivi di metallo (per categoria C), coppia
(90, 3, 'Metallo'), (90, 8, 'Cat. C'),
-- Distintivi di plastica (per categoria C), coppia, su tubolare
(91, 3, 'Plastica'), (91, 8, 'Cat. C'),
-- Distintivi su supporto a velcro
(92, 7, 'Su velcro'),
-- Barretta con rombo in posizione centrale
(93, 7, 'Con rombo'),
-- Galloncini per categoria C
(94, 8, 'Cat. C'),
-- Galloncini per categoria D e Dirigenti
(95, 8, 'Cat. D e Dirigenti'),
-- Galloncini con bordatura in robbio rosso
(96, 7, 'Bordatura robbio rosso'),
-- Greca / Ramoscelli d'alloro incrociati
(97, 7, 'Standard'),
(98, 7, 'Incrociati'),
-- Soggolo nero (Agente, Assistente, Sovrintendente e Ispettore)
(99, 1, 'Nero'), (99, 8, 'Agente, Assistente, Sovrintendente, Ispettore'),
-- Soggolo argentato (Ispettore)
(100, 1, 'Argentato'), (100, 8, 'Ispettore'),
-- Soggolo con treccia o cordoncino (Commissario, Dirigente e Comandante)
(101, 7, 'Treccia/Cordoncino'), (101, 8, 'Commissario, Dirigente, Comandante'),
-- Stella / Torre
(102, 3, 'Metallo'),
(103, 3, 'Metallo'),
-- Tubolari a fondo blu, coppia
(104, 1, 'Blu'),
-- Caschetto da ciclista
(105, 7, 'Da ciclista'),
-- Marsupio impermeabile blu
(106, 3, 'Impermeabile'), (106, 1, 'Blu'),
-- Casco jet
(107, 7, 'Jet'),
-- Casco modulare
(108, 7, 'Modulare'),

-- Manette
(109, 7, 'Standard'),
-- Paletta di segnalazione
(110, 7, 'Segnalazione'),
-- Bastone estensibile
(111, 7, 'Estensibile'),
-- Spray di colore nero
(112, 1, 'Nero'),
-- Bomboletta di ricarica
(113, 7, 'Ricarica per spray'),

-- Scarpa bassa allacciata, da uomo, invernale
(114, 7, 'Bassa allacciata'), (114, 4, 'Uomo'), (114, 2, 'Invernale'),
-- Scarpa bassa allacciata, da uomo, estiva
(115, 7, 'Bassa allacciata'), (115, 4, 'Uomo'), (115, 2, 'Estiva'),
-- Scarpa modello decolté - donna
(116, 7, 'Decolté'), (116, 4, 'Donna'),
-- Scarpa modello mocassino, da donna, invernale
(117, 7, 'Mocassino'), (117, 4, 'Donna'), (117, 2, 'Invernale'),
-- Scarpa modello mocassino, da donna, estiva
(118, 7, 'Mocassino'), (118, 4, 'Donna'), (118, 2, 'Estiva'),
-- Scarpa bassa allacciata, da donna, invernale
(119, 7, 'Bassa allacciata'), (119, 4, 'Donna'), (119, 2, 'Invernale'),
-- Scarpa bassa allacciata, da donna, estiva
(120, 7, 'Bassa allacciata'), (120, 4, 'Donna'), (120, 2, 'Estiva'),
-- Scarpe sportive unisex
(121, 7, 'Sportiva'), (121, 4, 'Unisex'),
-- Stivaletti da uomo invernali
(122, 4, 'Uomo'), (122, 2, 'Invernali'),
-- Stivaletti da donna invernali
(123, 4, 'Donna'), (123, 2, 'Invernali'),
-- Scarponcini di servizio, invernali
(124, 7, 'Di servizio'), (124, 2, 'Invernali'),
-- Scarpe di servizio, estive
(125, 7, 'Di servizio'), (125, 2, 'Estive'),
-- Stivali invernali
(126, 2, 'Invernali'),
-- Stivali estivi
(127, 2, 'Estivi'),
-- Anfibi alti, invernali
(128, 7, 'Alti'), (128, 2, 'Invernali'),
-- Anfibi medio alti, invernali
(129, 7, 'Medio alti'), (129, 2, 'Invernali'),
-- Anfibi estivi
(130, 2, 'Estivi'),
-- Stivali in gomma/materiale polimerico
(131, 3, 'Gomma/Polimero'),
-- Anfibi estivi (Soldini / Fuori convenzione)
(132, 2, 'Estivi'), (132, 7, 'Fuori convenzione');