-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-02-2024 a las 17:00:30
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jabones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `email` varchar(20) NOT NULL,
  `contrasena` varchar(8) NOT NULL,
  `nombre` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`email`, `contrasena`, `nombre`) VALUES
('chc0089@gmail.com', '1234', 'Senua');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cesta`
--

CREATE TABLE `cesta` (
  `cestaID` int(2) NOT NULL,
  `email` varchar(20) NOT NULL,
  `fechaCreacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `email` varchar(20) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `cp` varchar(5) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `contrasena` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`email`, `nombre`, `direccion`, `cp`, `telefono`, `contrasena`) VALUES
('aaaa@gmail.com', 'aa', 'aaaa', '24567', '670644812', ''),
('sasha@gmail.com', 'Sasha', 'C/ loquesea, 4', '28300', '667745321', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itemcesta`
--

CREATE TABLE `itemcesta` (
  `itemCestaID` int(2) NOT NULL,
  `cestaID` int(2) NOT NULL,
  `productoID` int(2) NOT NULL,
  `cantidad` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itempedido`
--

CREATE TABLE `itempedido` (
  `itemPedidoID` int(2) NOT NULL,
  `pedidoID` int(2) NOT NULL,
  `productoID` int(2) NOT NULL,
  `unidades` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `pedidoID` int(2) NOT NULL,
  `email` varchar(20) NOT NULL,
  `fechaPedido` date NOT NULL,
  `fechaEntrega` date NOT NULL,
  `totalPedido` int(11) NOT NULL,
  `entregado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `productoID` int(2) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `peso` double NOT NULL,
  `precio` double NOT NULL,
  `imagen` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`productoID`, `nombre`, `descripcion`, `peso`, `precio`, `imagen`) VALUES
(1, 'Light-up Mushroom Soap', 'Qué hace: contiene tu jabón de manos suave y espumoso favorito.\n\nTe dice exactamente cuánto tiempo debes lavarte las manos y se ilumina.', 0.5, 19.95, 'imagenes/028000371.jpg'),
(2, 'Black Cherry Merlot ', 'A qué huele: una copa rica y afrutada de rojo dulce.\r\n\r\nNotas de fragancia: cereza oscura, frambuesa negra y suntuoso merlot.', 0.5, 7.95, 'imagenes/026686014.jpg'),
(3, 'Palo Santo & Sage ', 'A qué huele: una renovación limpiadora terrosa, amaderada.\r\n\r\nNotas olfativas: salvia, palo santo y maderas ambarinas.', 0.5, 7.95, 'imagenes/026790483.jpg'),
(4, 'Coconut Sandalwood ', 'A qué huele: a playa de madera flotante al anochecer.\r\n\r\nNotas olfativas: palma de coco, sándalo lujoso, almizcle cálido y jazmín.', 0.5, 8.95, 'imagenes/026790543.jpg'),
(5, 'Cozy Vanilla Almond ', 'A qué huele: cálido, a nuez y muy dulce.\r\n\r\nNotas olfativas: orquídea de vainilla, almendras azucaradas y almizcle dulce.', 0.5, 7.95, 'imagenes/026686011.jpg'),
(6, 'Vanilla Birch ', 'A qué huele: un tranquilo paseo por el bosque.\r\n\r\nNotas de fragancia: abedul blanco, vainilla de Madagascar y sándalo cálido.', 0.5, 7.95, 'imagenes/026774721.jpg'),
(7, 'Sea and Sandstone', 'A qué huele: las relajantes olas del océano rompiendo contra las rocas junto al mar.', 0.3, 7.95, 'imagenes/026686018.jpg'),
(8, 'Fiji White Sands ', 'A qué huele: el día de playa más dulce y luminoso de todos los tiempos.\r\nNotas olfativas: caña de azúcar recién cortada, nectarina blanca y sándalo.\r\n', 0.3, 8.95, 'imagenes/028000208.jpg'),
(9, 'Raspberry Tangerine ', 'A qué huele: una mezcla de frutas cítricas y brillantes.\r\nNotas de fragancia: frambuesas silvestres, mandarina fresca y ralladura de limón.', 0.3, 7.95, 'imagenes/026793203.jpg'),
(10, 'Crystal Blue Coast', 'A qué huele: playas de arena blanca y agua cristalina y deslumbrante.\r\nNotas de fragancia: palmeras ventosas y manzana fresca', 0.3, 7.95, 'imagenes/026790487.jpg'),
(11, 'White T-Shirt ', 'A qué huele: tu camiseta favorita, recién lavada, colgada para secar en un prado soleado. Notas de fragancia: pera crujiente y sándalo suave.', 0.5, 8.95, 'imagenes/026779862.jpg'),
(12, 'Black Oak & Patchouli', 'A qué huele: un escape dulce y místico.\r\nNotas de fragancia: azúcar hilado, ámbar dorado y pachulí rubio.', 0.5, 8.95, 'imagenes/026790765.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `cesta`
--
ALTER TABLE `cesta`
  ADD PRIMARY KEY (`cestaID`),
  ADD KEY `email_ibfk_1` (`email`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `itemcesta`
--
ALTER TABLE `itemcesta`
  ADD PRIMARY KEY (`itemCestaID`),
  ADD KEY `producto_ibfk_1` (`productoID`),
  ADD KEY `cestaID_ibfk_1` (`cestaID`);

--
-- Indices de la tabla `itempedido`
--
ALTER TABLE `itempedido`
  ADD PRIMARY KEY (`itemPedidoID`),
  ADD KEY `pedido_id_ibfk_1` (`pedidoID`),
  ADD KEY `producto_ID_ibfk_1` (`productoID`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedidoID`),
  ADD KEY `email_pedidos_ibfk_1` (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`productoID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cesta`
--
ALTER TABLE `cesta`
  MODIFY `cestaID` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itemcesta`
--
ALTER TABLE `itemcesta`
  MODIFY `itemCestaID` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itempedido`
--
ALTER TABLE `itempedido`
  MODIFY `itemPedidoID` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedidoID` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `productoID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cesta`
--
ALTER TABLE `cesta`
  ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`email`) REFERENCES `clientes` (`email`);

--
-- Filtros para la tabla `itemcesta`
--
ALTER TABLE `itemcesta`
  ADD CONSTRAINT `cestaID_ibfk_1` FOREIGN KEY (`cestaID`) REFERENCES `cesta` (`cestaID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cestaID_item_ibfk_1` FOREIGN KEY (`cestaID`) REFERENCES `cesta` (`cestaID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`productoID`) REFERENCES `productos` (`productoID`);

--
-- Filtros para la tabla `itempedido`
--
ALTER TABLE `itempedido`
  ADD CONSTRAINT `pedido_id_ibfk_1` FOREIGN KEY (`pedidoID`) REFERENCES `pedidos` (`pedidoID`),
  ADD CONSTRAINT `producto_ID_ibfk_1` FOREIGN KEY (`productoID`) REFERENCES `productos` (`productoID`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `email_pedidos_ibfk_1` FOREIGN KEY (`email`) REFERENCES `clientes` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
