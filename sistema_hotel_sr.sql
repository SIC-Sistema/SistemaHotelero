-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2022 a las 18:35:35
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_hotel_sr`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rfc` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `colonia` varchar(30) NOT NULL,
  `localidad` varchar(30) NOT NULL,
  `cp` int(11) NOT NULL,
  `limpieza` varchar(200) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `rfc`, `email`, `telefono`, `direccion`, `colonia`, `localidad`, `cp`, `limpieza`, `usuario`, `fecha`) VALUES
(1, 'ALFREDO MARTOIE', '123456789098765', 'prueba@fredy.org', '4331002042', 'Av. Hidalgo 508', 'h', 'Sombrerete', 99100, 'limpiar hasta debajo de la cama', 1, '2022-10-13'),
(2, 'Alfredo', '32456784334565432', 'correo@sicsom.com', '4331002042', 'Av. Hidalgo 508', 'centro', 'Sombrerete', 99100, 'Solo cambiar sabanas x2', 1, '2022-10-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL,
  `piso` varchar(15) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` float NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `estatus` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `piso`, `descripcion`, `precio`, `tipo`, `estatus`, `usuario`, `fecha`) VALUES
(1, 'Primer', '1 Cinta aislar', 1, 'Individual', 0, 1, '2022-10-13'),
(2, 'Primer', '222', 22, 'Individual', 0, 1, '2022-10-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `id_reservacion` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id`, `id_reservacion`, `descripcion`, `usuario`, `fecha`) VALUES
(1, 1, '', 1, '2022-10-20'),
(2, 1, 'Hola', 1, '2022-10-20'),
(3, 1, 'Holaxq', 1, '2022-10-20'),
(4, 1, 'Holaxqd', 1, '2022-10-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(10) NOT NULL,
  `id_cliente` int(10) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `fecha` varchar(30) DEFAULT NULL,
  `hora` time NOT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `corte` int(10) DEFAULT NULL,
  `id_user` int(10) DEFAULT NULL,
  `tipo_cambio` varchar(50) DEFAULT NULL,
  `id_deuda` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_cliente`, `descripcion`, `cantidad`, `fecha`, `hora`, `tipo`, `corte`, `id_user`, `tipo_cambio`, `id_deuda`) VALUES
(1, 1, 'Reservación Habitacion N° $habitacion ($Entrada - $Salida)', 1, '2022-10-19', '09:50:31', 'Anticipo', 0, 1, 'Efectivo', NULL),
(2, 1, 'Reservación Habitacion N° 2 (2022-10-19 - 2022-10-21)', 1, '2022-10-19', '09:51:39', 'Anticipo', 0, 1, 'Efectivo', NULL),
(3, 1, 'Reservación Habitacion N°2 (2022-10-20 - 2022-10-21)', 50, '2022-10-19', '09:53:03', 'Anticipo', 0, 1, 'Banco', NULL),
(4, 2, 'Reservación Habitacion N°1 (2022-10-19 - 2022-10-22)', 2, '2022-10-19', '10:30:25', 'Anticipo', 0, 1, 'Credito', NULL),
(5, 1, 'Reservación Habitacion N°2 (2022-10-28 - 2022-10-31)', 44, '2022-10-19', '11:37:48', 'Anticipo', 0, 1, 'Efectivo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_borrar_cliente`
--

CREATE TABLE `pv_borrar_cliente` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `colonia` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `cp` int(11) NOT NULL,
  `rfc` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `registro` int(11) NOT NULL,
  `borro` int(11) NOT NULL,
  `fecha_borro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `observacion` varchar(100) NOT NULL,
  `total` float NOT NULL,
  `anticipo` float NOT NULL,
  `estatus` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reservaciones`
--

INSERT INTO `reservaciones` (`id`, `id_cliente`, `id_habitacion`, `nombre`, `fecha_entrada`, `fecha_salida`, `observacion`, `total`, `anticipo`, `estatus`, `usuario`, `fecha_registro`) VALUES
(1, 1, 2, 'fred', '2022-10-19', '2022-10-20', 'dd', 0.1, 0, 2, 1, '2022-10-19'),
(4, 1, 2, 'fred', '2022-10-20', '2022-10-21', 'dd', 22, 50, 3, 1, '2022-10-19'),
(5, 1, 2, '444', '2022-10-21', '2022-10-22', '', 22, 0, 3, 1, '2022-10-19'),
(6, 2, 1, 'hola', '2022-10-19', '2022-10-22', 'nada', 3, 2, 0, 1, '2022-10-19'),
(7, 1, 2, 'ulimo', '2022-10-22', '2022-10-24', 'redireccion', 44, 0, 0, 1, '2022-10-19'),
(8, 1, 2, 'ulimo', '2022-10-26', '2022-10-27', 'redireccion', 22, 0, 0, 1, '2022-10-19'),
(9, 1, 2, 'ulimo', '2022-10-27', '2022-10-28', 'redireccion', 22, 0, 0, 1, '2022-10-19'),
(10, 1, 2, 'hola', '2022-10-28', '2022-10-31', 'redirreccion', 66, 44, 0, 1, '2022-10-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `start` date NOT NULL,
  `end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_events`
--

INSERT INTO `tbl_events` (`id`, `title`, `start`, `end`) VALUES
(1, 'Nombre: ALfredo', '2022-10-04', '2022-10-06'),
(2, 'Nombre:Omar Martinez hhhh', '2022-10-14', '2022-10-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `firstname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `date_added` datetime NOT NULL,
  `area` varchar(254) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Estatus` int(11) NOT NULL,
  `usuarios` int(11) NOT NULL,
  `clientes` int(11) NOT NULL,
  `habitaciones` int(11) NOT NULL,
  `reportes` int(11) NOT NULL,
  `borrar` int(11) NOT NULL,
  `cancelar` int(11) NOT NULL,
  `facturar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `user_name`, `user_password_hash`, `user_email`, `date_added`, `area`, `Estatus`, `usuarios`, `clientes`, `habitaciones`, `reportes`, `borrar`, `cancelar`, `facturar`) VALUES
(1, 'admin', 'admin', 'Administrador', '$2y$10$CwfVw8AtNQnTeg3emhTC8ebYAkMmMr6IiS1yrgfaIlmQnARImRAwu', 'admin@sanroman.com', '2022-10-04 08:44:35', 'Administrador', 1, 1, 1, 1, 1, 1, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indices de la tabla `pv_borrar_cliente`
--
ALTER TABLE `pv_borrar_cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pv_borrar_cliente`
--
ALTER TABLE `pv_borrar_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
