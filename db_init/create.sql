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


CREATE TABLE `actuacio_de_incidencia` (
  `id_incidencia` int(11) NOT NULL,
  `linia_incidencia` int(11) NOT NULL,
  `descripcio` text NOT NULL,
  `temp_requeit` time NOT NULL,
  `estat_incidencia` int(11) NOT NULL,
  `data_actuacio` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `Departament`
--

CREATE TABLE `Departament` (
  `ID` int(11) NOT NULL,
  `Nom_Departament` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `Departament`
--

INSERT INTO `Departament` (`ID`, `Nom_Departament`) VALUES
(1, 'Informàtica'),
(2, 'Administració'),
(3, 'RRHH'),
(4, 'Logística');

-- --------------------------------------------------------

--
-- Estructura de la taula `Empleat`
--

CREATE TABLE `Empleat` (
  `DNI` varchar(20) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `Rol_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `Empleat`
--

INSERT INTO `Empleat` (`DNI`, `Nom`, `Rol_ID`) VALUES
('12345678A', 'Ermengol', 2),
('12345678A1', 'Ricardo', 2),
('12345678A2', 'Iker', 2),
('12345678A3', 'Pol', 2),
('12345678A4', 'Carlos', 1),
('12345678A5', 'Oscar', 1);

-- --------------------------------------------------------

--
-- Estructura de la taula `Estat`
--

CREATE TABLE `Estat` (
  `ID` int(11) NOT NULL,
  `Estat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `Estat`
--

INSERT INTO `Estat` (`ID`, `Estat`) VALUES
(1, 'No Fet'),
(2, 'En Proces'),
(3, 'Fet');

-- --------------------------------------------------------

--
-- Estructura de la taula `Incidencies`
--

CREATE TABLE `Incidencies` (
  `ID` int(11) NOT NULL,
  `Usuari` varchar(20) NOT NULL,
  `Estat` int(11) NOT NULL DEFAULT 1,
  `Empleat` varchar(20) DEFAULT NULL,
  `Departament` int(11) NOT NULL,
  `Descripcio` text DEFAULT NULL,
  `Prioritat` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `Prioritat`
--

CREATE TABLE `Prioritat` (
  `ID` int(11) NOT NULL,
  `Nivel_de_Prioritat` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `Prioritat`
--

INSERT INTO `Prioritat` (`ID`, `Nivel_de_Prioritat`) VALUES
(1, 'Baix'),
(2, 'Mitja'),
(3, 'Alta');

-- --------------------------------------------------------

--
-- Estructura de la taula `Rol`
--

CREATE TABLE `Rol` (
  `ID` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `Rol`
--

INSERT INTO `Rol` (`ID`, `Nom`) VALUES
(1, 'Responsable'),
(2, 'Tècnic');

-- --------------------------------------------------------

--
-- Estructura de la taula `Usuari`
--

CREATE TABLE `Usuari` (
  `DNI` varchar(20) NOT NULL,
  `Nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `Usuari`
--
--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `actuacio_de_incidencia`
--
ALTER TABLE `actuacio_de_incidencia`
  ADD PRIMARY KEY (`id_incidencia`,`linia_incidencia`),
  ADD KEY `estat_incidencia` (`estat_incidencia`);

--
-- Índexs per a la taula `Departament`
--
ALTER TABLE `Departament`
  ADD PRIMARY KEY (`ID`);

--
-- Índexs per a la taula `Empleat`
--
ALTER TABLE `Empleat`
  ADD PRIMARY KEY (`DNI`),
  ADD KEY `FK_Rol` (`Rol_ID`);

--
-- Índexs per a la taula `Estat`
--
ALTER TABLE `Estat`
  ADD PRIMARY KEY (`ID`);

--
-- Índexs per a la taula `Incidencies`
--
ALTER TABLE `Incidencies`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Estat` (`Estat`),
  ADD KEY `Departament` (`Departament`),
  ADD KEY `Usuari` (`Usuari`),
  ADD KEY `Empleat` (`Empleat`),
  ADD KEY `Prioritat` (`Prioritat`);

--
-- Índexs per a la taula `Prioritat`
--
ALTER TABLE `Prioritat`
  ADD PRIMARY KEY (`ID`);

--
-- Índexs per a la taula `Rol`
--
ALTER TABLE `Rol`
  ADD PRIMARY KEY (`ID`);

--
-- Índexs per a la taula `Usuari`
--
ALTER TABLE `Usuari`
  ADD PRIMARY KEY (`DNI`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `Incidencies`
--
ALTER TABLE `Incidencies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT per la taula `Rol`
--
ALTER TABLE `Rol`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restriccions per a les taules bolcades
--

--
-- Restriccions per a la taula `actuacio_de_incidencia`
--
ALTER TABLE `actuacio_de_incidencia`
  ADD CONSTRAINT `actuacio_de_incidencia_ibfk_1` FOREIGN KEY (`id_incidencia`) REFERENCES `Incidencies` (`ID`),
  ADD CONSTRAINT `actuacio_de_incidencia_ibfk_2` FOREIGN KEY (`estat_incidencia`) REFERENCES `Estat` (`ID`),
  ADD CONSTRAINT `fk_actuacio_incidencia` FOREIGN KEY (`id_incidencia`) REFERENCES `Incidencies` (`ID`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `Empleat`
--
ALTER TABLE `Empleat`
  ADD CONSTRAINT `FK_Rol` FOREIGN KEY (`Rol_ID`) REFERENCES `Rol` (`ID`);

--
-- Restriccions per a la taula `Incidencies`
--
ALTER TABLE `Incidencies`
  ADD CONSTRAINT `Incidencies_ibfk_1` FOREIGN KEY (`Estat`) REFERENCES `Estat` (`ID`),
  ADD CONSTRAINT `Incidencies_ibfk_2` FOREIGN KEY (`Departament`) REFERENCES `Departament` (`ID`),
  ADD CONSTRAINT `Incidencies_ibfk_3` FOREIGN KEY (`Usuari`) REFERENCES `Usuari` (`DNI`),
  ADD CONSTRAINT `Incidencies_ibfk_4` FOREIGN KEY (`Empleat`) REFERENCES `Empleat` (`DNI`),
  ADD CONSTRAINT `Incidencies_ibfk_5` FOREIGN KEY (`Prioritat`) REFERENCES `Prioritat` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;