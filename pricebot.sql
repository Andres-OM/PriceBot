-- Creacion de la base de datos
CREATE DATABASE IF NOT EXISTS pricebot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pricebot`;
-- -----------------------------------------------------
-- Tabla `wpfrk_posts`
-- Estructura estándar de WordPress para almacenar posts, páginas, productos, etc. Es un ejemplo de una tabla de una web en wordpres que almacena los precios de sus productos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wpfrk_posts` (
  `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_author` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `post_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_date_gmt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_modified_gmt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_content_filtered` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `guid` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` INT(11) NOT NULL DEFAULT 0,
  `post_type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` BIGINT(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  INDEX `post_name` (`post_name`(191) ASC) VISIBLE,
  INDEX `type_status_date` (`post_type` ASC, `post_status` ASC, `post_date` ASC, `ID` ASC) VISIBLE,
  INDEX `post_parent` (`post_parent` ASC) VISIBLE,
  INDEX `post_author` (`post_author` ASC) VISIBLE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla `wpfrk_postmeta`
-- Estructura estándar de WordPress para almacenar metadatos de los posts.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wpfrk_postmeta` (
  `meta_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `meta_value` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  INDEX `post_id` (`post_id` ASC) VISIBLE,
  INDEX `meta_key` (`meta_key`(191) ASC) VISIBLE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- -------------
-- Insertar datos
-- -----------------------------------------------------
-- INSERTS PARA wpfrk_posts
-- -----------------------------------------------------
INSERT INTO `wpfrk_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`, `to_ping`, `pinged`, `post_content_filtered`, `post_password`, `comment_status`, `ping_status`, `post_parent`, `guid`, `menu_order`, `post_mime_type`, `comment_count`) VALUES
(202, 1, NOW(), NOW(), 'Sumérgete en una isla desierta y crea tu propio paraíso en Animal Crossing: New Horizons para Nintendo Switch.', 'Animal Crossing: New Horizons para Nintendo Switch', 'El popular juego de simulación de vida Animal Crossing: New Horizons.', 'publish', 'animal-crossing-new-horizons-nintendo-switch', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=202', 0, '', 0),
(203, 1, NOW(), NOW(), 'Espeon llega en su versión más adorable con esta figura Pop! Games de 9 cm con acabado flocked. Su diseño captura a la perfección la elegancia y misticismo de este Pokémon psíquico.', 'POKEMON POP! GAMES VINYL FIGURA FLOCKED ESPEON 9 CM', 'Figura Funko Pop de Espeon, edición flocked, Pokémon.', 'publish', 'pokemon-pop-games-vinyl-figura-flocked-espeon-9-cm', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=203', 0, '', 0),
(204, 1, NOW(), NOW(), 'Descubre la Mattel - Espada encantada Minecraft, un accesorio de juego de tamaño real que permite recrear las emocionantes aventuras del famoso mundo pixelado.', 'Mattel - Espada encantada minecraft', 'Espada de Minecraft de tamaño real, ideal para recrear aventuras.', 'publish', 'mattel-espada-encantada-minecraft', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=204', 0, '', 0),
(205, 1, NOW(), NOW(), 'Peluche de Stitch de Lilo & Stitch, Peluche Electrónico de 45 cm, con 100+ Combinaciones de Acciones y Sonidos.', 'Disney - Stitch FX, Peluche de Stitch Electrónico', 'Peluche interactivo de Stitch FX con sonidos y reacciones.', 'publish', 'disney-stitch-fx-peluche-electronico', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=205', 0, '', 0),
(206, 1, NOW(), NOW(), 'Maqueta para Adultos de Nave Estelar Coleccionable LEGO Star Wars 75375 Halcón Milenario, con Base y Placa.', 'LEGO Star Wars 75375 Halcón Milenario', 'Increíble maqueta coleccionable del Halcón Milenario de Star Wars.', 'publish', 'lego-star-wars-75375-halcon-milenario', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=206', 0, '', 0),
(207, 1, NOW(), NOW(), 'Grand Theft Auto V Edición Premium para PlayStation 4, incluye el juego base y el Criminal Enterprise Starter Pack.', 'Grand Theft Auto V Edición Premium PS4', 'El exitoso juego Grand Theft Auto V en su edición premium para PS4.', 'publish', 'grand-theft-auto-v-premium-edition-ps4', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=207', 0, '', 0),
(208, 1, NOW(), NOW(), 'Peluche de Todoroki de 27 cm, fiel representación del personaje de My Hero Academia.', 'MY HERO ACADEMIA TODOROKI PELUCHE 27 CM', 'Peluche coleccionable de Todoroki de My Hero Academia.', 'publish', 'my-hero-academia-todoroki-peluche-27-cm', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=208', 0, '', 0),
-- NUEVO Producto: Mattel Games Juego de Cartas UNO Flex (ID 209)
(209, 1, NOW(), NOW(), 'Juego de Cartas UNO Flex de Mattel Games, ideal para toda la familia y grupos de 2 a 8 jugadores, recomendado para mayores de 7 años. Modelo HMY99.', 'Mattel Games Juego de Cartas UNO Flex', 'El clásico juego UNO con un nuevo giro flexible.', 'publish', 'mattel-games-uno-flex', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=209', 0, '', 0),
-- NUEVO Producto: LEGO Star Wars 75402 Caza Estelar ARC-170 (ID 210)
(210, 1, NOW(), NOW(), 'LEGO Star Wars 75402 Caza Estelar ARC-170 de Juguete con 4 Minifiguras, incluyendo Pilotos Clon y Droide R4-P44. Ideal para fans de La Venganza de los Sith.', 'LEGO Star Wars 75402 Caza Estelar ARC-170', 'Caza Estelar ARC-170 de LEGO Star Wars con minifiguras.', 'publish', 'lego-star-wars-75402-caza-estelar-arc-170', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=210', 0, '', 0),
-- NUEVO Producto: Jupesa Cubo Mirror 3x3x3 (ID 211)
(211, 1, NOW(), NOW(), 'Cubo Mirror 3x3x3 de la marca Jupesa, un rompecabezas desafiante y divertido.', 'Jupesa Cubo Mirror 3x3x3', 'Cubo Mirror 3x3x3 para entusiastas de los puzzles.', 'publish', 'jupesa-cubo-mirror-3x3x3', 'product', '', '', '', '', 'open', 'open', 0, 'https://pricebot.example.com/?post_type=product&p=211', 0, '', 0);

-- -----------------------------------------------------
-- INSERTS PARA wpfrk_postmeta
-- -----------------------------------------------------
INSERT INTO `wpfrk_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
-- Metadatos para Animal Crossing: New Horizons (post_id = 202)
(202, '_sku', '0045496425395'), (202, '_price', '49.90'), (202, '_regular_price', '49.90'), (202, '_sale_price', ''), (202, '_stock_status', 'instock'), (202, '_manage_stock', 'yes'), (202, '_stock', '15'), (202, '_wcj_purchase_price', '37.50'),
-- Metadatos para POKEMON POP! ESPEON (post_id = 203)
(203, '_sku', '889698691321'), (203, '_price', '18.95'), (203, '_regular_price', '18.95'), (203, '_sale_price', ''), (203, '_stock_status', 'instock'), (203, '_manage_stock', 'yes'), (203, '_stock', '25'), (203, '_wcj_purchase_price', '12.00'),
-- Metadatos para Mattel - Espada encantada minecraft (post_id = 204)
(204, '_sku', '0194735276417'), (204, '_price', '19.95'), (204, '_regular_price', '19.95'), (204, '_sale_price', ''), (204, '_stock_status', 'instock'), (204, '_manage_stock', 'yes'), (204, '_stock', '30'), (204, '_wcj_purchase_price', '11.50'),
-- Metadatos para Disney - Stitch FX Peluche (post_id = 205)
(205, '_sku', '8056379174011'), (205, '_price', '105.00'), (205, '_regular_price', '105.00'), (205, '_sale_price', ''), (205, '_stock_status', 'instock'), (205, '_manage_stock', 'yes'), (205, '_stock', '8'), (205, '_wcj_purchase_price', '75.00'),
-- Metadatos para LEGO Star Wars Halcón Milenario (post_id = 206)
(206, '_sku', '5702017584348'), (206, '_price', '79.99'), (206, '_regular_price', '79.99'), (206, '_sale_price', ''), (206, '_stock_status', 'instock'), (206, '_manage_stock', 'yes'), (206, '_stock', '5'), (206, '_wcj_purchase_price', '60.00'),
-- Metadatos para Grand Theft Auto V Edición Premium PS4 (post_id = 207)
(207, '_sku', '5026555424295'), (207, '_price', '22.50'), (207, '_regular_price', '22.50'), (207, '_sale_price', ''), (207, '_stock_status', 'instock'), (207, '_manage_stock', 'yes'), (207, '_stock', '50'), (207, '_wcj_purchase_price', '15.00'),
-- Metadatos para MY HERO ACADEMIA TODOROKI PELUCHE (post_id = 208)
(208, '_sku', '8436591581987'), (208, '_price', '24.95'), (208, '_regular_price', '24.95'), (208, '_sale_price', ''), (208, '_stock_status', 'instock'), (208, '_manage_stock', 'yes'), (208, '_stock', '18'), (208, '_wcj_purchase_price', '16.00'),

-- NUEVOS Metadatos para Mattel Games Juego de Cartas UNO Flex (post_id = 209)
(209, '_sku', '0194735135967'), (209, '_price', '9.99'), (209, '_regular_price', '9.99'), (209, '_sale_price', ''), (209, '_stock_status', 'instock'), (209, '_manage_stock', 'yes'), (209, '_stock', '40'), (209, '_wcj_purchase_price', '6.50'),
-- NUEVOS Metadatos para LEGO Star Wars 75402 Caza Estelar ARC-170 (post_id = 210)
(210, '_sku', '5702017817460'), (210, '_price', '49.95'), (210, '_regular_price', '49.95'), (210, '_sale_price', ''), (210, '_stock_status', 'instock'), (210, '_manage_stock', 'yes'), (210, '_stock', '12'), (210, '_wcj_purchase_price', '35.00'),
-- NUEVOS Metadatos para Jupesa Cubo Mirror 3x3x3 (post_id = 211)
(211, '_sku', '8422878783212'), (211, '_price', '11.50'), (211, '_regular_price', '11.50'), (211, '_sale_price', ''), (211, '_stock_status', 'instock'), (211, '_manage_stock', 'yes'), (211, '_stock', '30'), (211, '_wcj_purchase_price', '7.00');