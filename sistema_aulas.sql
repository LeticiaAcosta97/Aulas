-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-04-2025 a las 21:38:57
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_aulas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id` int NOT NULL,
  `aula_id` int DEFAULT NULL,
  `curso_id` int DEFAULT NULL,
  `fecha_asignacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `turno` enum('Mañana','Tarde','Noche') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `asignaciones`
--

INSERT INTO `asignaciones` (`id`, `aula_id`, `curso_id`, `fecha_asignacion`, `turno`) VALUES
(7, 4, 17, '2025-03-12 21:29:39', NULL),
(19, 1, 18, '2025-04-21 17:08:06', NULL),
(20, 1, 2, '2025-04-21 19:35:56', NULL),
(21, 5, 3, '2025-04-21 20:57:50', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` int NOT NULL,
  `numero` int NOT NULL,
  `capacidad` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id`, `numero`, `capacidad`) VALUES
(1, 50, 25),
(2, 51, 30),
(3, 52, 40),
(4, 53, 35),
(5, 54, 45),
(6, 55, 20),
(7, 56, 25),
(8, 57, 30),
(9, 58, 35),
(10, 59, 40),
(11, 60, 50),
(12, 61, 25),
(13, 62, 30),
(14, 63, 35),
(15, 64, 40),
(16, 65, 45),
(17, 66, 20),
(18, 67, 25),
(19, 68, 30),
(20, 69, 35),
(21, 70, 40),
(22, 71, 25),
(23, 72, 30),
(24, 73, 35),
(25, 74, 40),
(26, 75, 45),
(27, 76, 50),
(28, 77, 20),
(29, 78, 25),
(30, 79, 30),
(31, 80, 35),
(32, 81, 40),
(33, 82, 45),
(34, 83, 50),
(35, 84, 20),
(36, 85, 25),
(37, 86, 30),
(38, 87, 35),
(39, 88, 40),
(40, 89, 45),
(41, 90, 50),
(42, 91, 20),
(43, 92, 25),
(44, 93, 30),
(45, 94, 35),
(46, 95, 40),
(47, 96, 45),
(48, 97, 50),
(49, 98, 20),
(50, 99, 25),
(51, 100, 30),
(52, 55, 30),
(53, 56, 30),
(54, 57, 30),
(55, 58, 30),
(56, 59, 30),
(57, 60, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas_equipos`
--

CREATE TABLE `aulas_equipos` (
  `id` int NOT NULL,
  `aula_id` int NOT NULL,
  `equipo_id` int NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `aulas_equipos`
--

INSERT INTO `aulas_equipos` (`id`, `aula_id`, `equipo_id`, `cantidad`) VALUES
(10, 1, 8, 1),
(13, 2, 49, 1),
(14, 3, 66, 1),
(15, 2, 68, 1),
(16, 4, 69, 1),
(17, 52, 67, 1),
(18, 5, 70, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` int NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id`, `nombre`) VALUES
(1, 'Lic. en Análisis de Sistema'),
(2, 'Administración de Empresas'),
(4, 'Comercio Internacional'),
(5, 'Contabilidad'),
(6, 'Ingenieria Comercial'),
(7, 'Ingenieria en Informatica'),
(8, 'Arquitectura'),
(9, 'Diseño Grafico'),
(10, 'Ingenieria en Marketing');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `turno` enum('Mañana','Tarde','Noche') NOT NULL,
  `carrera_id` int DEFAULT NULL,
  `alumnos_matriculados` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `turno`, `carrera_id`, `alumnos_matriculados`) VALUES
(1, 'PRIMER', 'Mañana', 1, 30),
(2, 'SEGUNDO', 'Tarde', 1, 25),
(3, 'TERCER', 'Noche', 2, 40),
(17, 'QUINTO', 'Tarde', 2, 35),
(18, 'SEGUNDO', 'Noche', 5, 28),
(19, 'CUARTO', 'Mañana', 7, 22),
(20, 'TERCER', 'Tarde', 10, 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int NOT NULL,
  `aula_id` int DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text,
  `tipo` varchar(50) DEFAULT NULL,
  `marca` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `cantidad` int DEFAULT '1',
  `estado` varchar(50) DEFAULT 'Sin Asignar',
  `fecha_estado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nro_serie` varchar(50) DEFAULT NULL,
  `fecha_instalacion` date DEFAULT NULL,
  `ultima_fecha_mantenimiento` date DEFAULT NULL,
  `periodo_mantenimiento` int DEFAULT NULL COMMENT 'Período en días',
  `observaciones` text,
  `nro_factura` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `aula_id`, `nombre`, `descripcion`, `tipo`, `marca`, `modelo`, `cantidad`, `estado`, `fecha_estado`, `nro_serie`, `fecha_instalacion`, `ultima_fecha_mantenimiento`, `periodo_mantenimiento`, `observaciones`, `nro_factura`) VALUES
(8, 1, 'Proyector', 'Proyector Full HD 1080p', 'PROYECTOR', 'Epson', '1080p', 1, 'activo', '2025-03-12 20:07:29', '10002', '2024-08-05', '2024-12-02', 180, NULL, NULL),
(49, 52, 'Proyector', 'Proyector multimedia Full HD', 'PROYECTOR', 'Epson', 'GENERICO', 1, 'activo', '2025-03-12 20:07:29', '10003', '2025-04-17', NULL, 180, NULL, NULL),
(65, NULL, 'PROYECTOR MULTIMEDIA FULL HD', 'PROYECTOR MULTIMEDIA FULL HD', 'PROYECTOR MULTIMEDIA FULL HD', 'EPSON', 'GENERICO', 1, 'Sin Asignar', '2025-04-15 16:17:08', '0001-001-00001-001', '2025-04-17', NULL, 180, NULL, '0001-001-00001'),
(66, NULL, 'PROYECTOR MULTIMEDIA FULL HD', 'PROYECTOR MULTIMEDIA FULL HD', 'PROYECTOR MULTIMEDIA FULL HD', 'EPSON', '3000 lumenes', 1, 'Sin Asignar', '2025-04-15 18:21:50', '0001-001-00002-001', NULL, NULL, NULL, NULL, '0001-001-00002'),
(67, NULL, 'AIRE ACONDICIONADO 24000 BTU', 'AIRE ACONDICIONADO 24000 BTU', 'AIRE ACONDICIONADO 24000 BTU', 'TOKYO', '24000 BTU', 1, 'Sin Asignar', '2025-04-15 18:41:19', '20003', '2025-04-17', NULL, 180, NULL, '0001-001-00003'),
(68, NULL, 'AIRE ACONDICIONADO 24.000 BTU', 'AIRE ACONDICIONADO 24.000 BTU', 'AIRE ACONDICIONADO 24.000 BTU', 'TOKYO', '24000 BTU', 1, 'Sin Asignar', '2025-04-17 22:09:05', '20002', '2025-02-18', NULL, 180, NULL, '0001-001-00004'),
(69, NULL, 'AIRE ACONDICIONADO 24.000 BTU', 'AIRE ACONDICIONADO 24.000 BTU', 'AIRE ACONDICIONADO 24.000 BTU', 'TOKYO', '24000 BTU', 1, 'Sin Asignar', '2025-04-17 22:09:05', '20001', '2025-04-10', NULL, 180, NULL, '0001-001-00004'),
(70, NULL, 'AIRE ACONDICIONADO 24000 BTU', 'AIRE ACONDICIONADO 24000 BTU', 'AIRE', 'TOKYO', '24000 BTU', 1, 'Sin Asignar', '2025-04-17 22:28:58', '20004', '2025-04-17', NULL, 180, NULL, '0001-001-00005'),
(71, NULL, 'AIRE ACONDICIONADO 24000 BTU', 'AIRE ACONDICIONADO 24000 BTU', 'AIRE', 'TOKYO', '24000 BTU', 1, 'Sin Asignar', '2025-04-17 22:28:58', '20005', '2025-04-17', NULL, 180, NULL, '0001-001-00005'),
(72, NULL, 'AIRE ACONDICIONADO 24000 BTU', 'AIRE ACONDICIONADO 24000 BTU', 'AIRE', 'TOKYO', '24000 BTU', 1, 'Sin Asignar', '2025-04-17 22:28:58', '20006', '2025-04-17', NULL, 180, NULL, '0001-001-00005'),
(73, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:11', '30001', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(74, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30002', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(75, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30003', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(76, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30004', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(77, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30005', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(78, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30006', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(79, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30007', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(80, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30008', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(81, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30009', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(82, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30010', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(83, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30011', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(84, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30012', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(85, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30013', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(86, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30014', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(87, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30015', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(88, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30016', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(89, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30017', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(90, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30018', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(91, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30019', '2025-04-17', NULL, 180, NULL, '0001-001-00006'),
(92, NULL, 'PC ESCRITORIO INTEL I5', 'PC ESCRITORIO INTEL I5', 'PC', 'ASUS', 'P5G45', 1, 'Sin Asignar', '2025-04-17 23:03:12', '30020', '2025-04-17', NULL, 180, NULL, '0001-001-00006');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_mantenimiento`
--

CREATE TABLE `historial_mantenimiento` (
  `id` int NOT NULL,
  `equipo_id` int DEFAULT NULL,
  `fecha_mantenimiento` date DEFAULT NULL,
  `observaciones` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `historial_mantenimiento`
--

INSERT INTO `historial_mantenimiento` (`id`, `equipo_id`, `fecha_mantenimiento`, `observaciones`) VALUES
(1, 8, '2024-12-02', 'se recomienda mantenimiento cada 180 dias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `usuario` varchar(50),
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuarios`
--

-- Modificar la tabla usuarios
ALTER TABLE usuarios MODIFY COLUMN rol ENUM('administrador', 'secretaria', 'mantenimiento') NOT NULL DEFAULT 'secretaria';

-- Actualizar los usuarios existentes
UPDATE usuarios SET rol = 'administrador' WHERE usuario = 'admin';

-- Insertar usuario administrador por defecto si no existe
INSERT INTO usuarios (usuario, password, nombre, apellido, email, rol) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', 'admin@sistema.com', 'administrador')
ON DUPLICATE KEY UPDATE rol = 'administrador';
(1, 'admin', 'adm123', 'Administrador', '', '', 'admin'),
(10, 'LEACOSTA', '1234', 'LETICIA', 'ACOSTA AQUINO', 'leticiaacosta91@gmail.com', ''),
(11, 'JUCACERES', 'juan123', 'JUAN', 'CACERES', 'crashservice9@gmail.com', 'user');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aula_id` (`aula_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aulas_equipos`
--
ALTER TABLE `aulas_equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aula_id` (`aula_id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrera_id` (`carrera_id`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aula_id` (`aula_id`);

--
-- Indices de la tabla `historial_mantenimiento`
--
ALTER TABLE `historial_mantenimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `aulas_equipos`
--
ALTER TABLE `aulas_equipos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `historial_mantenimiento`
--
ALTER TABLE `historial_mantenimiento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `aulas_equipos`
--
ALTER TABLE `aulas_equipos`
  ADD CONSTRAINT `aulas_equipos_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`),
  ADD CONSTRAINT `aulas_equipos_ibfk_2` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_mantenimiento`
--
ALTER TABLE `historial_mantenimiento`
  ADD CONSTRAINT `historial_mantenimiento_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
