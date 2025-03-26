ALTER TABLE equipos 
ADD tipo VARCHAR(50),
ADD modelo VARCHAR(50),
ADD nro_serie VARCHAR(50),
ADD fecha_instalacion DATE,
ADD ultima_fecha_mantenimiento DATE,
ADD periodo_mantenimiento INT COMMENT 'Período en días';