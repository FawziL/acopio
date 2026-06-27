<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refugios - Apoya Venezuela</title>
    <?php require_once __DIR__ . '/partials/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <?php $activeNav = 'refugios'; ?>
    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

    <main class="av-main">
        <div class="container py-4">

        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <h1 class="h3">Refugios Registrados</h1>
                <p class="text-muted">Albergues disponibles para personas damnificadas.</p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="/registrar" class="btn btn-av-blue">
                    <i class="bi bi-plus-circle"></i> Registrar
                </a>
                <a href="/centros-acopio" class="btn btn-av-outline-blue mt-1 mt-md-0">
                    <i class="bi bi-box-seam"></i> Centros de Acopio
                </a>
            </div>
        </div>

        <form method="GET" class="row g-2 mb-4">
            <div class="col-12 col-md-4">
                <select name="estado" id="filtro-estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, nombre FROM estados ORDER BY nombre");
                    $estadoActual = $_GET['estado'] ?? '';
                    while ($row = $stmt->fetch()):
                        $selected = ($estadoActual == $row['id']) ? 'selected' : '';
                    ?>
                        <option value="<?= $row['id'] ?>" <?= $selected ?>><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select name="municipio" id="filtro-municipio" class="form-select">
                    <option value="">Todos los municipios</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <button type="submit" class="btn btn-av-outline-blue w-100">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>
        </form>

        <?php
        $estado    = $_GET['estado'] ?? '';
        $municipio = $_GET['municipio'] ?? '';
        $pagina    = max(1, (int)($_GET['pagina'] ?? 1));
        $limite    = 20;
        $offset    = ($pagina - 1) * $limite;

        $where  = [];
        $params = [];

        if ($estado !== '') {
            $where[] = 'r.estado_id = :estado';
            $params[':estado'] = (int)$estado;
        }
        if ($municipio !== '') {
            $where[] = 'r.municipio_id = :municipio';
            $params[':municipio'] = (int)$municipio;
        }

        $sqlWhere = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM refugios r $sqlWhere");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();
        $totalPaginas = (int)ceil($total / $limite);

        $stmt = $pdo->prepare("
            SELECT r.id, r.direccion, r.foto_url, r.telefono, r.created_at,
                   e.nombre AS estado,
                   m.nombre AS municipio
            FROM refugios r
            JOIN estados e ON e.id = r.estado_id
            JOIN municipios m ON m.id = r.municipio_id
            $sqlWhere
            ORDER BY r.created_at DESC
            LIMIT $limite OFFSET $offset
        ");
        $stmt->execute($params);
        $refugios = $stmt->fetchAll();
        ?>

        <p class="text-muted mb-3">
            <?= $total ?> refugio<?= $total !== 1 ? 's' : '' ?> encontrado<?= $total !== 1 ? 's' : '' ?>
            <?php if ($estado || $municipio): ?>
                <a href="/refugios" class="btn btn-sm btn-outline-secondary ms-2">Limpiar filtros</a>
            <?php endif; ?>
        </p>

        <?php if (empty($refugios)): ?>
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <p class="mt-3 text-muted">No hay refugios registrados con esos filtros.</p>
                <a href="/refugios" class="btn btn-av-outline-blue">Limpiar filtros</a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($refugios as $refugio):
                    $stmtInv = $pdo->prepare("
                        SELECT tipo, item FROM inventario_refugios
                        WHERE refugio_id = :id AND activo = 1
                        ORDER BY tipo, item
                        LIMIT 10
                    ");
                    $stmtInv->execute([':id' => $refugio['id']]);
                    $items = $stmtInv->fetchAll();
                    $falta = array_filter($items, fn($i) => $i['tipo'] === 'falta');
                    $sobra = array_filter($items, fn($i) => $i['tipo'] === 'sobra');
                ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="/refugio/<?= $refugio['id'] ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-danger">
                                <?php if ($refugio['foto_url']): ?>
                                    <img src="<?= htmlspecialchars($refugio['foto_url']) ?>"
                                         class="card-img-top" alt="Foto del refugio"
                                         style="height: 180px; object-fit: cover;"
                                         onerror="this.style.display='none'">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title text-danger"><?= htmlspecialchars($refugio['direccion'] ? mb_substr($refugio['direccion'], 0, 60) : 'Refugio #' . $refugio['id']) ?></h5>
                                    <p class="card-text small text-muted mb-1">
                                        <i class="bi bi-geo-alt"></i>
                                        <?= htmlspecialchars($refugio['estado']) ?> &middot; <?= htmlspecialchars($refugio['municipio']) ?>
                                    </p>
                                    <?php if ($refugio['telefono']): ?>
                                        <p class="card-text small text-muted mb-1">
                                            <i class="bi bi-telephone"></i> <?= htmlspecialchars($refugio['telefono']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="card-text small mb-1">
                                        <?php if (count($falta) > 0): ?>
                                            <span class="text-danger fw-semibold">❌ Falta:</span>
                                            <?php foreach (array_slice($falta, 0, 3) as $item): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger me-1"><?= htmlspecialchars($item['item']) ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($falta) > 3): ?>
                                                <span class="text-muted small">+<?= count($falta) - 3 ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (count($sobra) > 0): ?>
                                            <br>
                                            <span class="text-success fw-semibold">✅ Sobra:</span>
                                            <?php foreach (array_slice($sobra, 0, 3) as $item): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success me-1"><?= htmlspecialchars($item['item']) ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($sobra) > 3): ?>
                                                <span class="text-muted small">+<?= count($sobra) - 3 ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </p>
                                    <p class="card-text small text-muted mb-0">
                                        <i class="bi bi-clock"></i> <?= htmlspecialchars($refugio['created_at']) ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPaginas > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++):
                            $active = ($i === $pagina) ? 'active' : '';
                            $query = $_GET;
                            $query['pagina'] = $i;
                            $url = '?' . http_build_query($query);
                        ?>
                            <li class="page-item <?= $active ?>">
                                <a class="page-link" href="<?= $url ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php endif; ?>
        </div>
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
