-- Aquest script NOMÉS s'executa la primera vegada que es crea el contenidor.
-- Si es vol recrear les taules de nou cal esborrar el contenidor, o bé les dades del contenidor
-- és a dir, 
-- esborrar el contingut de la carpeta db_data 
-- o canviant el nom de la carpeta, però atenció a no pujar-la a git


-- És un exemple d'script per crear una base de dades i una taula
-- i afegir-hi dades inicials

-- Si creem la BBDD aquí podem control·lar la codificació i el collation
-- en canvi en el docker-compose no podem especificar el collation ni la codificació

-- Per assegurar-nes de que la codificació dels caràcters d'aquest script és la correcta
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS incidencia
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Donem permisos a l'usuari 'usuari' per accedir a la base de dades 'persones'
-- sinó, aquest usuari no podrà veure la base de dades i no podrà accedir a les taules
GRANT ALL PRIVILEGES ON incidencia.* TO 'usuari'@'%';
FLUSH PRIVILEGES;


-- Després de crear la base de dades, cal seleccionar-la per treballar-hi
USE incidencia;


CREATE TABLE Tipus_Incidencia (
    ID INT PRIMARY KEY,
    Tipus_de_Incidencia VARCHAR(100) NOT NULL
);

CREATE TABLE Estat (
    ID INT PRIMARY KEY,
    Estat VARCHAR(50) NOT NULL
);

CREATE TABLE Departament (
    ID INT PRIMARY KEY,
    Nom_Departament VARCHAR(100) NOT NULL
);

CREATE TABLE Empleats (
    DNI VARCHAR(20) PRIMARY KEY,
    DEP VARCHAR(20) NOT NULL
    Rol VARCHAR(50) NOT NULL
    FOREIGN KEY ()
);

CREATE TABLE Rol(
    ID INT PRIMARY KEY,
    
)

CREATE TABLE usuari(
    DNI VARCHAR(20) PRIMARY KEY,
    Nom VARCHAR(20) NOT NULL
)

CREATE TABLE Incidencies (
    ID INT PRIMARY KEY,
    Tipus INT NOT NULL,
    Estat INT NOT NULL,
    Empleat VARCHAR(20) NOT NULL,
    Numero_PC VARCHAR(50),
    Descripcio TEXT,
    Fecha DATE,
    FOREIGN KEY (Tipus) REFERENCES Tipus_Incidencia(ID),
    FOREIGN KEY (Estat) REFERENCES Estat(ID),
    FOREIGN KEY (Empleat) REFERENCES Empleats(DNI)
    FOREIGN KEY (Departament) REFERENCES Departament(ID)
    FOREIGN KEY (usuari) REFERENCES usuari(DNI)
);



INSERT INTO `Estat` (`ID`, `Estat`) VALUES
(1,	'No Fet'),
(2,	'En Proces'),
(3,	'Fet');


INSERT INTO `Tipus_Incidencia` (`ID`, `Tipus_de_Incidencia`) VALUES
(1,	'Hardware'),
(2,	'Software');
