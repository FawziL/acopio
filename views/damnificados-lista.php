<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damnificados Registrados - Apoya Venezuela</title>
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
                        <a class="nav-link" href="/averias/lista"><i class="bi bi-exclamation-triangle"></i> Averías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/damnificados/lista"><i class="bi bi-people"></i> Damnificados</a>
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
            <a href="/" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="/damnificados" class="btn btn-danger">
                <i class="bi bi-plus-circle"></i> Registrar damnificado
            </a>
        </div>

        <h2 class="h4 mb-1">Damnificados registrados</h2>
        <p class="text-muted mb-4">Personas afectadas registradas para coordinar asistencia.</p>

        <?php
        $pagina = max(1, (int)($_GET['pagina'] ?? 1));
        $limite = 20;
        $offset = ($pagina - 1) * $limite;

        $stmtCount = $pdo->query("SELECT COUNT(*) FROM damnificados");
        $total = (int)$stmtCount->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT id, nombre, cedula, edad, telefono, ubicacion, foto_url, created_at
            FROM damnificados
            ORDER BY created_at DESC
            LIMIT $limite OFFSET $offset
        ");
        $stmt->execute();
        $damnificados = $stmt->fetchAll();
        ?>

        <p class="text-muted small mb-3">
            <i class="bi bi-file-text"></i> Mostrando <?= count($damnificados) ?> de <?= $total ?> damnificado<?= $total !== 1 ? 's' : '' ?>
        </p>

        <?php if (count($damnificados) === 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <p class="mt-3 text-muted">No hay damnificados registrados aún.</p>
                <a href="/damnificados" class="btn btn-danger">
                    <i class="bi bi-plus-circle"></i> Registrar damnificado
                </a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($damnificados as $d):
                    $iniciales = '';
                    if ($d['nombre']) {
                        $partes = explode(' ', trim($d['nombre']));
                        $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1));
                        if (count($partes) > 1) {
                            $iniciales .= mb_strtoupper(mb_substr(end($partes), 0, 1));
                        }
                    }
                ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card shadow-sm h-100 border-danger">
                            <?php if ($d['foto_url']): ?>
                                <img src="<?= htmlspecialchars($d['foto_url']) ?>" class="card-img-top"
                                     alt="Foto" style="height:160px;object-fit:cover;"
                                     onerror="this.style.display='none'">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10"
                                     style="height:160px;">
                                    <span class="display-4 fw-bold text-danger"><?= htmlspecialchars($iniciales) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title text-danger mb-1"><?= htmlspecialchars($d['nombre']) ?></h6>
                                <p class="card-text small text-muted mb-1">
                                    <i class="bi bi-credit-card"></i> V-<?= htmlspecialchars($d['cedula']) ?> &middot; <?= (int)$d['edad'] ?> años
                                </p>
                                <?php if ($d['telefono']): ?>
                                    <p class="card-text small text-muted mb-1">
                                        <i class="bi bi-telephone"></i> <?= htmlspecialchars($d['telefono']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($d['ubicacion']): ?>
                                    <p class="card-text small text-muted mb-0">
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($d['ubicacion']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
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
                        ?>
                            <li class="page-item <?= $active ?>">
                                <a class="page-link" href="/damnificados/lista?pagina=<?= $i ?>"><?= $i ?></a>
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
            &middot; <a href="/damnificados/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Damnificados</a>
            <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
