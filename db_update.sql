ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS rol ENUM('administrador', 'auxiliar', 'mantenimiento') NOT NULL DEFAULT 'auxiliar';

-- Actualizar el usuario admin existente
UPDATE usuarios SET rol = 'administrador' WHERE usuario = 'admin';