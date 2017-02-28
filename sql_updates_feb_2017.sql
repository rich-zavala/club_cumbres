ALTER TABLE `arbitrossueldos`
ADD COLUMN `descuento`  decimal(10,2) NOT NULL DEFAULT 10.00 AFTER `sueldo5`;

ALTER TABLE `partidos`
ADD COLUMN `liguilla`  int(1) NULL DEFAULT 0 AFTER `FechaRegistro`;



ALTER TABLE `arbitrossueldos`
MODIFY COLUMN `sueldo4`  decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `sueldo3`,
MODIFY COLUMN `sueldo5`  decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `sueldo4`,
ADD COLUMN `sueldo6`  decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `sueldo5`;

ALTER VIEW `jornada_arbitros` AS 
SELECT
j.ID_Jornada,
j.ID_CatTorn,
c.ID_Torneo,
a.sueldo1,
a.sueldo2,
a.sueldo3,
a.sueldo4,
a.sueldo5,
a.sueldo6,
a.descuento
FROM
tcat_jorn AS j
INNER JOIN torneos_cats AS c ON j.ID_CatTorn = c.ID_CatTorn
INNER JOIN arbitrossueldos AS a ON c.ID_Torneo = a.torneo ;



CREATE 
VIEW `arbitros_faltas_sumatorias`AS 
SELECT
af.arbitrospartido partido,
af.arbitro1faltas1 + af.arbitro1faltas2 + af.arbitro1faltas3 + af.arbitro1faltas4 faltas1,
af.arbitro2faltas1 + af.arbitro2faltas2 + af.arbitro2faltas3 + af.arbitro2faltas4 faltas2,
af.arbitro3faltas1 + af.arbitro3faltas2 + af.arbitro3faltas3 + af.arbitro3faltas4 faltas3
FROM
arbitrospartidosfaltas AS af ;


DROP TRIGGER `arbitroPartidoCreado`;

CREATE TRIGGER `arbitroPartidoCreado` AFTER INSERT ON `arbitrospartidos`
FOR EACH ROW INSERT INTO arbitrospartidosfaltas (arbitrospartido) values (NEW.id);

ALTER VIEW `reporte_sueldos` AS 
SELECT
ID_Torneo,
p.FechaHora,
DATE_FORMAT(FechaHora, "%d%b%Y") AS fechaId,
DATE_FORMAT(FechaHora, "%W %d/%b/%Y") AS fecha,
DATE_FORMAT(FechaHora, "%H:%i") AS hora,
p.TipoCancha,
GROUP_CONCAT( CONCAT( pp.ID_Equipo, "|", NomEquipo, "|", pp.pago_servicio ) ORDER BY pp.ID_Equipo ) AS equipos,
ar1.nombre AS arbitroNombre1,
ar2.nombre AS arbitroNombre2,
ar3.nombre AS crono,
IF(ar1.nombre IS NULL, 0, ja.sueldo1) - (afs.faltas1 * ja.descuento) arbitroSueldo1,
IF(ar2.nombre IS NULL, 0, ja.sueldo2) - (afs.faltas2 * ja.descuento) arbitroSueldo2,
IF(ar3.nombre IS NULL, 0, ja.sueldo3) - (afs.faltas3 * ja.descuento) cronoSueldo,
ja.sueldo4 mesa,
CASE
	WHEN liguilla = 1 THEN ja.sueldo6
	WHEN p.TipoCancha = 1 THEN ja.sueldo5
	WHEN p.TipoCancha = 2 THEN ja.sueldo6
END servicio,
liguilla
FROM
jornadas
JOIN partidos AS p ON jornadas.ID_Jornada = p.ID_Jornada
INNER JOIN part_punt AS pp ON p.ID_Partido = pp.ID_Partido
INNER JOIN equipos_catalog AS eq ON pp.ID_Equipo = eq.ID_Equipo
INNER JOIN jornada_arbitros AS ja ON jornadas.ID_Jornada = ja.ID_Jornada
LEFT JOIN arbitrospartidos AS ap ON ap.partido = p.ID_Partido
LEFT JOIN arbitrospartidosfaltas AS apf ON apf.arbitrospartido = ap.id
LEFT JOIN arbitros AS ar1 ON ar1.id = ap.arbitro1
LEFT JOIN arbitros AS ar2 ON ar2.id = ap.arbitro2
LEFT JOIN arbitros AS ar3 ON ar3.id = ap.arbitro3
LEFT JOIN arbitros_faltas_sumatorias afs ON afs.partido = ap.id
WHERE FechaHora > 0

GROUP BY
p.ID_Partido
ORDER BY
p.FechaHora ASC,
p.TipoCancha ASC;

