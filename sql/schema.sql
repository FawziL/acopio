CREATE DATABASE IF NOT EXISTS acopio_venezuela
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE acopio_venezuela;

-- ===== TABLAS =====

CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    iso_3166_2 VARCHAR(4)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    UNIQUE KEY uk_municipio (estado_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE parroquias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    municipio_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    UNIQUE KEY uk_parroquia (municipio_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE centros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    municipio_id INT NOT NULL,
    parroquia_id INT NULL,
    direccion TEXT NOT NULL,
    direccion_hash CHAR(40) UNIQUE NOT NULL,
    foto_url VARCHAR(500),
    telefono VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (parroquia_id) REFERENCES parroquias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    centro_id INT NOT NULL,
    item VARCHAR(200) NOT NULL,
    tipo ENUM('falta', 'sobra') NOT NULL,
    cantidad VARCHAR(100) NOT NULL DEFAULT '',
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (centro_id) REFERENCES centros(id) ON DELETE CASCADE,
    KEY idx_inventario_centro (centro_id, activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== ESTADOS DE VENEZUELA =====

INSERT INTO estados (nombre, iso_3166_2) VALUES
('Distrito Capital',       'VE-A'),
('Amazonas',                'VE-Z'),
('Anzoátegui',              'VE-B'),
('Apure',                   'VE-C'),
('Aragua',                  'VE-D'),
('Barinas',                 'VE-E'),
('Bolívar',                 'VE-F'),
('Carabobo',                'VE-G'),
('Cojedes',                 'VE-H'),
('Delta Amacuro',           'VE-Y'),
('Falcón',                  'VE-I'),
('Guárico',                 'VE-J'),
('Lara',                    'VE-K'),
('Mérida',                  'VE-L'),
('Miranda',                 'VE-M'),
('Monagas',                 'VE-N'),
('Nueva Esparta',           'VE-O'),
('Portuguesa',              'VE-P'),
('Sucre',                   'VE-R'),
('Táchira',                 'VE-S'),
('Trujillo',                'VE-T'),
('La Guaira',               'VE-W'),
('Yaracuy',                 'VE-U'),
('Zulia',                   'VE-V');

-- ===== MUNICIPIOS (principales) =====

-- Distrito Capital (1)
INSERT INTO municipios (estado_id, nombre) VALUES
(1, 'Libertador');

-- Amazonas (2)
INSERT INTO municipios (estado_id, nombre) VALUES
(2, 'Atures'),
(2, 'Atabapo'),
(2, 'Autana'),
(2, 'Manapiare'),
(2, 'Maroa'),
(2, 'Río Negro');

-- Anzoátegui (3)
INSERT INTO municipios (estado_id, nombre) VALUES
(3, 'Anaco'),
(3, 'Aragua'),
(3, 'Bolívar'),
(3, 'Bruzual'),
(3, 'Cajigal'),
(3, 'Carvajal'),
(3, 'Freites'),
(3, 'Guanipa'),
(3, 'Guanta'),
(3, 'Independencia'),
(3, 'Libertad'),
(3, 'McGregor'),
(3, 'Maturín'),
(3, 'Miranda'),
(3, 'Monagas'),
(3, 'Peñalver'),
(3, 'Píritu'),
(3, 'San Juan de Capistrano'),
(3, 'Santa Ana'),
(3, 'Simón Bolívar'),
(3, 'Simón Rodríguez'),
(3, 'Sotillo');

-- Apure (4)
INSERT INTO municipios (estado_id, nombre) VALUES
(4, 'Achaguas'),
(4, 'Biruaca'),
(4, 'Muñoz'),
(4, 'Páez'),
(4, 'Pedro Camejo'),
(4, 'Rómulo Gallegos'),
(4, 'San Fernando');

-- Aragua (5)
INSERT INTO municipios (estado_id, nombre) VALUES
(5, 'Bolívar'),
(5, 'Camatagua'),
(5, 'Francisco Linares Alcántara'),
(5, 'Girardot'),
(5, 'José Ángel Lamas'),
(5, 'José Félix Ribas'),
(5, 'José Rafael Revenga'),
(5, 'Libertador'),
(5, 'Mario Briceño Iragorry'),
(5, 'Ocumare de la Costa'),
(5, 'San Casimiro'),
(5, 'San Sebastián'),
(5, 'Santiago Mariño'),
(5, 'Santos Michelena'),
(5, 'Sucre'),
(5, 'Tovar'),
(5, 'Urdaneta'),
(5, 'Zamora');

-- Barinas (6)
INSERT INTO municipios (estado_id, nombre) VALUES
(6, 'Alberto Arvelo Torrealba'),
(6, 'Andrés Eloy Blanco'),
(6, 'Antonio José de Sucre'),
(6, 'Arismendi'),
(6, 'Barinas'),
(6, 'Bolívar'),
(6, 'Cruz Paredes'),
(6, 'Ezequiel Zamora'),
(6, 'Obispos'),
(6, 'Pedraza'),
(6, 'Rojas'),
(6, 'Sosa');

-- Bolívar (7)
INSERT INTO municipios (estado_id, nombre) VALUES
(7, 'Caroní'),
(7, 'Cedeño'),
(7, 'El Callao'),
(7, 'Gran Sabana'),
(7, 'Heres'),
(7, 'Piar'),
(7, 'Raúl Leoni'),
(7, 'Roscio'),
(7, 'Sifontes'),
(7, 'Sucre');

-- Carabobo (8)
INSERT INTO municipios (estado_id, nombre) VALUES
(8, 'Bejuma'),
(8, 'Carlos Arvelo'),
(8, 'Diego Ibarra'),
(8, 'Guacara'),
(8, 'Juan José Mora'),
(8, 'Libertador'),
(8, 'Los Guayos'),
(8, 'Miranda'),
(8, 'Montalbán'),
(8, 'Naguanagua'),
(8, 'Puerto Cabello'),
(8, 'San Diego'),
(8, 'San Joaquín'),
(8, 'Valencia');

-- Cojedes (9)
INSERT INTO municipios (estado_id, nombre) VALUES
(9, 'Anzoátegui'),
(9, 'Falcón'),
(9, 'Girardot'),
(9, 'Lima Blanco'),
(9, 'Pao de San Juan Bautista'),
(9, 'Ricaurte'),
(9, 'Rómulo Gallegos'),
(9, 'Tinaco');

-- Delta Amacuro (10)
INSERT INTO municipios (estado_id, nombre) VALUES
(10, 'Antonio Díaz'),
(10, 'Casacoima'),
(10, 'Pedernales'),
(10, 'Tucupita');

-- Falcón (11)
INSERT INTO municipios (estado_id, nombre) VALUES
(11, 'Acosta'),
(11, 'Bolívar'),
(11, 'Buchivacoa'),
(11, 'Cacique Manaure'),
(11, 'Carirubana'),
(11, 'Colina'),
(11, 'Dabajuro'),
(11, 'Democracia'),
(11, 'Falcón'),
(11, 'Federación'),
(11, 'Jacura'),
(11, 'Los Taques'),
(11, 'Mauroa'),
(11, 'Miranda'),
(11, 'Monseñor Iturriza'),
(11, 'Palmasola'),
(11, 'Petit'),
(11, 'Píritu'),
(11, 'San Francisco'),
(11, 'Silva'),
(11, 'Tocópero'),
(11, 'Unión'),
(11, 'Urumaco'),
(11, 'Zamora');

-- Guárico (12)
INSERT INTO municipios (estado_id, nombre) VALUES
(12, 'Camaguán'),
(12, 'Chaguaramas'),
(12, 'El Socorro'),
(12, 'Infante'),
(12, 'Las Mercedes'),
(12, 'Mellado'),
(12, 'Miranda'),
(12, 'Monagas'),
(12, 'Ortiz'),
(12, 'Rendón'),
(12, 'San Gerónimo de Guayabal'),
(12, 'San José de Guaribe'),
(12, 'Santa María de Ipire'),
(12, 'Zaraza');

-- Lara (13)
INSERT INTO municipios (estado_id, nombre) VALUES
(13, 'Andrés Eloy Blanco'),
(13, 'Crespo'),
(13, 'Iribarren'),
(13, 'Jiménez'),
(13, 'Morán'),
(13, 'Palavecino'),
(13, 'Simón Planas'),
(13, 'Torres'),
(13, 'Urdaneta');

-- Mérida (14)
INSERT INTO municipios (estado_id, nombre) VALUES
(14, 'Alberto Adriani'),
(14, 'Andrés Bello'),
(14, 'Antonio Pinto Salinas'),
(14, 'Aricagua'),
(14, 'Arzobispo Chacón'),
(14, 'Campo Elías'),
(14, 'Caracciolo Parra Olmedo'),
(14, 'Cardenal Quintero'),
(14, 'Guaraque'),
(14, 'Julio César Salas'),
(14, 'Justo Briceño'),
(14, 'Libertador'),
(14, 'Miranda'),
(14, 'Obispo Ramos de Lora'),
(14, 'Padre Noguera'),
(14, 'Pueblo Llano'),
(14, 'Rangel'),
(14, 'Rivas Dávila'),
(14, 'Santos Marquina'),
(14, 'Sucre'),
(14, 'Tovar'),
(14, 'Tulio Febres Cordero'),
(14, 'Zea');

-- Miranda (15)
INSERT INTO municipios (estado_id, nombre) VALUES
(15, 'Acevedo'),
(15, 'Andrés Bello'),
(15, 'Baruta'),
(15, 'Brión'),
(15, 'Buroz'),
(15, 'Carrizal'),
(15, 'Chacao'),
(15, 'Cristóbal Rojas'),
(15, 'El Hatillo'),
(15, 'Guaicaipuro'),
(15, 'Independencia'),
(15, 'Lander'),
(15, 'Los Salias'),
(15, 'Páez'),
(15, 'Paz Castillo'),
(15, 'Pedro Gual'),
(15, 'Plaza'),
(15, 'Simón Bolívar'),
(15, 'Sucre'),
(15, 'Urdaneta'),
(15, 'Zamora');

-- Monagas (16)
INSERT INTO municipios (estado_id, nombre) VALUES
(16, 'Acosta'),
(16, 'Aguasay'),
(16, 'Bolívar'),
(16, 'Caripe'),
(16, 'Cedeño'),
(16, 'Ezequiel Zamora'),
(16, 'Libertador'),
(16, 'Maturín'),
(16, 'Piar'),
(16, 'Punceres'),
(16, 'Santa Bárbara'),
(16, 'Sotillo'),
(16, 'Uracoa');

-- Nueva Esparta (17)
INSERT INTO municipios (estado_id, nombre) VALUES
(17, 'Antolín del Campo'),
(17, 'Arismendi'),
(17, 'Díaz'),
(17, 'García'),
(17, 'Gómez'),
(17, 'Maneiro'),
(17, 'Marcano'),
(17, 'Mariño'),
(17, 'Península de Macanao'),
(17, 'Tubores'),
(17, 'Villalba');

-- Portuguesa (18)
INSERT INTO municipios (estado_id, nombre) VALUES
(18, 'Agua Blanca'),
(18, 'Araure'),
(18, 'Esteller'),
(18, 'Guanare'),
(18, 'Guanarito'),
(18, 'Monseñor José Vicente de Unda'),
(18, 'Ospino'),
(18, 'Páez'),
(18, 'Papelón'),
(18, 'San Genaro de Boconoíto'),
(18, 'San Rafael de Onoto'),
(18, 'Santa Rosalía'),
(18, 'Sucre'),
(18, 'Turén');

-- Sucre (19)
INSERT INTO municipios (estado_id, nombre) VALUES
(19, 'Andrés Eloy Blanco'),
(19, 'Andrés Mata'),
(19, 'Arismendi'),
(19, 'Benítez'),
(19, 'Bermúdez'),
(19, 'Bolívar'),
(19, 'Cajigal'),
(19, 'Cruz Salmerón Acosta'),
(19, 'Libertador'),
(19, 'Mariño'),
(19, 'Mejía'),
(19, 'Montes'),
(19, 'Ribero'),
(19, 'Sucre'),
(19, 'Valdez');

-- Táchira (20)
INSERT INTO municipios (estado_id, nombre) VALUES
(20, 'Andrés Bello'),
(20, 'Antonio Rómulo Costa'),
(20, 'Ayacucho'),
(20, 'Bolívar'),
(20, 'Cárdenas'),
(20, 'Córdoba'),
(20, 'Fernández Feo'),
(20, 'Francisco de Miranda'),
(20, 'García de Hevia'),
(20, 'Guásimos'),
(20, 'Independencia'),
(20, 'Jauregui'),
(20, 'José María Vargas'),
(20, 'Junín'),
(20, 'Libertad'),
(20, 'Libertador'),
(20, 'Lobatera'),
(20, 'Michelena'),
(20, 'Panamericano'),
(20, 'Pedro María Ureña'),
(20, 'Rafael Urdaneta'),
(20, 'Samaniego'),
(20, 'San Cristóbal'),
(20, 'San Judas Tadeo'),
(20, 'Seboruco'),
(20, 'Simón Rodríguez'),
(20, 'Sucre'),
(20, 'Torbes'),
(20, 'Uribante');

-- Trujillo (21)
INSERT INTO municipios (estado_id, nombre) VALUES
(21, 'Andrés Bello'),
(21, 'Boconó'),
(21, 'Bolívar'),
(21, 'Candelaria'),
(21, 'Carache'),
(21, 'Escuque'),
(21, 'José Felipe Márquez Cañizales'),
(21, 'Juan Vicente Campo Elías'),
(21, 'La Ceiba'),
(21, 'Miranda'),
(21, 'Monte Carmelo'),
(21, 'Motatán'),
(21, 'Pampán'),
(21, 'Pampanito'),
(21, 'Rafael Rangel'),
(21, 'San Rafael de Carvajal'),
(21, 'Sucre'),
(21, 'Trujillo'),
(21, 'Urdaneta'),
(21, 'Valera');

-- La Guaira (22)
INSERT INTO municipios (estado_id, nombre) VALUES
(22, 'Carayaca'),
(22, 'Carlos Soublette'),
(22, 'Caruao'),
(22, 'Catia La Mar'),
(22, 'El Junko'),
(22, 'La Guaira'),
(22, 'Macuto'),
(22, 'Maiquetía'),
(22, 'Naiguatá'),
(22, 'Urimare');

-- Yaracuy (23)
INSERT INTO municipios (estado_id, nombre) VALUES
(23, 'Arístides Bastidas'),
(23, 'Bolívar'),
(23, 'Bruzual'),
(23, 'Cocorote'),
(23, 'Independencia'),
(23, 'José Antonio Páez'),
(23, 'La Trinidad'),
(23, 'Manuel Monge'),
(23, 'Nirgua'),
(23, 'Peña'),
(23, 'San Felipe'),
(23, 'Sucre'),
(23, 'Urachiche'),
(23, 'Veroes');

-- Zulia (24)
INSERT INTO municipios (estado_id, nombre) VALUES
(24, 'Almirante Padilla'),
(24, 'Baralt'),
(24, 'Cabimas'),
(24, 'Catatumbo'),
(24, 'Colón'),
(24, 'Francisco Javier Pulgar'),
(24, 'Jesús Enrique Lossada'),
(24, 'Jesús María Semprún'),
(24, 'La Cañada de Urdaneta'),
(24, 'Lagunillas'),
(24, 'Machiques de Perijá'),
(24, 'Mara'),
(24, 'Maracaibo'),
(24, 'Miranda'),
(24, 'Páez'),
(24, 'Rosario de Perijá'),
(24, 'San Francisco'),
(24, 'Santa Rita'),
(24, 'Simón Bolívar'),
(24, 'Sucre'),
(24, 'Valmore Rodríguez');

-- ===== PARROQUIAS (principales) =====
-- Los IDs de municipio corresponden al orden de los INSERTs de arriba.
-- Agregar más según sea necesario.

-- Distrito Capital - Libertador (1)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(1, 'Altagracia'),
(1, 'Antímano'),
(1, 'Candelaria'),
(1, 'Caricuao'),
(1, 'Catedral'),
(1, 'Coche'),
(1, 'El Junquito'),
(1, 'El Paraíso'),
(1, 'El Recreo'),
(1, 'El Valle'),
(1, 'La Pastora'),
(1, 'La Vega'),
(1, 'Macarao'),
(1, 'San Agustín'),
(1, 'San Bernardino'),
(1, 'San José'),
(1, 'San Juan'),
(1, 'San Pedro'),
(1, 'Santa Rosalía'),
(1, 'Santa Teresa'),
(1, 'Sucre (Catia)'),
(1, '23 de Enero');

-- Amazonas - Atures (2)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(2, 'Puerto Ayacucho'),
(2, 'Guayabal'),
(2, 'San Fernando de Atabapo');

-- Anzoátegui - Simón Bolívar (27)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(27, 'Barcelona'),
(27, 'El Carmen'),
(27, 'San Cristóbal'),
(27, 'Bergantín'),
(27, 'Caigua'),
(27, 'Naricual'),
(27, 'Pozuelos');

-- Apure - San Fernando (36)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(36, 'San Fernando'),
(36, 'El Recreo'),
(36, 'Peñalver'),
(36, 'San Rafael de Atamaica');

-- Aragua - Girardot (40)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(40, 'Choroní'),
(40, 'Chuao'),
(40, 'Las Delicias'),
(40, 'Madre María de San José'),
(40, 'Pedro José Ovalles'),
(40, 'San José de Maracay'),
(40, 'Santa Rosa'),
(40, 'Joaquín Crespo'),
(40, 'Los Tacariguas'),
(40, 'Andrés Eloy Blanco');

-- Barinas - Barinas (59)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(59, 'Barinas'),
(59, 'Alto Barinas'),
(59, 'Corazón de Jesús'),
(59, 'Dominga Ortiz de Páez'),
(59, 'El Carmen'),
(59, 'Juan Rodríguez Suárez'),
(59, 'Mérida'),
(59, 'Rivas Berti'),
(59, 'San Silvestre'),
(59, 'Santa Inés'),
(59, 'Santa Lucía'),
(59, 'Torunos');

-- Bolívar - Heres (71)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(71, 'Agua Salada'),
(71, 'Catedral'),
(71, 'José Antonio Páez'),
(71, 'La Sabanita'),
(71, 'Marhuanta'),
(71, 'Vista Hermosa'),
(71, 'Orinoco'),
(71, 'Zea'),
(71, 'Dalla Costa'),
(71, 'Cachamay'),
(71, 'Unare'),
(71, 'Simón Bolívar');

-- Carabobo - Valencia (90)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(90, 'Candelaria'),
(90, 'El Socorro'),
(90, 'Miguel Peña'),
(90, 'Negro Primero'),
(90, 'Rafael Urdaneta'),
(90, 'San Blas'),
(90, 'San José'),
(90, 'Santa Rosa');

-- Cojedes - Falcón (92)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(92, 'San Carlos de Cojedes');

-- Delta Amacuro - Tucupita (102)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(102, 'Tucupita'),
(102, 'San José'),
(102, 'Leonardo Ruíz Pineda'),
(102, 'Monseñor Argimiro García'),
(102, 'Virgen del Valle');

-- Falcón - Miranda (116)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(116, 'Coro'),
(116, 'Guzmán Guillermo'),
(116, 'San Antonio'),
(116, 'San Gabriel'),
(116, 'Santa Ana');

-- Lara - Iribarren (143)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(143, 'Aguedo Felipe Alvarado'),
(143, 'Ana Soto'),
(143, 'Barquisimeto'),
(143, 'Buena Vista'),
(143, 'Catedral'),
(143, 'Concepción'),
(143, 'El Cují'),
(143, 'El Rocío'),
(143, 'Juárez'),
(143, 'La Concordia'),
(143, 'La Pastora'),
(143, 'San Juan'),
(143, 'Santa Rosa'),
(143, 'Tamaca'),
(143, 'Unión');

-- Mérida - Libertador (161)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(161, 'Antonio Spinetti Dini'),
(161, 'Arias'),
(161, 'Caracciolo Parra Olmedo'),
(161, 'Domingo Peña'),
(161, 'El Llano'),
(161, 'Gonzalo Picón'),
(161, 'Jacinto Plaza'),
(161, 'Juan Rodríguez Suárez'),
(161, 'Lasso de la Vega'),
(161, 'Mariano Picón Salas'),
(161, 'Milla'),
(161, 'Mons. Chacón'),
(161, 'Osuna Rodríguez'),
(161, 'Sagrario'),
(161, 'San Bartolomé');

-- Miranda - Baruta (175)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(175, 'Baruta'),
(175, 'El Cafetal'),
(175, 'Las Minas'),
(175, 'Nuestra Señora del Rosario');

-- Miranda - Chacao (179)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(179, 'Chacao');

-- Miranda - El Hatillo (181)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(181, 'El Hatillo');

-- Miranda - Guaicaipuro (182)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(182, 'Los Teques'),
(182, 'Cecilio Acosta'),
(182, 'Paracotos'),
(182, 'San Pedro'),
(182, 'Tácata'),
(182, 'El Jarillo'),
(182, 'Altagracia de la Montaña');

-- Miranda - Sucre (191)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(191, 'Leoncio Martínez'),
(191, 'Petare'),
(191, 'Caucagüita'),
(191, 'La Dolorita'),
(191, 'Filas de Mariche');

-- Monagas - Maturín (201)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(201, 'Alto de los Godos'),
(201, 'Boquerón'),
(201, 'Las Cocuizas'),
(201, 'La Cruz'),
(201, 'San Simón'),
(201, 'El Corozo'),
(201, 'El Furrial'),
(201, 'Jusepín'),
(201, 'La Pica'),
(201, 'Santa Bárbara');

-- Nueva Esparta - Arismendi (208)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(208, 'La Asunción'),
(208, 'El Espinal'),
(208, 'Los Martínez'),
(208, 'Tacarigua');

-- Portuguesa - Guanare (221)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(221, 'Guanare'),
(221, 'Córdoba'),
(221, 'San José de la Montaña'),
(221, 'San Juan de Guanaguanare'),
(221, 'Virgen de Coromoto');

-- Sucre - Sucre (245)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(245, 'Cumaná'),
(245, 'Altagracia'),
(245, 'Ayacucho'),
(245, 'Gran Mariscal'),
(245, 'Raúl Leoni'),
(245, 'San Juan'),
(245, 'San Luis'),
(245, 'Santa Inés'),
(245, 'Valentín Valiente');

-- Táchira - San Cristóbal (269)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(269, 'La Concordia'),
(269, 'San Cristóbal'),
(269, 'San Juan Bautista'),
(269, 'Pedro María Morantes'),
(269, 'San Sebastián'),
(269, 'Francisco Romero Lobo'),
(269, 'La Ermita'),
(269, 'La Florida'),
(269, 'La Piscina'),
(269, 'La Vigía'),
(269, 'La Grita'),
(269, 'Táriba'),
(269, 'Palmira'),
(269, 'Santa Ana'),
(269, 'Colón'),
(269, 'San José de Bolívar'),
(269, 'San Pedro de Río'),
(269, 'Río Chiquito'),
(269, 'Boca de Monte');

-- Trujillo - Trujillo (293)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(293, 'Trujillo'),
(293, 'Cristóbal Mendoza'),
(293, 'Chiquinquirá'),
(293, 'La Paz'),
(293, 'Monseñor Carrillo'),
(293, 'Andrés Linares');

-- La Guaira - La Guaira (301)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(301, 'La Guaira'),
(301, 'Carlos Soublette'),
(301, 'Carayaca'),
(301, 'Caruao'),
(301, 'Catia La Mar'),
(301, 'El Junko'),
(301, 'Macuto'),
(301, 'Maiquetía'),
(301, 'Naiguatá'),
(301, 'Urimare'),
(301, 'Raúl Leoni');

-- Yaracuy - San Felipe (316)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(316, 'San Felipe'),
(316, 'Albarico'),
(316, 'San Javier'),
(316, 'El Guayabo'),
(316, 'Farriar'),
(316, 'Tablón'),
(316, 'Marín'),
(316, 'Cocorotico'),
(316, 'Independencia'),
(316, 'Urachiche'),
(316, 'Yaritagua');

-- Zulia - Maracaibo (332)
INSERT INTO parroquias (municipio_id, nombre) VALUES
(332, 'Antonio Borjas Romero'),
(332, 'Bolívar'),
(332, 'Cacique Mara'),
(332, 'Carracciolo Parra Pérez'),
(332, 'Cecilio Acosta'),
(332, 'Cristo de Aranza'),
(332, 'Coquivacoa'),
(332, 'Chiquinquirá'),
(332, 'Francisco Eugenio Bustamante'),
(332, 'Idelfonso Vásquez'),
(332, 'Juana de Ávila'),
(332, 'Luis Hurtado Higuera'),
(332, 'Manuel Dagnino'),
(332, 'Olegario Villalobos'),
(332, 'Raúl Leoni'),
(332, 'Santa Lucía'),
(332, 'San Isidro'),
(332, 'Venancio Pulgar');

-- ===== REFUGIOS =====

CREATE TABLE refugios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    municipio_id INT NOT NULL,
    parroquia_id INT NULL,
    direccion TEXT NOT NULL,
    direccion_hash CHAR(40) UNIQUE NOT NULL,
    foto_url VARCHAR(500),
    telefono VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (parroquia_id) REFERENCES parroquias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventario_refugios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    refugio_id INT NOT NULL,
    item VARCHAR(200) NOT NULL,
    tipo ENUM('falta', 'sobra') NOT NULL,
    cantidad VARCHAR(100) NOT NULL DEFAULT '',
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (refugio_id) REFERENCES refugios(id) ON DELETE CASCADE,
    KEY idx_inventario_refugio (refugio_id, activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== REPORTES / AUDITORÍA COMUNITARIA =====

CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    centro_id INT NULL,
    refugio_id INT NULL,
    nombre_anonimo VARCHAR(100) NOT NULL DEFAULT 'Anónimo',
    tipo_reporte ENUM('valida', 'alerta', 'denuncia', 'comentario') NOT NULL DEFAULT 'comentario',
    mensaje TEXT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (centro_id) REFERENCES centros(id) ON DELETE CASCADE,
    FOREIGN KEY (refugio_id) REFERENCES refugios(id) ON DELETE CASCADE,
    KEY idx_reportes_centro (centro_id, activo),
    KEY idx_reportes_refugio (refugio_id, activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reportes_denuncias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporte_id INT NOT NULL,
    ip_hash CHAR(40) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporte_id) REFERENCES reportes(id) ON DELETE CASCADE,
    UNIQUE KEY uk_denuncia (reporte_id, ip_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== SUGERENCIAS =====

CREATE TABLE sugerencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL DEFAULT 'Anónimo',
    email VARCHAR(200),
    mensaje TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
