CREATE TABLE averias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    estado_id INT NOT NULL,
    municipio_id INT NOT NULL,
    parroquia_id INT NULL,
    referencia TEXT NOT NULL,
    contacto VARCHAR(100) DEFAULT '',
    foto_url VARCHAR(500),
    estado ENUM('reportado','verificado','en_proceso','resuelto') NOT NULL DEFAULT 'reportado',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (parroquia_id) REFERENCES parroquias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE reportes
ADD COLUMN averia_id INT NULL AFTER refugio_id,
ADD FOREIGN KEY (averia_id) REFERENCES averias(id) ON DELETE CASCADE,
ADD KEY idx_reportes_averia (averia_id, activo);
