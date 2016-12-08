ALTER TABLE `arbitrossueldos`
ADD COLUMN `sueldo4`  decimal(10,2) NOT NULL AFTER `sueldo3`;

ALTER TABLE `arbitrossueldos`
ADD COLUMN `sueldo5`  decimal(10,2) NOT NULL AFTER `sueldo4`;

ALTER TABLE `part_punt`
ADD COLUMN `pago_servicio`  int(1) NOT NULL DEFAULT 0 AFTER `perdio_so`;

CREATE VIEW `jornada_arbitros` AS 
SELECT
j.ID_Jornada,
j.ID_CatTorn,
c.ID_Torneo,
a.sueldo1,
a.sueldo2,
a.sueldo3,
a.sueldo4,
a.sueldo5
FROM
tcat_jorn AS j
INNER JOIN torneos_cats AS c ON j.ID_CatTorn = c.ID_CatTorn
INNER JOIN arbitrossueldos AS a ON c.ID_Torneo = a.torneo ;

CREATE VIEW 'reporte_sueldos'
AS
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
IF(ar1.nombre IS NULL, 0, ja.sueldo1) arbitroSueldo1,
IF(ar2.nombre IS NULL, 0, ja.sueldo2) arbitroSueldo2,
IF(ar3.nombre IS NULL, 0, ja.sueldo3) cronoSueldo,
ja.sueldo4 mesa,
ja.sueldo5 servicio
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
WHERE FechaHora > 0
GROUP BY
p.ID_Partido
ORDER BY
p.FechaHora ASC,
p.TipoCancha ASC
