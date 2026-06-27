CREATE TABLE damnificados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    cedula VARCHAR(8) NOT NULL,
    edad INT NOT NULL,
    telefono VARCHAR(20) DEFAULT '',
    ubicacion VARCHAR(300) DEFAULT '',
    foto_url VARCHAR(500),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
