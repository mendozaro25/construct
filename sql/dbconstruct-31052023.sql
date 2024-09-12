-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 31-05-2023 a las 20:52:25
-- Versión del servidor: 8.0.30
-- Versión de PHP: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbconstruct`
--
CREATE DATABASE IF NOT EXISTS `dbconstruct` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `dbconstruct`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_settings`
--

DROP TABLE IF EXISTS `ac_settings`;
CREATE TABLE `ac_settings` (
  `id` int NOT NULL,
  `s_name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dt_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ac_settings`
--

INSERT INTO `ac_settings` (`id`, `s_name`, `s_value`, `dt_updated`) VALUES
(1, 'admin_user_id', '61', '2019-10-19 05:57:53'),
(2, 'pagination_limit', '5', '2019-10-19 05:57:53'),
(3, 'include_url', NULL, '2019-10-19 05:57:53'),
(4, 'exclude_url', NULL, '2019-10-19 05:57:53'),
(5, 'img_upload_path', 'assets/upload', '2019-03-06 00:00:00'),
(6, 'assets_path', 'assets', '2019-10-19 05:57:53'),
(8, 'is_groups', '0', '2019-10-19 05:57:53'),
(9, 'groups_table', NULL, '2019-10-19 05:57:53'),
(10, 'groups_col_id', NULL, '2019-10-19 05:57:53'),
(11, 'groups_col_name', NULL, '2019-10-19 05:57:53'),
(12, 'users_table', 'rh_personal', '2019-10-19 05:57:53'),
(13, 'users_col_id', 'id', '2019-10-19 05:57:53'),
(14, 'users_col_email', 'email', '2019-10-19 05:57:53'),
(15, 'ug_table', NULL, '2019-10-19 05:57:53'),
(16, 'ug_col_user_id', NULL, '2019-10-19 05:57:53'),
(17, 'ug_col_group_id', NULL, '2019-10-19 05:57:53'),
(18, 'include_or_exclude', '0', '2019-10-19 05:57:53'),
(19, 'guest_mode', '0', '2019-10-19 05:57:53'),
(20, 'guest_group_id', NULL, '2019-10-19 05:57:53'),
(21, 'site_name', 'MPMC | Chat', '2019-10-19 05:57:53'),
(22, 'theme_colour', NULL, '2019-10-19 05:57:53'),
(23, 'site_logo', NULL, '2019-09-06 08:25:52'),
(24, 'chat_icon', NULL, '2019-09-06 08:24:20'),
(25, 'notification_type', '0', '2019-10-19 05:57:53'),
(26, 'pusher_app_id', NULL, '2019-10-19 05:57:53'),
(27, 'pusher_key', NULL, '2019-10-19 05:57:53'),
(28, 'pusher_secret', NULL, '2019-10-19 05:57:53'),
(29, 'pusher_cluster', NULL, '2019-10-19 05:57:53'),
(30, 'footer_text', 'MPMC | Chat', '2019-10-19 05:57:53'),
(31, 'footer_url', 'javascript:;', '2019-10-19 05:57:53'),
(32, 'hide_email', '0', '2019-11-13 10:44:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `id` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id`, `nombre`, `descripcion`, `created_user_id`, `created_datetime`, `updated_user_id`, `updated_datetime`, `deleted_datetime`, `status`) VALUES
(0, 'AREA NO DEFENIDA', NULL, 1, '2023-05-31 20:49:30', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

DROP TABLE IF EXISTS `asistencia`;
CREATE TABLE `asistencia` (
  `id` int NOT NULL,
  `detalle_jornada_id` int DEFAULT NULL,
  `dia` varchar(100) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '0',
  `horas_extras` int DEFAULT '0',
  `sueldo_fijo` double DEFAULT '0',
  `sueldo_horas_Extras` double DEFAULT '0',
  `sueldo_total` double DEFAULT '0',
  `t_asistencia` int DEFAULT '0',
  `t_horas_extras` int DEFAULT '0',
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `id` int NOT NULL,
  `comprador_id` int NOT NULL,
  `fecha_compra` date NOT NULL,
  `tipo_comprobante` varchar(10) NOT NULL,
  `tipo_rubro` varchar(10) DEFAULT NULL,
  `serie_numero` varchar(50) DEFAULT NULL,
  `fecha_comprobante` date DEFAULT NULL,
  `t_subtotal` double DEFAULT NULL DEFAULT 0,
  `t_impuestos` double DEFAULT NULL DEFAULT 0,
  `t_descuento` double DEFAULT NULL DEFAULT 0,
  `t_importe_total` double DEFAULT NULL DEFAULT 0,
  `observacion` varchar(150) DEFAULT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `constante`
--

DROP TABLE IF EXISTS `constante`;
CREATE TABLE `constante` (
  `idconstante` int NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `valor` varchar(300) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  `orden` int DEFAULT NULL,
  `estado` bit(1) DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `constante`
--

INSERT INTO `constante` (`idconstante`, `codigo`, `valor`, `descripcion`, `orden`, `estado`) VALUES
(4, NULL, NULL, 'Tipo Documento Persona', 0, b'1'),
(4, 'RUC', 'RUC', NULL, 1, b'1'),
(4, 'DNI', 'DNI', NULL, 2, b'1'),
(4, 'S/D', 'SIN DOCUMENTO', NULL, 3, b'1'),
(5, NULL, NULL, 'Tipo Transporte', 0, b'1'),
(5, 'PUB', 'Público', NULL, 1, b'1'),
(5, 'PRI', 'Privado', NULL, 2, b'1'),
(7, NULL, NULL, 'Tipo de Producto', 0, b'1'),
(7, '1', 'Producto', 'Prod', 1, b'1'),
(7, '2', 'Servicio', 'Serv', 1, b'1'),
(11, NULL, NULL, 'Estado de Registros', NULL, b'1'),
(11, '1', 'Activo', NULL, 1, b'1'),
(11, '0', 'Inactivo', NULL, 2, b'1'),
(23, '', '-', 'Condicion Filtro', 0, b'1'),
(23, '=', '=', NULL, 1, b'1'),
(23, '>', '>', NULL, 2, b'1'),
(23, '<', '<', NULL, 3, b'1'),
(23, '>=', '>=', NULL, 4, b'1'),
(23, '<=', '<=', NULL, 5, b'1'),
(24, NULL, NULL, 'Order By Tipo', NULL, b'1'),
(24, 'ASC', 'ASC', NULL, 1, b'1'),
(24, 'DESC', 'DESC', NULL, 2, b'1'),
(27, NULL, NULL, 'Meses', NULL, b'1'),
(27, '01', 'Enero', NULL, 0, b'1'),
(27, '02', 'Febrero', NULL, 1, b'1'),
(27, '03', 'Marzo', NULL, 2, b'1'),
(27, '04', 'Abril', NULL, 3, b'1'),
(27, '05', 'Mayo', NULL, 4, b'1'),
(27, '06', 'Junio', NULL, 5, b'1'),
(27, '07', 'Julio', NULL, 6, b'1'),
(27, '08', 'Agosto', NULL, 7, b'1'),
(27, '09', 'Setiembre', NULL, 8, b'1'),
(27, '10', 'Octubre', NULL, 9, b'1'),
(27, '11', 'Noviembre', NULL, 10, b'1'),
(27, '12', 'Diciembre', NULL, 11, b'1'),
(28, NULL, NULL, 'Tipo de periodo de pago', 0, b'1'),
(28, 'M', 'MENSUAL', '30', 1, b'1'),
(28, 'A', 'ANUAL', '365', 2, b'1'),
(30, NULL, NULL, 'Tipo de forma de pago', 0, b'1'),
(30, 'E', 'EFECTIVO', NULL, 1, b'1'),
(30, 'T', 'TRANSFERENCIA', NULL, 2, b'1'),
(30, 'Y', 'YAPE', NULL, 3, b'1'),
(30, 'P', 'PLIN', NULL, 4, b'1'),
(31, NULL, NULL, 'Tipo de Monedas', 0, b'1'),
(31, 'PEN', 'Soles', 'S/.', 1, b'1'),
(31, 'USD', 'Dólar Americano', '$/.', 2, b'1'),
(32, NULL, NULL, 'Tipo de Bancos', 0, b'1'),
(32, 'BCP', 'BANCO DE CRÉDITO DEL PERÚ', NULL, 1, b'1'),
(32, 'INT', 'INTERBANK', NULL, 2, b'1'),
(33, NULL, NULL, 'Tipo de Proveedores', 0, b'1'),
(33, 'C', 'CLIENTE', NULL, 1, b'1'),
(33, 'P', 'PROVEEDOR', NULL, 2, b'1'),
(33, 'C/P', 'CLIENTE/PROVEEDOR', NULL, 3, b'1'),
(34, NULL, NULL, 'Tipo de Rubro Compras', 0, b'1'),
(34, 'MAT', 'MATERIALES', NULL, 1, b'1'),
(34, 'HER', 'HERRAMIENTAS', NULL, 2, b'1'),
(34, 'EQP', 'EQUIPOS', NULL, 3, b'1'),
(35, NULL, NULL, 'Tipo de Comprobantes', 0, b'1'),
(35, 'FAC', 'FACTURA', NULL, 1, b'1'),
(35, 'BOL', 'BOLETA', NULL, 2, b'1'),
(35, 'S/N', 'SIN COMPROBANTE', NULL, 3, b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
CREATE TABLE `detalle_compra` (
  `id` int NOT NULL,
  `obra_id` int DEFAULT NULL,
  `compra_id` int DEFAULT NULL,
  `producto_id` int DEFAULT NULL,
  `proveedor_id` int DEFAULT NULL,
  `cantidad` double DEFAULT NULL DEFAULT 0,
  `igv` double DEFAULT NULL DEFAULT 0,
  `descuento` double DEFAULT NULL DEFAULT 0,
  `subtotal` double DEFAULT NULL DEFAULT 0,
  `total` double DEFAULT NULL DEFAULT 0,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_jornada`
--

DROP TABLE IF EXISTS `detalle_jornada`;
CREATE TABLE `detalle_jornada` (
  `id` int NOT NULL,
  `jornada_id` int DEFAULT NULL,
  `personal_id` int DEFAULT NULL,
  `obra_id` int DEFAULT NULL,
  `sueldo_fijo` double DEFAULT '0',
  `sueldo_horas_extras` double DEFAULT '0',
  `sueldo_personal_semana` double DEFAULT '0',
  `total_asistencias` int DEFAULT '0',
  `total_horas_extras` int DEFAULT '0',
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
CREATE TABLE `especialidad` (
  `id` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id`, `nombre`, `descripcion`, `created_user_id`, `created_datetime`, `updated_user_id`, `updated_datetime`, `deleted_datetime`, `status`) VALUES
(0, 'ESPECIALIDAD NO DEFENIDA', NULL, 1, '2023-05-31 20:49:30', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornada`
--

DROP TABLE IF EXISTS `jornada`;
CREATE TABLE `jornada` (
  `id` int NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `costo_jornada` double DEFAULT '0',
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra`
--

DROP TABLE IF EXISTS `obra`;
CREATE TABLE `obra` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `costo_obra` double DEFAULT '0',
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `description` tinytext,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_roles`
--

DROP TABLE IF EXISTS `permission_roles`;
CREATE TABLE `permission_roles` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

DROP TABLE IF EXISTS `personal`;
CREATE TABLE `personal` (
  `id` int NOT NULL,
  `especialidad_id` int DEFAULT '0',
  `area_id` int DEFAULT '0',
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `banco` varchar(10) DEFAULT NULL,
  `sueldo` double DEFAULT '0',
  `num_cuenta` varchar(20) DEFAULT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `id` int NOT NULL,
  `unidad_medida_id` int DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_unitario` double NOT NULL DEFAULT 0,
  `descripcion` varchar(150) DEFAULT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE `proveedor` (
  `id` int NOT NULL,
  `tipo_documento` varchar(10) DEFAULT NULL,
  `num_documento` varchar(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_proveedor` varchar(10) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `correo` varchar(60) DEFAULT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `proveedor` (`id`, `nombre`, `created_user_id`, `created_datetime`, `status`) VALUES
(1, 'SIN PROVEEDOR', 1, '2023-05-31 20:49:30', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `display_name` varchar(30) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin', 'admin', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_users`
--

DROP TABLE IF EXISTS `roles_users`;
CREATE TABLE `roles_users` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

DROP TABLE IF EXISTS `unidad_medida`;
CREATE TABLE `unidad_medida` (
  `id` int NOT NULL,
  `simbolo` varchar(5) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_user_id` int DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `updated_user_id` int DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `deleted_datetime` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `phone` varchar(9) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `dni`, `phone`, `photo`, `username`, `password`, `status`, `created_at`, `user_id`) VALUES
(1, 'JUAN', 'MENDOZA ROMERO', 'jmendozaro73@gmail.com', '71229717', '939971883', 'no_image.jpg', 'admin', '$2y$10$HxAHCedd3uLq5Tysyw7swOMuIBaWaaZFNX2lHdFbnWFe9BvTlnj2e', 1, '2023-03-31 16:22:25', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ac_settings`
--
ALTER TABLE `ac_settings`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_asistencia_detalle_jornada1_idx` (`detalle_jornada_id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_compra_compra1_idx` (`compra_id`),
  ADD KEY `fk_detalle_compra_proveedor1_idx` (`proveedor_id`),
  ADD KEY `fk_detalle_compra_producto1_idx` (`producto_id`),
  ADD KEY `fk_detalle_compra_obra1_idx` (`obra_id`);

--
-- Indices de la tabla `detalle_jornada`
--
ALTER TABLE `detalle_jornada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jornada_has_personal_personal1_idx` (`personal_id`),
  ADD KEY `fk_jornada_has_personal_jornada1_idx` (`jornada_id`),
  ADD KEY `fk_datalle_jornada_obra1_idx` (`obra_id`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jornada`
--
ALTER TABLE `jornada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `obra`
--
ALTER TABLE `obra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD PRIMARY KEY (`role_id`,`permission_id`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni_UNIQUE` (`dni`),
  ADD KEY `fk_personal_area_idx` (`area_id`),
  ADD KEY `fk_personal_especialidad1_idx` (`especialidad_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_concepto_unidad_medida1_idx` (`unidad_medida_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `num_documento_UNIQUE` (`num_documento`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_user_roles_role_Name` (`name`);

--
-- Indices de la tabla `roles_users`
--
ALTER TABLE `roles_users`
  ADD PRIMARY KEY (`user_id`,`role_id`);

--
-- Indices de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ac_settings`
--
ALTER TABLE `ac_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_jornada`
--
ALTER TABLE `detalle_jornada`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jornada`
--
ALTER TABLE `jornada`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `obra`
--
ALTER TABLE `obra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
