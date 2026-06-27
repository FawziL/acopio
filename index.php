<?php require_once __DIR__ . '/config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apoya Venezuela - Centros de Acopio y Refugios</title>
    <?php require_once __DIR__ . '/views/partials/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <?php $activeNav = ''; ?>
    <?php require_once __DIR__ . '/views/partials/navbar.php'; ?>

    <main class="av-main">
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
                        <a class="nav-link" href="/damnificados/lista"><i class="bi bi-people"></i> Damnificados</a>
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

            <section class="av-section">
                <h2 class="h3 text-center mb-4">Portales</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-box-seam display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Centros de Acopio</h3>
                                <p class="text-muted">
                                    Puntos de recolección de donaciones. Consulta qué falta y qué sobra en cada centro.
                                </p>
                                <a href="/centros-acopio" class="btn btn-danger">
                                    <i class="bi bi-box-seam"></i> Ver centros
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-house-heart-fill display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Refugios</h3>
                                <p class="text-muted">
                                    Albergues y refugios disponibles para personas damnificadas.
                                </p>
                                <a href="/refugios" class="btn btn-danger">
                                    <i class="bi bi-box-seam"></i> Ver refugios
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Averías</h3>
                                <p class="text-muted">
                                    Reporta inmuebles afectados para coordinar evaluaciones.
                                </p>
                                <a href="/averias/lista" class="btn btn-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Ver averías
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-people display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Damnificados</h3>
                                <p class="text-muted">
                                    Registro de personas afectadas para coordinar asistencia.
                                </p>
                                <a href="/damnificados/lista" class="btn btn-danger">
                                    <i class="bi bi-people"></i> Ver damnificados
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-people-fill display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Voluntarios</h3>
                                <p class="text-muted">
                                    Ofrece tu tiempo y habilidades como voluntario.
                                </p>
                                <a href="/voluntarios/lista" class="btn btn-danger">
                                    <i class="bi bi-people-fill"></i> Ser voluntario
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-av-blue">
                            <div class="card-body text-center py-4 d-flex flex-column">
                                <i class="bi bi-info-circle-fill display-3 text-av-blue"></i>
                                <h5 class="card-title mt-3">Información Oficial</h5>
                                <p class="card-text small text-muted">
                                    Comunicados oficiales y Protección Civil.
                                </p>
                                <a href="#" class="btn btn-av-outline-blue mt-auto">
                                    <i class="bi bi-box-arrow-up-right"></i> Ingresar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="av-section mt-5">
                <h2 class="h3 text-center mb-4">Catálogo</h2>
                <div class="av-tabs">
                    <button class="av-tab active" data-tab="centros">
                        <i class="bi bi-box-seam"></i> Centros de Acopio
                    </button>
                    <button class="av-tab" data-tab="refugios">
                        <i class="bi bi-house-heart"></i> Refugios
                    </button>
                    <button class="av-tab" data-tab="averias">
                        <i class="bi bi-exclamation-triangle"></i> Averías
                    </button>
                    <button class="av-tab" data-tab="damnificados">
                        <i class="bi bi-people"></i> Damnificados
                    </button>
                </div>

                <div class="av-tab-panel active" id="panel-centros">
                    <?php
                    $stmt = $pdo->query("
                        SELECT c.id, c.direccion, c.foto_url, c.telefono,
                               e.nombre AS estado,
                               m.nombre AS municipio
                        FROM centros c
                        JOIN estados e ON e.id = c.estado_id
                        JOIN municipios m ON m.id = c.municipio_id
                        ORDER BY c.created_at DESC
                        LIMIT 6
                    ");
                    $centros = $stmt->fetchAll();
                    ?>
                    <?php if (empty($centros)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">No hay centros registrados aún.</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($centros as $centro):
                                $stmtInv = $pdo->prepare("
                                    SELECT tipo, item FROM inventario
                                    WHERE centro_id = :id AND activo = 1
                                    ORDER BY tipo, item
                                    LIMIT 10
                                ");
                                $stmtInv->execute([':id' => $centro['id']]);
                                $items = $stmtInv->fetchAll();
                                $falta = array_filter($items, fn($i) => $i['tipo'] === 'falta');
                                $sobra = array_filter($items, fn($i) => $i['tipo'] === 'sobra');
                            ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm">
                                        <?php if ($centro['foto_url']): ?>
                                            <img src="<?= htmlspecialchars($centro['foto_url']) ?>"
                                                 class="card-img-top" alt="Foto del centro"
                                                 style="height: 180px; object-fit: cover;"
                                                 onerror="this.style.display='none'">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <span class="badge badge-av-blue-light me-1">
                                                    <?= htmlspecialchars($centro['estado']) ?>
                                                </span>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($centro['municipio']) ?></span>
                                            </h5>
                                            <p class="card-text small mb-1">
                                                <i class="bi bi-geo-alt"></i>
                                                <?= htmlspecialchars(mb_substr($centro['direccion'], 0, 80)) ?>
                                                <?= mb_strlen($centro['direccion']) > 80 ? '...' : '' ?>
                                            </p>
                                            <?php if ($centro['telefono']): ?>
                                                <p class="card-text small mb-1">
                                                    <i class="bi bi-telephone"></i>
                                                    <a href="tel:<?= htmlspecialchars($centro['telefono']) ?>"><?= htmlspecialchars($centro['telefono']) ?></a>
                                                </p>
                                            <?php endif; ?>
                                            <div class="mt-2 small">
                                                <?php if (count($falta) > 0): ?>
                                                    <span class="text-av-red fw-semibold">❌ Falta:</span>
                                                    <?php foreach (array_slice($falta, 0, 3) as $item): ?>
                                                        <span class="badge badge-av-red-light me-1"><?= htmlspecialchars($item['item']) ?></span>
                                                    <?php endforeach; ?>
                                                    <?php if (count($falta) > 3): ?>
                                                        <span class="text-muted">+<?= count($falta) - 3 ?> más</span>
                                                    <?php endif; ?>
                                                    <br>
                                                <?php endif; ?>
                                                <?php if (count($sobra) > 0): ?>
                                                    <span class="text-av-green fw-semibold">✅ Sobra:</span>
                                                    <?php foreach (array_slice($sobra, 0, 3) as $item): ?>
                                                        <span class="badge badge-av-green-light me-1"><?= htmlspecialchars($item['item']) ?></span>
                                                    <?php endforeach; ?>
                                                    <?php if (count($sobra) > 3): ?>
                                                        <span class="text-muted">+<?= count($sobra) - 3 ?> más</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="/centro-acopio/<?= $centro['id'] ?>" class="btn btn-av-outline-blue btn-sm w-100">
                                                <i class="bi bi-eye"></i> Ver detalle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="/centros-acopio" class="btn btn-av-blue">
                                <i class="bi bi-arrow-right"></i> Ver todos los centros
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="av-tab-panel" id="panel-refugios">
                    <?php
                    $stmt = $pdo->query("
                        SELECT r.id, r.direccion, r.foto_url, r.telefono,
                               e.nombre AS estado,
                               m.nombre AS municipio
                        FROM refugios r
                        JOIN estados e ON e.id = r.estado_id
                        JOIN municipios m ON m.id = r.municipio_id
                        ORDER BY r.created_at DESC
                        LIMIT 6
                    ");
                    $refugios = $stmt->fetchAll();
                    ?>
                    <?php if (empty($refugios)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">No hay refugios registrados aún.</p>
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
                                    <div class="card h-100 shadow-sm">
                                        <?php if ($refugio['foto_url']): ?>
                                            <img src="<?= htmlspecialchars($refugio['foto_url']) ?>"
                                                 class="card-img-top" alt="Foto del refugio"
                                                 style="height: 180px; object-fit: cover;"
                                                 onerror="this.style.display='none'">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <span class="badge badge-av-blue-light me-1">
                                                    <?= htmlspecialchars($refugio['estado']) ?>
                                                </span>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($refugio['municipio']) ?></span>
                                            </h5>
                                            <p class="card-text small mb-1">
                                                <i class="bi bi-geo-alt"></i>
                                                <?= htmlspecialchars(mb_substr($refugio['direccion'], 0, 80)) ?>
                                                <?= mb_strlen($refugio['direccion']) > 80 ? '...' : '' ?>
                                            </p>
                                            <?php if ($refugio['telefono']): ?>
                                                <p class="card-text small mb-1">
                                                    <i class="bi bi-telephone"></i>
                                                    <a href="tel:<?= htmlspecialchars($refugio['telefono']) ?>"><?= htmlspecialchars($refugio['telefono']) ?></a>
                                                </p>
                                            <?php endif; ?>
                                            <div class="mt-2 small">
                                                <?php if (count($falta) > 0): ?>
                                                    <span class="text-av-red fw-semibold">❌ Falta:</span>
                                                    <?php foreach (array_slice($falta, 0, 3) as $item): ?>
                                                        <span class="badge badge-av-red-light me-1"><?= htmlspecialchars($item['item']) ?></span>
                                                    <?php endforeach; ?>
                                                    <?php if (count($falta) > 3): ?>
                                                        <span class="text-muted">+<?= count($falta) - 3 ?> más</span>
                                                    <?php endif; ?>
                                                    <br>
                                                <?php endif; ?>
                                                <?php if (count($sobra) > 0): ?>
                                                    <span class="text-av-green fw-semibold">✅ Sobra:</span>
                                                    <?php foreach (array_slice($sobra, 0, 3) as $item): ?>
                                                        <span class="badge badge-av-green-light me-1"><?= htmlspecialchars($item['item']) ?></span>
                                                    <?php endforeach; ?>
                                                    <?php if (count($sobra) > 3): ?>
                                                        <span class="text-muted">+<?= count($sobra) - 3 ?> más</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="/refugio/<?= $refugio['id'] ?>" class="btn btn-av-outline-blue btn-sm w-100">
                                                <i class="bi bi-eye"></i> Ver detalle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="/refugios" class="btn btn-av-red">
                                <i class="bi bi-arrow-right"></i> Ver todos los refugios
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="av-tab-panel" id="panel-averias">
                    <?php
                    $stmt = $pdo->query("
                        SELECT a.id, a.nombre, a.referencia, a.foto_url, a.estado, a.created_at,
                               e.nombre AS estado_nombre, m.nombre AS municipio
                        FROM averias a
                        JOIN estados e ON e.id = a.estado_id
                        JOIN municipios m ON m.id = a.municipio_id
                        ORDER BY a.created_at DESC
                        LIMIT 6
                    ");
                    $averias = $stmt->fetchAll();
                    ?>
                    <?php if (empty($averias)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">No hay averías reportadas aún.</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($averias as $a):
                                $badgeClass = match ($a['estado']) {
                                    'reportado'  => 'bg-secondary',
                                    'verificado' => 'bg-success',
                                    'en_proceso' => 'bg-warning text-dark',
                                    'resuelto'   => 'bg-info text-dark',
                                    default      => 'bg-secondary',
                                };
                                $estadoLabel = match ($a['estado']) {
                                    'reportado'  => 'Reportado',
                                    'verificado' => 'Verificado',
                                    'en_proceso' => 'En proceso',
                                    'resuelto'   => 'Resuelto',
                                    default      => $a['estado'],
                                };
                            ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="/averia/<?= $a['id'] ?>" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm border-danger">
                                            <?php if ($a['foto_url']): ?>
                                                <img src="<?= htmlspecialchars($a['foto_url']) ?>" class="card-img-top" alt="Foto" style="height:180px;object-fit:cover;" onerror="this.style.display='none'">
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h6 class="card-title text-danger"><?= htmlspecialchars($a['nombre']) ?></h6>
                                                <p class="card-text small text-muted mb-1">
                                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($a['estado_nombre']) ?> &middot; <?= htmlspecialchars($a['municipio']) ?>
                                                </p>
                                                <p class="card-text small mb-1">
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
                        <div class="text-center mt-4">
                            <a href="/averias/lista" class="btn btn-av-red">
                                <i class="bi bi-arrow-right"></i> Ver todas las averías
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="av-tab-panel" id="panel-damnificados">
                    <?php
                    $stmt = $pdo->query("
                        SELECT id, nombre, cedula, edad, telefono, ubicacion, foto_url, created_at
                        FROM damnificados
                        ORDER BY created_at DESC
                        LIMIT 6
                    ");
                    $damnificados = $stmt->fetchAll();
                    ?>
                    <?php if (empty($damnificados)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">No hay damnificados registrados aún.</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($damnificados as $d):
                                $partes = explode(' ', trim($d['nombre']));
                                $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1));
                                if (count($partes) > 1) {
                                    $iniciales .= mb_strtoupper(mb_substr(end($partes), 0, 1));
                                }
                            ?>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                    <div class="card shadow-sm h-100 border-danger">
                                        <?php if ($d['foto_url']): ?>
                                            <img src="<?= htmlspecialchars($d['foto_url']) ?>" class="card-img-top" alt="Foto" style="height:160px;object-fit:cover;" onerror="this.style.display='none'">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10" style="height:160px;">
                                                <span class="display-4 fw-bold text-danger"><?= htmlspecialchars($iniciales) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title text-danger mb-1"><?= htmlspecialchars($d['nombre']) ?></h6>
                                            <p class="card-text small text-muted mb-1">
                                                <i class="bi bi-credit-card"></i> V-<?= htmlspecialchars($d['cedula']) ?> &middot; <?= (int)$d['edad'] ?> años
                                            </p>
                                            <?php if ($d['telefono']): ?>
                                                <p class="card-text small text-muted mb-1"><i class="bi bi-telephone"></i> <?= htmlspecialchars($d['telefono']) ?></p>
                                            <?php endif; ?>
                                            <?php if ($d['ubicacion']): ?>
                                                <p class="card-text small text-muted mb-0"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($d['ubicacion']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="/damnificados/lista" class="btn btn-av-red">
                                <i class="bi bi-arrow-right"></i> Ver todos los damnificados
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </main>

    <?php require_once __DIR__ . '/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('btn-difundir').addEventListener('click', function () {
        navigator.clipboard.writeText('https://apoyavenezuela.com').then(() => {
            this.innerHTML = '<i class="bi bi-check-lg"></i> Copiado';
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-share"></i> Copiar enlace';
            }, 2000);
        });
    });
    </script>
</body>
</html>
