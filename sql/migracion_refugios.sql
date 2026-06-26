-- Migración: Agregar tablas de refugios + modificar reportes
-- Es seguro ejecutarlo múltiples veces

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

CREATE TABLE IF NOT EXISTS sugerencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL DEFAULT 'Anónimo',
    email VARCHAR(200),
    mensaje TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modificar reportes: centro_id nullable
ALTER TABLE reportes MODIFY COLUMN centro_id INT NULL;

-- Agregar refugio_id solo si no existe
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reportes' AND COLUMN_NAME = 'refugio_id');
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE reportes ADD COLUMN refugio_id INT NULL AFTER centro_id',
    'SELECT ''refugio_id ya existe''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Agregar índice si no existe
SET @idx_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reportes' AND INDEX_NAME = 'idx_reportes_refugio');
SET @sql2 = IF(@idx_exists = 0,
    'ALTER TABLE reportes ADD KEY idx_reportes_refugio (refugio_id, activo)',
    'SELECT ''índice ya existe''');
PREPARE stmt FROM @sql2;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Agregar FK si no existe
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reportes'
    AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME LIKE '%refugio%');
SET @sql3 = IF(@fk_exists = 0,
    'ALTER TABLE reportes ADD CONSTRAINT fk_reportes_refugio FOREIGN KEY (refugio_id) REFERENCES refugios(id) ON DELETE CASCADE',
    'SELECT ''FK ya existe''');
PREPARE stmt FROM @sql3;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
