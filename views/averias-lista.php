<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Averías Reportadas - Apoya Venezuela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-house-heart-fill"></i> Apoya Venezuela
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/centros-acopio"><i class="bi bi-box-seam"></i> Centros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/refugios"><i class="bi bi-house-heart"></i> Refugios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/averias/lista"><i class="bi bi-exclamation-triangle"></i> Averías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/portales"><i class="bi bi-globe2"></i> Portales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/voluntarios/lista"><i class="bi bi-people"></i> Voluntarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
            <div>
                <a href="/" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            <a href="/averias" class="btn btn-danger">
                <i class="bi bi-plus-circle"></i> Reportar avería
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <h2 class="h4 mb-1">Averías reportadas</h2>
                <p class="text-muted mb-0">Inmuebles afectados reportados por la comunidad.</p>
            </div>
        </div>

        <!-- Filtros -->
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
                <button type="submit" class="btn btn-outline-danger w-100">
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
            $where[] = 'a.estado_id = :estado';
            $params[':estado'] = (int)$estado;
        }
        if ($municipio !== '') {
            $where[] = 'a.municipio_id = :municipio';
            $params[':municipio'] = (int)$municipio;
        }

        $sqlWhere = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM averias a $sqlWhere");
        $stmtCount->execute($params);
        $total = (int)$stmtCount->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT a.id, a.nombre, a.referencia, a.foto_url, a.contacto, a.estado, a.created_at,
                   e.nombre AS estado_nombre, m.nombre AS municipio, p.nombre AS parroquia
            FROM averias a
            JOIN estados e ON e.id = a.estado_id
            JOIN municipios m ON m.id = a.municipio_id
            LEFT JOIN parroquias p ON p.id = a.parroquia_id
            $sqlWhere
            ORDER BY a.created_at DESC
            LIMIT $limite OFFSET $offset
        ");
        $stmt->execute($params);
        $averias = $stmt->fetchAll();
        ?>

        <p class="text-muted small mb-3">
            <i class="bi bi-file-text"></i> Mostrando <?= count($averias) ?> de <?= $total ?> avería<?= $total !== 1 ? 's' : '' ?>
        </p>

        <?php if (count($averias) === 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle display-1 text-muted"></i>
                <p class="mt-3 text-muted">No hay averías reportadas<?= $estado || $municipio ? ' con los filtros seleccionados' : ' aún' ?>.</p>
                <a href="/averias" class="btn btn-danger">
                    <i class="bi bi-plus-circle"></i> Reportar avería
                </a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($averias as $a): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="/averia/<?= $a['id'] ?>" class="text-decoration-none">
                            <div class="card shadow-sm h-100 border-danger">
                                <?php if ($a['foto_url']): ?>
                                    <img src="<?= htmlspecialchars($a['foto_url']) ?>" class="card-img-top"
                                         alt="Foto" style="height:180px;object-fit:cover;"
                                         onerror="this.style.display='none'">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title text-danger"><?= htmlspecialchars($a['nombre']) ?></h5>
                                    <p class="card-text small text-muted mb-1">
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($a['estado_nombre']) ?> &middot; <?= htmlspecialchars($a['municipio']) ?>
                                    </p>
                                    <p class="card-text small mb-1">
                                        <?php
                                        $estadosBadge = [
                                            'reportado'  => 'bg-secondary text-white',
                                            'verificado' => 'bg-success text-white',
                                            'en_proceso' => 'bg-warning text-dark',
                                            'resuelto'   => 'bg-info text-dark',
                                        ];
                                        $badgeClass = $estadosBadge[$a['estado']] ?? 'bg-secondary text-white';
                                        $estadoLabel = match ($a['estado']) {
                                            'reportado'  => 'Reportado',
                                            'verificado' => 'Verificado',
                                            'en_proceso' => 'En proceso',
                                            'resuelto'   => 'Resuelto',
                                            default      => $a['estado'],
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $estadoLabel ?></span>
                                    </p>
                                    <p class="card-text small text-muted mb-0">
                                        <i class="bi bi-clock"></i> <?= htmlspecialchars($a['created_at']) ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($total > $limite):
                $totalPaginas = (int)ceil($total / $limite);
            ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++):
                            $active = $i === $pagina ? 'active' : '';
                            $paramsUrl = [];
                            if ($estado) $paramsUrl['estado'] = $estado;
                            if ($municipio) $paramsUrl['municipio'] = $municipio;
                            $paramsUrl['pagina'] = $i;
                            $url = '/averias/lista?' . http_build_query($paramsUrl);
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

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Apoya Venezuela &mdash; Centros de Acopio y Refugios
            &middot; <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var estadoSelect = document.getElementById('filtro-estado');
        var municipioSelect = document.getElementById('filtro-municipio');

        if (estadoSelect && municipioSelect) {
            estadoSelect.addEventListener('change', function () {
                var estadoId = this.value;
                municipioSelect.innerHTML = '<option value="">Cargando...</option>';

                if (!estadoId) {
                    municipioSelect.innerHTML = '<option value="">Todos los municipios</option>';
                    return;
                }

                fetch('/api/municipios.php?estado_id=' + estadoId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        municipioSelect.innerHTML = '<option value="">Todos los municipios</option>';
                        if (data.data) {
                            data.data.forEach(function (m) {
                                var opt = document.createElement('option');
                                opt.value = m.id;
                                opt.textContent = m.nombre;
                                municipioSelect.appendChild(opt);
                            });
                        }
                    })
                    .catch(function () {
                        municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            });
        }

        <?php if ($estado): ?>
        if (estadoSelect) {
            var event = new Event('change');
            estadoSelect.dispatchEvent(event);
        }
        <?php endif; ?>

        <?php if ($municipio): ?>
        setTimeout(function () {
            if (municipioSelect) {
                for (var i = 0; i < municipioSelect.options.length; i++) {
                    if (municipioSelect.options[i].value === '<?= $municipio ?>') {
                        municipioSelect.value = '<?= $municipio ?>';
                        break;
                    }
                }
            }
        }, 300);
        <?php endif; ?>
    });
    </script>
</body>
</html>
