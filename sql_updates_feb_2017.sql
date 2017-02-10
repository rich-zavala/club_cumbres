ALTER TABLE `arbitrossueldos`
ADD COLUMN `descuento`  decimal(10,2) NOT NULL DEFAULT 10.00 AFTER `sueldo5`;

ALTER TABLE `partidos`
ADD COLUMN `liguilla`  int(1) NULL DEFAULT 0 AFTER `FechaRegistro`;

