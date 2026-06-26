<?php
require_once __DIR__ . '/config/database.php';

echo "=== Migración ===\n\n";

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS refugios (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ refugios\n";

    $pdo->exec("CREATE TABLE IF NOT EXISTS inventario_refugios (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ inventario_refugios\n";

    $stmt = $pdo->query("SHOW COLUMNS FROM reportes LIKE 'centro_id'");
    $col = $stmt->fetch();
    if ($col && $col['Null'] === 'NO') {
        $pdo->exec("ALTER TABLE reportes MODIFY COLUMN centro_id INT NULL");
        echo "✅ reportes.centro_id NULLABLE\n";
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM reportes LIKE 'refugio_id'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE reportes ADD COLUMN refugio_id INT NULL AFTER centro_id");
        $pdo->exec("ALTER TABLE reportes ADD FOREIGN KEY (refugio_id) REFERENCES refugios(id) ON DELETE CASCADE");
        $pdo->exec("ALTER TABLE reportes ADD KEY idx_reportes_refugio (refugio_id, activo)");
        echo "✅ reportes.refugio_id agregado\n";
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS sugerencias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL DEFAULT 'Anónimo',
        email VARCHAR(200),
        mensaje TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ sugerencias\n";

    echo "\n=== Migración completada ===\n";
} catch (PDOException $e) {
    echo "❌ " . $e->getMessage() . "\n";
    exit(1);
}
