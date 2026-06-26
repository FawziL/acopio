-- Migración: Agregar tablas de refugios + modificar reportes
-- Ejecutar después del schema.sql original

CREATE TABLE IF NOT EXISTS refugios (
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

CREATE TABLE IF NOT EXISTS inventario_refugios (
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

-- Modificar reportes: hacer centro_id nullable y agregar refugio_id
ALTER TABLE reportes
    MODIFY COLUMN centro_id INT NULL,
    ADD COLUMN refugio_id INT NULL AFTER centro_id,
    ADD FOREIGN KEY (refugio_id) REFERENCES refugios(id) ON DELETE CASCADE,
    ADD KEY idx_reportes_refugio (refugio_id, activo);
