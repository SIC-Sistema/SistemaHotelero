-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2022 a las 19:34:09
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
-- Base de datos: `servintcomp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_borrados`
--

CREATE TABLE `pagos_borrados` (
  `id` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `realizo` int(11) NOT NULL,
  `tipo_cambio` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_hora_registro` datetime NOT NULL,
  `motivo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `borro` int(11) NOT NULL,
  `fecha_borrado` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pagos_borrados`
--

INSERT INTO `pagos_borrados` (`id`, `cliente`, `cantidad`, `descripcion`, `realizo`, `tipo_cambio`, `fecha_hora_registro`, `motivo`, `borro`, `fecha_borrado`) VALUES
(2, 0, 400, 'Mensualidad :AGOSTO 2022', 102, 'Efectivo', '2022-08-03 13:57:23', 'Error en tipo de cambio', 101, '2022-08-11'),
(3, 0, 410, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio)', 102, 'Efectivo', '2022-08-11 15:46:59', 'Error de Cambio', 101, '2022-08-11'),
(4, 0, 400, 'Abono :ABONO', 102, 'Efectivo', '2022-08-12 10:11:05', 'error de registro', 101, '2022-08-12'),
(5, 0, 50, 'Abono :ABONO', 102, 'Efectivo', '2022-08-12 10:12:23', 'Error de registro', 101, '2022-08-12'),
(6, 0, 450, 'Abono :ABONO', 102, 'Efectivo', '2022-08-12 10:12:57', 'Error de registro', 101, '2022-08-12'),
(7, 0, 330, 'Mensualidad :AGOSTO 2022', 101, 'Banco', '2022-08-12 14:09:24', 'Error de cobro', 101, '2022-08-12'),
(8, 0, 250, 'Reporte :; MATERIAL(N/A, N/A, SE MOVIO ANTENA DE LUGAR Y ROUTER)', 68, 'Efectivo', '2022-08-15 13:49:16', 'Error de Cambo ', 101, '2022-08-15'),
(9, 0, 1100, 'Otros Pagos :PAGO DE INSTALACIÓN', 102, 'Banco', '2022-08-16 14:31:10', 'Cliente erróneo', 101, '2022-08-16'),
(10, 0, 500, 'Mensualidad :AGOSTO 2022', 102, 'Efectivo', '2022-08-16 14:33:41', 'Por tipo de Cambio ', 101, '2022-08-16'),
(11, 0, 380, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio)', 28, 'Efectivo', '2022-08-12 20:28:21', 'el pago era a credito y se metio en efectivo se vuelve a capturar', 28, '2022-08-22'),
(12, 0, 410, 'Mensualidad :JULIO 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'Efectivo', '2022-08-22 13:30:53', 'Error de Cambio', 101, '2022-08-22'),
(13, 0, 100, 'Otros Pagos :AUMENTAR PAQUETE: Diferencia ($100)', 84, 'Efectivo', '2022-08-23 11:42:47', 'Prueba para mostrar a cobredor error de fernando', 49, '2022-08-23'),
(14, 0, 277, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $173', 101, 'Efectivo', '2022-08-23 09:22:21', 'Error de Cambio y cantidad', 101, '2022-08-23'),
(15, 0, 277, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $173', 101, 'Efectivo', '2022-08-23 09:21:07', 'Error de Cambio y cantidad', 101, '2022-08-23'),
(16, 1960, 450, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'Banco', '2022-08-24 16:46:08', 'Error de registro', 101, '2022-08-24'),
(17, 1960, 450, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'Banco', '2022-08-24 16:57:35', 'Error de registro', 101, '2022-08-24'),
(18, 3085, 450, 'Reporte :; MATERIAL(N/A, N/A, )', 68, 'Efectivo', '2022-08-25 12:35:55', 'Pago duplicado', 101, '2022-08-25'),
(19, 475, 306, 'Mensualidad :AGOSTO 2022 - Descuento: $194', 106, 'Efectivo', '2022-08-25 14:22:34', 'Error de Cambio', 101, '2022-08-25'),
(20, 5258, 550, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio)', 46, 'Efectivo', '2022-08-25 13:57:18', 'Por activación hasta septiembre', 101, '2022-08-25'),
(21, 3085, 450, 'Abono :', 101, 'Efectivo', '2022-08-25 14:37:16', 'Error de Cambio', 101, '2022-08-25'),
(22, 1210, 360, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 105, 'Banco', '2022-08-25 16:04:57', 'ERROR DE CAPTURA', 101, '2022-08-25'),
(23, 600, 14400, 'Otros Pagos :adeudo de 3 años sin pagar y con consumo de internet', 84, 'Efectivo', '2022-08-25 17:13:52', 'Error en tipo de cambio era a credito', 49, '2022-08-25'),
(24, 1210, 200, 'Mensualidad :SEPTIEMBRE 2022 - Descuento: $110', 105, 'Banco', '2022-08-25 16:30:57', 'Aplicado a cuenta equivocada ', 101, '2022-08-26'),
(25, 1210, 160, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $200', 105, 'Banco', '2022-08-25 16:28:52', 'Aplicado a cuenta equivocada ', 101, '2022-08-26'),
(26, 4, 400, 'Mensualidad :SEPTIEMBRE 2022', 10, 'Efectivo', '2022-08-26 17:15:46', 'prueba', 10, '2022-08-26'),
(27, 5549, 500, 'Mensualidad :MARZO 2023', 106, 'Banco', '2022-08-29 12:05:06', 'Por cobro de mes gratis', 101, '2022-08-29'),
(28, 786, 550, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 106, 'Efectivo', '2022-08-29 13:50:24', 'ERROR DE CAPTURA', 101, '2022-08-29'),
(29, 786, 550, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio)', 106, 'Efectivo', '2022-08-29 14:02:27', 'ERROR DE CAPTURA', 101, '2022-08-29'),
(30, 4801, 0, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $550', 66, 'Efectivo', '2022-08-30 13:02:06', 'Error de usuario', 101, '2022-08-30'),
(31, 1877, 410, 'Otros Pagos :Adeudo del mes de agosto 2022, se tuvo consumo', 84, 'Credito', '2022-08-29 10:31:55', 'Error de registro', 101, '2022-08-30'),
(32, 2415, 0, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $450', 106, 'Efectivo', '2022-08-31 11:46:37', 'Mes erroneo', 101, '2022-08-31'),
(33, 6048, 1100, 'Liquidacion :Liquidación de Instalación', 68, 'Efectivo', '2022-09-01 11:25:44', 'Cleinte pagara por trasferencia', 101, '2022-09-01'),
(34, 5448, 390, 'Mensualidad :SEPTIEMBRE 2022 - Descuento: $110', 102, 'Banco', '2022-08-10 13:55:48', 'pago ficticio ', 49, '2022-09-01'),
(35, 441, 360, 'Mensualidad :SEPTIEMBRE 2022', 105, 'Banco', '2022-09-02 09:25:25', 'Por error en monto. paga aumento de megas', 101, '2022-09-02'),
(36, 6057, 1100, 'Liquidacion :Liquidación de Instalación', 73, 'Efectivo', '2022-09-02 11:06:20', 'Por tipo de cambio', 101, '2022-09-02'),
(37, 5931, 400, 'Mensualidad :SEPTIEMBRE 2022', 63, 'Efectivo', '2022-09-02 11:44:52', '', 10, '2022-09-02'),
(38, 5596, 460, 'Mensualidad :SEPTIEMBRE 2022', 46, 'Efectivo', '2022-09-02 20:10:21', 'se equivoco de cliente', 10, '2022-09-02'),
(39, 249, 3300, 'Mensualidad :SEPTIEMBRE 2023', 101, 'Efectivo', '2022-08-22 10:57:01', 'no pagó el año es pago equivocado', 10, '2022-09-02'),
(40, 730, 360, 'Mensualidad :OCTUBRE 2022', 52, 'Efectivo', '2022-09-03 08:56:20', 'Error de mes', 101, '2022-09-03'),
(41, 3517, 400, 'Mensualidad :SEPTIEMBRE 2022', 46, 'Efectivo', '2022-09-05 09:44:43', 'Por pago anual', 101, '2022-09-05'),
(42, 3517, 4000, 'Mensualidad :SEPTIEMBRE 2022', 46, 'Efectivo', '2022-09-05 09:48:54', 'año erroneo', 101, '2022-09-05'),
(43, 35, 330, 'Mensualidad :SEPTIEMBRE 2022', 101, 'Efectivo', '2022-09-05 10:42:05', 'ERROR DE CAPTURA', 101, '2022-09-05'),
(44, 1862, 400, 'Mensualidad :SEPTIEMBRE 2022', 101, 'Efectivo', '2022-09-05 11:26:34', 'ERROR DE CAPTURA', 101, '2022-09-05'),
(45, 1862, 400, 'Mensualidad :OCTUBRE 2022', 101, 'Efectivo', '2022-09-05 11:26:47', 'ERROR DE CAPTURA', 101, '2022-09-05'),
(46, 1865, 360, 'Mensualidad :SEPTIEMBRE 2022', 57, 'Efectivo', '2022-09-05 12:08:58', 'error de captura del cobrador \"[0:16 p. m., 5/9/2022] SOCORRO Hernández Estancia: Si me hase  favor de borrar un pago [0:17 p. m., 5/9/2022] SOCORRO Hernández Estancia: Isela villa [0:19 p. m., 5/9/20', 28, '2022-09-05'),
(47, 4523, 360, 'Mensualidad :SEPTIEMBRE 2023', 52, 'Efectivo', '2022-09-05 12:13:44', 'Error de cantidad', 101, '2022-09-05'),
(48, 4245, 330, 'Mensualidad :SEPTIEMBRE 2022', 101, 'Efectivo', '2022-09-05 13:58:50', 'mas megas', 101, '2022-09-05'),
(49, 5313, 400, 'Mensualidad :SEPTIEMBRE 2022', 101, 'Efectivo', '2022-09-05 15:01:50', 'ERROR DE CAPTURA', 101, '2022-09-05'),
(50, 1275, 400, 'Mensualidad :SEPTIEMBRE 2022', 49, 'Efectivo', '2022-08-30 20:24:33', 'Era a crédito ', 49, '2022-09-05'),
(51, 2538, 2750, 'Otros Pagos :Adeudo de 9 meses atrasados con consumo de internet', 84, 'Credito', '2022-08-31 13:59:33', 'ajuste de cantidad', 49, '2022-09-06'),
(52, 2538, 6180, 'Otros Pagos :Adeudo de mensualidades pasadas con consumo de internet', 84, 'Credito', '2022-09-06 09:08:58', 'Se borra deuda por acuerdo con el cliente EWIN HARA EL PAGO DEL ACUERDO', 49, '2022-09-06'),
(53, 2861, 380, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 105, 'Banco', '2022-09-06 11:54:16', 'Errorr de recargo', 101, '2022-09-06'),
(54, 191, 500, 'Mensualidad :SEPTIEMBRE 2022', 31, 'Efectivo', '2022-09-05 18:46:09', 'DISMINUCION DE PAQUETE', 49, '2022-09-06'),
(55, 4347, 400, 'Mensualidad :SEPTIEMBRE 2022', 31, 'Efectivo', '2022-09-05 18:46:34', 'DISMINUCION DE PAQUETE', 49, '2022-09-06'),
(56, 4347, 400, 'Mensualidad :SEPTIEMBRE 2022', 31, 'Efectivo', '2022-09-06 13:33:44', 'EL MENSO DE FERNANDO SE EQUIVOCO', 49, '2022-09-06'),
(57, 4347, 360, 'Mensualidad :OCTUBRE 2022', 49, 'Efectivo', '2022-09-06 13:35:17', 'EL MENSO DE FERNANDO SE EQUIVOCO', 49, '2022-09-06'),
(58, 221, 290, 'Mensualidad :SEPTIEMBRE 2022 - Descuento: $70', 105, 'Credito', '2022-09-03 12:00:38', 'ERROR DE CAPTURA', 101, '2022-09-06'),
(59, 5638, 450, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 105, 'Banco', '2022-09-06 14:23:54', 'ERROR DE CAPTURA', 101, '2022-09-06'),
(60, 5905, 1350, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'SAN', '2022-09-06 15:06:57', 'Por el recargo no aplica', 101, '2022-09-06'),
(61, 1215, 450, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 46, 'Efectivo', '2022-09-06 15:14:29', 'ERROR DE CAPTURA', 101, '2022-09-06'),
(62, 1210, 300, 'Mensualidad :SEPTIEMBRE 2022 - Descuento: $10', 105, 'Banco', '2022-09-06 15:04:25', 'ERROR Y SE REALIZARA NUEVAMENTE', 101, '2022-09-06'),
(63, 2826, 280, 'Mensualidad :SEPTIEMBRE 2022', 105, 'Banco', '2022-09-06 17:58:46', 'ERROR DE CAPTURA', 101, '2022-09-06'),
(64, 3354, 259, 'Mensualidad :AGOSTO 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $121', 63, 'Efectivo', '2022-08-21 23:34:57', '', 10, '2022-09-06'),
(65, 303, 380, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 93, 'Efectivo', '2022-09-06 11:16:58', 'Mala captura', 10, '2022-09-07'),
(66, 1094, 550, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'Efectivo', '2022-09-07 13:55:27', 'PAGO EN BANCO', 101, '2022-09-07'),
(67, 3636, 360, 'Mensualidad :SEPTIEMBRE 2022', 46, 'Efectivo', '2022-08-01 10:17:24', 'Por error de orden al registrar mensualidad ', 101, '2022-09-07'),
(68, 2030, 170, 'Mensualidad :SEPTIEMBRE 2022 - Descuento: $30', 91, 'Credito', '2022-08-03 10:29:18', 'Sin motivo para crédito', 101, '2022-09-09'),
(69, 146, 250, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 105, 'Banco', '2022-09-09 13:11:45', 'Cliente equivocado ', 101, '2022-09-09'),
(70, 656, 410, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 93, 'Efectivo', '2022-09-11 13:16:37', 'error de pago, no cuenta con servicio', 49, '2022-09-12'),
(71, 141, 250, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 77, 'Banco', '2021-09-11 14:07:52', 'Se registro erróneamente el año ', 101, '2022-09-12'),
(72, 23, 1100, 'Reporte :; MATERIAL(245A4C4E7B50, 22234X7003047, )', 73, 'Credito', '2022-09-09 12:50:17', 'Por prestamos de equipo contrato funge como responsiva ', 101, '2022-09-12'),
(73, 6082, 2200, 'Otros Pagos :COSTO DE INSTALACION', 68, 'Efectivo', '2022-09-12 15:18:35', 'Error de Cambio', 101, '2022-09-12'),
(74, 1906, 3500, 'Otros Pagos :pago 6 meses internet', 47, 'Efectivo', '2022-09-11 09:25:28', 'No se realiza registro por mes', 101, '2022-09-12'),
(75, 4586, 348, 'Mensualidad :SEPTIEMBRE 2022 - Descuento: $12', 101, 'Banco', '2022-09-13 12:01:16', 'sin descuento', 101, '2022-09-13'),
(76, 6077, 1100, 'Liquidacion :Liquidación de Instalación', 68, 'Efectivo', '2022-09-14 09:54:58', 'Se le asigno crédito para liquidar al día siguiente ', 101, '2022-09-14'),
(77, 2596, 400, 'Mensualidad :SEPTIEMBRE 2022', 49, 'Efectivo', '2022-09-14 13:57:15', 'ERROR CAMBIO', 49, '2022-09-14'),
(78, 979, 450, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 107, 'Efectivo', '2022-09-14 15:16:49', 'Error de Cambio', 101, '2022-09-14'),
(79, 2184, 380, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'SAN', '2022-09-15 09:52:19', 'Error de registro', 101, '2022-09-15'),
(80, 5858, 450, 'Mensualidad :OCTUBRE 2022', 101, 'Efectivo', '2022-09-14 18:38:47', 'Error de registro', 101, '2022-09-15'),
(81, 6042, 450, 'Mensualidad :DICIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'SAN', '2022-09-19 10:32:04', 'Sin Recargo', 101, '2022-09-19'),
(82, 6042, 450, 'Mensualidad :NOVIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'SAN', '2022-09-19 10:31:59', 'Sin Recargo', 101, '2022-09-19'),
(83, 6042, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'SAN', '2022-09-19 10:31:50', 'Sin Recargo', 101, '2022-09-19'),
(84, 6042, 450, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'SAN', '2022-09-19 10:31:00', 'Sin Recargo', 101, '2022-09-19'),
(85, 1, 100, 'Mensualidad :JULIO 2022 - Descuento: $500', 101, 'Credito', '2022-07-13 13:39:51', 'No aplica crédito ', 101, '2022-09-19'),
(86, 1, 500, 'Mensualidad :AGOSTO 2022', 102, 'Credito', '2022-08-12 15:29:12', 'No aplica crédito ', 101, '2022-09-19'),
(87, 2048, 710, 'Otros Pagos :Adeudo de mensualidades de junio y julio 2022 con consumo', 84, 'Credito', '2022-08-31 14:33:10', 'ajuste de deuda', 49, '2022-09-19'),
(88, 2048, 710, 'Abono :liquidacion total', 24, 'Efectivo', '2022-09-19 15:28:43', 'ajuste de deuda', 49, '2022-09-19'),
(89, 2048, 660, 'Otros Pagos :liquidacion de adeudo', 24, 'Efectivo', '2022-09-19 15:45:13', 'ajuste de deuda', 49, '2022-09-19'),
(90, 1916, 1450, 'Reporte :; MATERIAL(245A4CD86142, N/A, Grapas)', 68, 'Efectivo', '2022-09-20 11:05:52', 'Pago por transferencia bancaria', 101, '2022-09-20'),
(91, 5491, 400, 'Mensualidad :FEBRERO 2023', 107, 'Efectivo', '2022-09-20 17:06:07', 'Mes gratis', 101, '2022-09-20'),
(92, 2915, 450, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 55, 'Efectivo', '2022-09-14 20:46:52', 'por disminución de paquete, se hiso pago erroneo', 101, '2022-09-21'),
(93, 2204, 450, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 31, 'Efectivo', '2022-09-17 13:52:19', 'No aplica ese pago', 101, '2022-09-22'),
(94, 2646, 150, 'Mes-Tel :OCTUBRE 2022', 107, 'Efectivo', '2022-09-26 12:27:40', 'Error de mes registrado', 101, '2022-09-26'),
(95, 1086, 380, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 107, 'Efectivo', '2022-09-15 15:49:47', 'pago removido a cliente 1436', 49, '2022-09-28'),
(96, 1436, 12000, 'Otros Pagos :60 meses de adeudo', 84, 'Credito', '2022-08-24 17:37:21', 'ajuste de crédito negociado a 2800 financiados (Negoció Fernando)', 49, '2022-09-28'),
(97, 567, 6850, 'Otros Pagos :Adeudo de 17 mensualidades pasadas con consumo de internet', 84, 'Credito', '2022-08-29 09:54:09', 'crédito negociado a 3425 (Fernando)', 49, '2022-09-28'),
(98, 567, 300, 'Abono :ABONO', 107, 'Efectivo', '2022-09-28 15:03:05', 'reajuste de pago abono y crédito', 49, '2022-09-28'),
(99, 40, 1650, 'Otros Pagos :Adeudo de 4 meses, may, jun, jul y ago 2022 se tuvo consumo', 84, 'Credito', '2022-08-26 11:17:22', 'ajuste de deuda 1200', 49, '2022-09-29'),
(100, 40, 1100, 'Abono :LIQUIDACION', 107, 'Banco', '2022-09-29 14:34:30', 'ajuste de deuda 1200', 49, '2022-09-29'),
(101, 40, 1100, 'Abono :LIQUIDACION', 107, 'Efectivo', '2022-09-29 15:51:28', 'Error de Cambio', 101, '2022-09-29'),
(102, 820, 350, 'Mensualidad :OCTUBRE 2022 - Descuento: $50', 101, 'Efectivo', '2022-09-30 12:41:22', 'Error de Cambio', 101, '2022-09-30'),
(103, 820, 400, 'Mensualidad :OCTUBRE 2022', 101, 'Banco', '2022-09-30 13:45:42', 'ERROR DE CAPTURA', 101, '2022-09-30'),
(104, 441, 400, 'Mensualidad :OCTUBRE 2022', 105, 'Banco', '2022-10-03 09:59:40', 'Error de Cantidad', 101, '2022-10-03'),
(105, 5164, 400, 'Mensualidad :OCTUBRE 2022', 107, 'Efectivo', '2022-10-03 12:06:22', 'ERROR DE CAPTURA', 101, '2022-10-03'),
(106, 4925, 300, 'Mensualidad :OCTUBRE 2022 - Descuento: $100', 107, 'Banco', '2022-10-03 12:34:32', 'Error de Cambio', 101, '2022-10-03'),
(107, 5101, 400, 'Mensualidad :OCTUBRE 2022', 107, 'Efectivo', '2022-10-03 12:48:54', 'ERROR DE CAPTURA', 101, '2022-10-03'),
(108, 555, 50, 'Mensualidad :SEPTIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio) - Descuento: $400', 105, 'Efectivo', '2022-10-03 13:29:47', 'ERROR DE CAPTURA', 101, '2022-10-03'),
(109, 156, 250, 'Mensualidad :OCTUBRE 2022', 107, 'Efectivo', '2022-10-03 14:28:43', 'ERROR DE CAPTURA', 101, '2022-10-03'),
(110, 3336, 400, 'Mensualidad :OCTUBRE 2022', 105, 'Banco', '2022-10-03 15:54:47', 'ERROR DE CAPTURA', 101, '2022-10-03'),
(111, 4562, 400, 'Mensualidad :ENERO 2022', 29, 'Efectivo', '2022-10-03 14:56:49', 'error de año', 101, '2022-10-03'),
(112, 4562, 400, 'Mensualidad :FEBRERO 2022', 29, 'Efectivo', '2022-10-03 14:57:05', 'error de año', 101, '2022-10-03'),
(113, 4801, 500, 'Mensualidad :OCTUBRE 2022', 105, 'Efectivo', '2022-10-03 13:55:51', 'Error de Cambio', 101, '2022-10-03'),
(114, 6040, 400, 'Mensualidad :MARZO 2023', 29, 'Efectivo', '2022-10-03 15:50:09', 'es cajero no le puso descuento por 6 meses', 49, '2022-10-04'),
(115, 4852, 460, 'Mensualidad :MARZO 2023', 29, 'Efectivo', '2022-10-03 15:58:20', 'el cajero no le puso descuento por 6 meses de pago', 49, '2022-10-04'),
(116, 6040, 0, 'Mensualidad :MARZO 2022 - Descuento: $400', 84, 'Efectivo', '2022-10-04 09:15:11', 'error de año', 49, '2022-10-04'),
(117, 6040, 400, 'Mensualidad :MARZO 2023', 84, 'Efectivo', '2022-10-04 09:17:28', 'DEBE SER CORTECIA ', 28, '2022-10-04'),
(118, 1791, 150, 'Mensualidad :OCTUBRE 2022', 105, 'Efectivo', '2022-10-04 12:06:07', 'Error de tipo', 101, '2022-10-04'),
(119, 5399, 360, 'Mensualidad :OCTUBRE 2022 - Descuento: $40', 47, 'Efectivo', '2022-10-04 13:05:00', 'por ajuste de paquete', 101, '2022-10-04'),
(120, 5567, 150, 'Mes-Tel :OCTUBRE 2022', 105, 'Banco', '2022-10-04 16:55:06', 'Error de mes', 101, '2022-10-05'),
(121, 80, 400, 'Mensualidad :OCTUBRE 2022', 105, 'Banco', '2022-10-05 12:10:32', 'ERROR DE CAPTURA', 101, '2022-10-05'),
(122, 3351, 330, 'Mensualidad :OCTUBRE 2022', 105, 'Banco', '2022-10-05 12:25:41', 'Cliente Erroneo', 101, '2022-10-05'),
(123, 2345, 360, 'Mensualidad :OCTUBRE 2022', 105, 'Efectivo', '2022-10-05 12:46:37', 'Error de Cambio, cliente decide pagar con tarjeta', 101, '2022-10-05'),
(124, 6124, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 46, 'Efectivo', '2022-10-05 18:11:25', 'Gabriel recargo no aplica', 10, '2022-10-05'),
(125, 6036, 360, 'Mensualidad :OCTUBRE 2022', 105, 'Efectivo', '2022-10-06 09:17:37', 'Error de Cambio', 101, '2022-10-06'),
(126, 5587, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 28, 'Credito', '2022-10-06 13:52:04', 'ERROR A CAPTURAR PROMESA DE PAGO', 28, '2022-10-06'),
(127, 3704, 360, 'Mensualidad :OCTUBRE 2022', 64, 'Efectivo', '2022-10-04 18:06:05', 'Cliente aun no paga', 101, '2022-10-06'),
(128, 2128, 500, 'Mensualidad :NOVIEMBRE 2022', 107, 'Efectivo', '2022-10-06 15:12:42', 'CLIENTE DECIDE PAGARA CON TAJETA', 101, '2022-10-06'),
(129, 1906, 700, 'Mensualidad :SEPTIEMBRE 2023', 47, 'Efectivo', '2022-09-24 18:21:27', 'Error de mes', 101, '2022-10-06'),
(130, 4742, 605, 'Reporte :; MATERIAL(N/A, 22234X6010660, )', 26, 'Efectivo', '2022-10-06 14:31:54', 'por duplicado', 101, '2022-10-06'),
(131, 579, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 107, 'Banco', '2022-10-07 13:48:32', 'Cliente Erroneo', 101, '2022-10-07'),
(132, 5630, 460, 'Mensualidad :OCTUBRE 2022', 101, 'SAN', '2022-09-07 10:13:15', 'ERROR DE CAPTURA', 101, '2022-10-07'),
(133, 4014, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 46, 'Efectivo', '2022-10-08 11:19:13', 'Error de registro', 101, '2022-10-08'),
(134, 831, 400, 'Abono :liquidacion de instalacion ', 105, 'Efectivo', '2022-10-08 12:03:50', 'Error de Cambio', 101, '2022-10-08'),
(135, 2222, 450, 'Mensualidad :NOVIEMBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 46, 'Efectivo', '2022-10-09 20:02:38', 'para nov', 10, '2022-10-09'),
(136, 1721, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 101, 'Efectivo', '2022-10-10 09:17:48', 'Cliente solicito Credito para el 17 de octubre', 101, '2022-10-10'),
(137, 5020, 450, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 107, 'Efectivo', '2022-10-10 16:53:23', 'Error de Cambio', 101, '2022-10-10'),
(138, 1301, 380, 'Mensualidad :OCTUBRE 2022 + RECARGO (Reconexion o Pago Tardio)', 49, 'Efectivo', '2022-10-10 19:29:11', 'error de tipo de cambio', 49, '2022-10-10'),
(139, 5926, 400, 'Mensualidad :OCTUBRE 2022', 43, 'Efectivo', '2022-10-11 08:52:22', 'Error de Cambio', 101, '2022-10-11'),
(140, 3367, 3300, 'Mensualidad :SEPTIEMBRE 2022', 106, 'Efectivo', '2022-08-29 12:42:14', 'anualidad mal registrada', 101, '2022-10-12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pagos_borrados`
--
ALTER TABLE `pagos_borrados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pagos_borrados`
--
ALTER TABLE `pagos_borrados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
