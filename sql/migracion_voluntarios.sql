CREATE TABLE voluntarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    zona VARCHAR(300) DEFAULT '',
    tiene_transporte TINYINT(1) DEFAULT 0,
    necesita_transporte TINYINT(1) DEFAULT 0,
    tipo_apoyo TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
