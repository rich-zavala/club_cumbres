CREATE TABLE `1557164_cumbres`.`arbitros` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(45) NULL,
  `fechaRegistro` DATETIME NULL,
  `activo` INT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`, `activo`));
USE `1557164_cumbres`;

CREATE TABLE `1557164_cumbres`.`arbitrosSueldos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `torneo` INT NOT NULL,
  `sueldo1` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `sueldo2` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `sueldo3` DECIMAL(10,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`));

CREATE TABLE `1557164_cumbres`.`arbitrosPartidos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `partido` INT NOT NULL,
  `arbitro1` INT NULL,
  `arbitro2` INT NULL,
  `arbitro3` INT NULL,
  PRIMARY KEY (`id`));

DROP TRIGGER IF EXISTS 1557164_cumbres.arbitros_AFTER_DELETE;
DROP TRIGGER IF EXISTS 1557164_cumbres.torneos_BEFORE_DELETE;
DROP TRIGGER IF EXISTS 1557164_cumbres.arbitros_BEFORE_INSERT;

DELIMITER $$
USE `1557164_cumbres`$$
CREATE TRIGGER `1557164_cumbres`.`arbitros_BEFORE_INSERT` BEFORE INSERT ON `arbitros` FOR EACH ROW
BEGIN
SET NEW.fechaRegistro = NOW();
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `1557164_cumbres`.`arbitros_AFTER_DELETE` AFTER DELETE ON `arbitros` FOR EACH ROW
BEGIN
DELETE FROM arbitrosPartidos WHERE arbitro1 = OLD.id;
DELETE FROM arbitrosPartidos WHERE arbitro2 = OLD.id;
DELETE FROM arbitrosPartidos WHERE arbitro3 = OLD.id;
END$$
DELIMITER ;

DELIMITER $$
USE `1557164_cumbres`$$
CREATE TRIGGER `1557164_cumbres`.`torneos_BEFORE_DELETE` BEFORE DELETE ON `torneos` FOR EACH ROW
BEGIN
DELETE FROM arbitrosSueldos WHERE torneo = OLD.ID_Torneo;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS 1557164_cumbres.partido_equipos$$
USE `1557164_cumbres`$$
CREATE DEFINER=`root`@`127.0.0.1` TRIGGER `partido_equipos` AFTER DELETE ON `partidos` FOR EACH ROW BEGIN
	DELETE FROM part_punt WHERE ID_Partido = OLD.ID_Partido;
	DELETE FROM goles_jornadas WHERE ID_Partido = OLD.ID_Partido;
	DELETE FROM part_gan_sout WHERE ID_Partido = OLD.ID_Partido;
  DELETE FROM arbitrosPartidos WHERE partido = OLD.ID_Partido;
END$$
DELIMITER ;
