-- Insertar aires acondicionados para todas las aulas
INSERT INTO equipos (aula_id, descripcion, cantidad, marca)
SELECT id, 'Aire Acondicionado', 1, 'Samsung'
FROM aulas
WHERE id NOT IN (
    SELECT DISTINCT aula_id 
    FROM equipos 
    WHERE descripcion LIKE '%Aire%'
);

-- Insertar proyectores en algunas aulas (por ejemplo, aulas pares)
INSERT INTO equipos (aula_id, descripcion, cantidad, marca)
SELECT id, 'Proyector', 1, 'Epson'
FROM aulas
WHERE id % 2 = 0
AND id NOT IN (
    SELECT DISTINCT aula_id 
    FROM equipos 
    WHERE descripcion LIKE '%Proyector%'
);

-- Insertar PCs en algunas aulas (por ejemplo, aulas múltiplos de 3)
INSERT INTO equipos (aula_id, descripcion, cantidad, marca)
SELECT id, 'PC con Monitor', 1, 'HP'
FROM aulas
WHERE id % 3 = 0
AND id NOT IN (
    SELECT DISTINCT aula_id 
    FROM equipos 
    WHERE descripcion LIKE '%PC%'
);