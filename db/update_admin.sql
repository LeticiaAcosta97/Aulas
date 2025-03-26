-- Actualizar la contraseña del usuario admin
UPDATE usuarios 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE usuario = 'admin';

-- Si el usuario no existe, lo creamos
INSERT INTO usuarios (usuario, password, nombre) 
SELECT 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador'
WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE usuario = 'admin');