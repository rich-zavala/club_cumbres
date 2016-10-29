DROP TRIGGER `dTorneo`;

CREATE TRIGGER `dTorneo` AFTER DELETE ON `torneos`
FOR EACH ROW BEGIN

DELETE FROM torneos_cats where ID_Torneo=OLD.ID_Torneo;
DELETE FROM arbitrossueldos WHERE torneo = OLD.ID_Torneo;

END;

CREATE TABLE `arbitrospartidosfaltas` (
`id`  int NOT NULL AUTO_INCREMENT ,
`partido`  int NULL ,
`arbitro1faltas1`  int NULL DEFAULT 0 ,
`arbitro1faltas2`  int NULL DEFAULT 0 ,
`arbitro1faltas3`  int NULL DEFAULT 0 ,
`arbitro2faltas1`  int NULL DEFAULT 0 ,
`arbitro2faltas2`  int NULL DEFAULT 0 ,
`arbitro2faltas3`  int NULL DEFAULT 0 ,
`arbitro3faltas1`  int NULL DEFAULT 0 ,
`arbitro3faltas2`  int NULL DEFAULT 0 ,
`arbitro3faltas3`  int NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
;

ALTER TABLE `arbitrospartidosfaltas`
CHANGE COLUMN `partido` `arbitrospartido`  int(11) NULL DEFAULT NULL AFTER `id`;

CREATE TRIGGER `arbitroPartidoCreado` AFTER INSERT ON `arbitrospartidos`
FOR EACH ROW INSERT INTO arbitrospartidosfaltas (arbitrospartido) values (NEW.id);;

CREATE TRIGGER `arbitroPartidoEliminado` AFTER DELETE ON `arbitrospartidos`
FOR EACH ROW DELETE FROM arbitrospartidosfaltas WHERE arbitrospartido = OLD.id;;

ALTER TABLE `arbitrospartidosfaltas`
ADD COLUMN `arbitro1faltas4`  int NULL AFTER `arbitro1faltas3`,
ADD COLUMN `arbitro2faltas4`  int NULL AFTER `arbitro2faltas3`,
ADD COLUMN `arbitro3faltas4`  int NULL AFTER `arbitro3faltas3`;

ALTER TABLE `arbitrospartidosfaltas`
MODIFY COLUMN `arbitro1faltas4`  int(11) NULL DEFAULT 0 AFTER `arbitro1faltas3`,
MODIFY COLUMN `arbitro2faltas4`  int(11) NULL DEFAULT 0 AFTER `arbitro2faltas3`,
MODIFY COLUMN `arbitro3faltas4`  int(11) NULL DEFAULT 0 AFTER `arbitro3faltas3`;

