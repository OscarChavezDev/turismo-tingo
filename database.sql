-- Base de datos de Tingo María Turismo
-- Importar con:  mysql -u root -p < database.sql

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS turismo_tingo
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE turismo_tingo;

DROP TABLE IF EXISTS lugares;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE categorias (
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE lugares (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id    INT UNSIGNED NOT NULL,
  nombre          VARCHAR(120) NOT NULL,
  resumen         VARCHAR(255) NOT NULL,
  descripcion     TEXT NOT NULL,
  ubicacion       VARCHAR(200) NOT NULL DEFAULT '',
  recomendaciones TEXT NULL,
  imagen_url      VARCHAR(500) NULL,
  destacado       TINYINT(1) NOT NULL DEFAULT 0,
  creado_en       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_lugar_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

CREATE TABLE usuarios (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario    VARCHAR(40) NOT NULL UNIQUE,
  nombre     VARCHAR(80) NOT NULL,
  clave_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO categorias (id, nombre) VALUES
  (1, 'Naturaleza'),
  (2, 'Cataratas y balnearios'),
  (3, 'Cuevas'),
  (4, 'Gastronomía'),
  (5, 'Festividades');

-- Usuario administrador inicial:  admin / Tingo2026!
INSERT INTO usuarios (usuario, nombre, clave_hash) VALUES
  ('admin', 'Administrador', '$2y$10$BNP0mzilQOQewo/lDNKVH.tQPMtm3CcdEvPS.N.8C.LMSabwPO3QC');

INSERT INTO lugares (categoria_id, nombre, resumen, descripcion, ubicacion, recomendaciones, imagen_url, destacado) VALUES
(1, 'Parque Nacional Tingo María',
 'Una de las áreas naturales protegidas más antiguas del Perú, hogar de La Bella Durmiente.',
 'Creado en 1965, el parque protege bosques de selva alta, quebradas y una gran variedad de aves, mariposas y orquídeas. Dentro de sus límites se encuentran la cadena montañosa de La Bella Durmiente y la Cueva de las Lechuzas, los dos símbolos de la ciudad.',
 'A pocos kilómetros del centro de Tingo María',
 'Lleva repelente, agua y calzado con buen agarre. Los senderos pueden estar resbalosos en época de lluvias.',
 'https://portal.andina.pe/EDPFotografia3/thumbnail/2018/08/14/000525077M.jpg', 1),
(1, 'La Bella Durmiente',
 'La cadena de cerros cuya silueta dibuja a una mujer recostada mirando al cielo.',
 'Es el ícono de Tingo María. Desde la ciudad, el perfil de los cerros se ve como una mujer dormida. Una leyenda local cuenta la historia de una princesa que quedó dormida para siempre en la selva. Se aprecia mejor al amanecer y al atardecer, cuando la neblina se despeja.',
 'Visible desde la ciudad, sobre la margen del río Huallaga',
 'Los mejores puntos para la foto son el puente sobre el río Huallaga y el malecón.',
 'https://commons.wikimedia.org/wiki/Special:FilePath/Bella_Durmiente_TM-Peru-1.jpg?width=1024', 1),
(3, 'Cueva de las Lechuzas',
 'Una caverna enorme habitada por guácharos, con estalactitas y estalagmitas.',
 'A pesar de su nombre, las aves que viven en la cueva no son lechuzas sino guácharos, aves nocturnas que se orientan con sonidos. La entrada es amplia y un pasadizo de madera permite recorrer el primer tramo y ver las formaciones de roca.',
 'Dentro del Parque Nacional Tingo María',
 'Lleva linterna y evita hacer ruido fuerte para no alterar a las aves.',
 'https://portal.andina.pe/EDPFotografia3/thumbnail/2018/08/15/000525458M.jpg', 1),
(2, 'Cueva de las Pavas',
 'Una quebrada de aguas cristalinas entre paredes de roca, ideal para bañarse.',
 'No es una cueva propiamente dicha, sino un cañón angosto cubierto de vegetación por donde corre un riachuelo de agua fresca. Es uno de los lugares preferidos por las familias para pasar el día.',
 'Al sur de la ciudad, por la carretera hacia Huánuco',
 'Ve temprano los fines de semana, porque se llena. Lleva ropa de baño y sandalias.',
 NULL, 0),
(2, 'Catarata Santa Carmen',
 'Una caída de agua rodeada de bosque, con pozas para refrescarse.',
 'Se llega por un sendero corto en medio de la vegetación. El agua cae sobre una poza donde es posible bañarse. El caudal aumenta bastante en temporada de lluvias.',
 'En las afueras de Tingo María',
 'Pregunta por el estado del sendero si ha llovido el día anterior.',
 'https://portal.andina.pe/EDPFotografia3/thumbnail/2021/10/13/000815866M.jpg', 0),
(2, 'Velo de las Ninfas',
 'Una catarata cuyo chorro se abre como un velo sobre la roca.',
 'El agua desciende formando una cortina fina que da nombre al lugar. La poza al pie de la caída es de poca profundidad y el entorno conserva mucha vegetación.',
 'En las afueras de Tingo María',
 'Lleva calzado que se pueda mojar y una bolsa para tu basura.',
 NULL, 0),
(1, 'Laguna El Milagro',
 'Una laguna tranquila rodeada de selva, para paseos en bote.',
 'Sus aguas calmadas permiten pasear en bote y observar aves. Alrededor hay espacios para descansar y probar comida de la zona.',
 'Al norte de Tingo María',
 'Usa bloqueador y sombrero, hay poca sombra sobre el agua.',
 'https://portal.andina.pe/EDPFotografia3/thumbnail/2021/10/13/000815867M.jpg', 0),
(1, 'Jardín Botánico de Tingo María',
 'Un recorrido corto entre árboles y plantas nativas de la Amazonía, dentro de la ciudad.',
 'Reúne especies forestales, frutales y ornamentales de la selva peruana. Es una buena primera parada para conocer la flora de la región sin salir de la ciudad.',
 'En la ciudad de Tingo María',
 'Se recorre en menos de una hora. Ve por la mañana, cuando hace menos calor.',
 NULL, 0),
(4, 'Juane',
 'Arroz sazonado con gallina, envuelto y cocido en hoja de bijao.',
 'Es el plato más representativo de la selva peruana. El arroz se mezcla con huevo, aceituna y presa de gallina, se envuelve en hojas de bijao y se hierve. Es infaltable en la fiesta de San Juan.',
 'Restaurantes y mercados de la ciudad',
 'Acompáñalo con ají de cocona.',
 'https://portal.andina.pe/EDPFotografia3/thumbnail/2021/06/23/000784205M.jpg', 0),
(4, 'Tacacho con cecina',
 'Plátano verde asado y machacado, servido con carne de cerdo ahumada.',
 'El tacacho se prepara con plátano verde asado, machacado con manteca. Se sirve con cecina, que es carne de cerdo seca y ahumada, y muchas veces con chorizo regional.',
 'Restaurantes y mercados de la ciudad',
 'Pídelo en el desayuno, como lo come la gente de la zona.',
 'https://portal.andina.pe/EDPFotografia3/thumbnail/2021/10/17/000816867M.jpg', 0),
(5, 'Fiesta de San Juan',
 'La celebración más grande de la Amazonía peruana, cada 24 de junio.',
 'Las familias salen a los ríos y balnearios a compartir juanes, música y baile. Durante la semana hay pandillas, ferias y actividades culturales en toda la ciudad.',
 'Toda la ciudad y sus balnearios',
 'Reserva alojamiento con anticipación, es temporada alta.',
 NULL, 0),
(5, 'Aniversario de Tingo María',
 'La ciudad celebra su fundación cada 15 de octubre.',
 'La semana de aniversario incluye desfiles, ferias gastronómicas, conciertos y concursos. Es un buen momento para conocer las costumbres y los productos de la provincia de Leoncio Prado.',
 'Centro de la ciudad',
 'Consulta el programa oficial de la municipalidad para las fechas de cada actividad.',
 'https://portal.andina.pe/EDPfotografia3/Thumbnail/2021/10/13/000815861M.webp', 0);
