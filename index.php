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
            <div class="row">
                <div class="col-10">

                    <h1 class="display-5 fw-bold mb-1">
                        <span class="text-av-yellow">Apoya</span> <span class="text-av-blue">a</span> <span class="text-av-red">Venezuela</span>
                    </h1>
                    <hr class="hero-stripe w-50 mb-4">
                    <h2 class="h4 text-start text-muted mb-2" style="max-width: 50ch;">
                        La organización de la información también es apoyar.
                    </h2>
                    <p class="text-muted small mt-4" style="max-width: 55ch;">
                        Plataforma ciudadana para conectar centros de acopio y refugios durante la emergencia en Venezuela. No solicitamos ni gestionamos dinero, donaciones ni ayudas de ningún tipo. Nuestro único objetivo es facilitar la recopilación y organización de información que pueda contribuir a la ayuda humanitaria.
                    </p>
                    <a href="/registrar" class="btn btn-av-blue btn-lg mb-2">
                        <i class="bi bi-plus-circle"></i> Registrar centro de acopio o refugio
                    </a>
                    <div class="mt-3">
                        <p class="text-muted small mb-2">¿No sabes cómo apoyar? <strong>Difunde</strong></p>
                        <button type="button" class="btn btn-av-outline-yellow btn-sm" id="btn-difundir">
                            <i class="bi bi-share"></i> Compartir enlace
                        </button>
                    </div>

                </div>
            </div>

            <section class="av-section">
                <h2 class="h3 text-center mb-4">Portales</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-av-red">
                            <div class="card-body text-center py-4 d-flex flex-column">
                                <i class="bi bi-house-heart-fill display-3 text-av-red"></i>
                                <h5 class="card-title mt-3">Refugios</h5>
                                <p class="card-text small text-muted">
                                    Albergues disponibles para personas damnificadas.
                                </p>
                                <a href="/refugios" class="btn btn-av-red mt-auto">
                                    <i class="bi bi-house-heart"></i> Ver refugios
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-av-blue">
                            <div class="card-body text-center py-4 d-flex flex-column">
                                <i class="bi bi-box-seam display-3 text-av-blue"></i>
                                <h5 class="card-title mt-3">Centros de Acopio</h5>
                                <p class="card-text small text-muted">
                                    Puntos de recolección de donaciones.
                                </p>
                                <a href="/centros-acopio" class="btn btn-av-blue mt-auto">
                                    <i class="bi bi-box-seam"></i> Ver centros
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
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-av-red">
                            <div class="card-body text-center py-4 d-flex flex-column">
                                <i class="bi bi-bandaid-fill display-3 text-av-red"></i>
                                <h5 class="card-title mt-3">Asistencia Médica</h5>
                                <p class="card-text small text-muted">
                                    Hospitales y puntos de atención médica.
                                </p>
                                <a href="#" class="btn btn-av-outline-red mt-auto">
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
            </section>

        </div>
    </main>

    <?php require_once __DIR__ . '/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>