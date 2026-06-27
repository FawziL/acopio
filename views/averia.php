<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avería - Detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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

    <?php
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        echo '<div class="container py-5"><div class="alert alert-danger">ID de avería no válido.</div></div>';
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT a.*, e.nombre AS estado_nombre, m.nombre AS municipio, p.nombre AS parroquia
        FROM averias a
        JOIN estados e ON e.id = a.estado_id
        JOIN municipios m ON m.id = a.municipio_id
        LEFT JOIN parroquias p ON p.id = a.parroquia_id
        WHERE a.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $averia = $stmt->fetch();

    if (!$averia) {
        echo '<div class="container py-5"><div class="alert alert-danger">Avería no encontrada.</div></div>';
        exit;
    }
    ?>

    <div class="container py-4">
        <a href="/averias/lista" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>

        <div class="row">
            <div class="col-12 col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <?php if ($averia['foto_url']): ?>
                        <img src="<?= htmlspecialchars($averia['foto_url']) ?>"
                             class="card-img-top" alt="Foto del inmueble"
                             style="max-height: 300px; object-fit: cover;"
                             onerror="this.style.display='none'">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="h5 card-title"><?= htmlspecialchars($averia['nombre']) ?></h3>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="ps-0" style="width:100px;">Estado</th>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger"><?= htmlspecialchars($averia['estado_nombre']) ?></span></td>
                            </tr>
                            <tr>
                                <th class="ps-0">Municipio</th>
                                <td><?= htmlspecialchars($averia['municipio']) ?></td>
                            </tr>
                            <?php if ($averia['parroquia']): ?>
                            <tr>
                                <th class="ps-0">Parroquia</th>
                                <td><?= htmlspecialchars($averia['parroquia']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="ps-0">Referencia</th>
                                <td><?= nl2br(htmlspecialchars($averia['referencia'])) ?></td>
                            </tr>
                            <?php if ($averia['contacto']): ?>
                            <tr>
                                <th class="ps-0">Contacto</th>
                                <td><?= htmlspecialchars($averia['contacto']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="ps-0">Estado</th>
                                <td>
                                    <?php
                                    $estadosBadge = [
                                        'reportado'  => 'bg-secondary',
                                        'verificado' => 'bg-success',
                                        'en_proceso' => 'bg-warning text-dark',
                                        'resuelto'   => 'bg-info text-dark',
                                    ];
                                    $badgeClass = $estadosBadge[$averia['estado']] ?? 'bg-secondary';
                                    $estadoLabel = match ($averia['estado']) {
                                        'reportado'  => 'Reportado',
                                        'verificado' => 'Verificado',
                                        'en_proceso' => 'En proceso',
                                        'resuelto'   => 'Resuelto',
                                        default      => $averia['estado'],
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?> status-badge"><?= $estadoLabel ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Reportado</th>
                                <td><small class="text-muted"><?= htmlspecialchars($averia['created_at']) ?></small></td>
                            </tr>
                            <tr>
                                <th class="ps-0">Actualizado</th>
                                <td><small class="text-muted"><?= htmlspecialchars($averia['updated_at']) ?></small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> Información del reporte
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">
                            Este inmueble ha sido reportado como afectado. Si tienes más información, puedes ayudar reportando a través del formulario comunitario.
                        </p>
                        <hr>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/voluntarios" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-people"></i> Ofrecerme como voluntario
                            </a>
                            <a href="/averias" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-plus-circle"></i> Reportar otra avería
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes comunitarios -->
    <?php
    $stmtRep = $pdo->prepare("
        SELECT r.id, r.nombre_anonimo, r.tipo_reporte, r.mensaje, r.created_at,
               (SELECT COUNT(*) FROM reportes_denuncias WHERE reporte_id = r.id) AS denuncias
        FROM reportes r
        WHERE r.averia_id = :averia_id AND r.activo = 1
        ORDER BY r.created_at DESC LIMIT 50
    ");
    $stmtRep->execute([':averia_id' => $id]);
    $reportes = $stmtRep->fetchAll();
    ?>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h3 class="h5 mb-3">
                    <i class="bi bi-people-fill"></i> Reportes comunitarios
                </h3>
                <p class="text-muted small mb-4">
                    Vecinos verifican y actualizan la informacion en tiempo real.
                    Los reportes con 3 denuncias se ocultan automaticamente.
                </p>
            </div>

            <div class="col-12 col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <i class="bi bi-pencil-square"></i> Agregar reporte
                    </div>
                    <div class="card-body">
                        <form id="form-reporte">
                            <input type="hidden" name="averia_id" value="<?= $id ?>">
                            <div class="mb-3">
                                <label class="form-label">Categoria</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo"
                                               id="tipo-valida" value="valida" checked>
                                        <label class="form-check-label text-success fw-semibold" for="tipo-valida">
                                            🟢 Confirmación
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo"
                                               id="tipo-alerta" value="alerta">
                                        <label class="form-check-label text-warning fw-semibold" for="tipo-alerta">
                                            ⚠️ Alerta
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo"
                                               id="tipo-denuncia" value="denuncia">
                                        <label class="form-check-label text-danger fw-semibold" for="tipo-denuncia">
                                            🚨 Denuncia
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo"
                                               id="tipo-comentario" value="comentario">
                                        <label class="form-check-label text-muted fw-semibold" for="tipo-comentario">
                                            💬 Comentario
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reporte-nombre" class="form-label">Tu nombre <small class="text-muted">(opcional)</small></label>
                                <input type="text" name="nombre" id="reporte-nombre" class="form-control" placeholder="Anonimo" maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label for="reporte-mensaje" class="form-label">Mensaje</label>
                                <textarea name="mensaje" id="reporte-mensaje" class="form-control" rows="3"
                                          placeholder="Ej: Confirme que los danos son graves"
                                          maxlength="2000" required></textarea>
                            </div>
                            <div class="mb-3">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileReporteCallback"></div>
                            </div>
                            <input type="hidden" name="turnstile_token" id="turnstile_token_reporte">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-send"></i> Enviar reporte
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <?php if (count($reportes) === 0): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-square-text display-4"></i>
                        <p class="mt-2">No hay reportes aun. Se el primero en verificar esta averia.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($reportes as $rep):
                            $icono = match ($rep['tipo_reporte']) {
                                'valida'    => '<span class="text-success fw-bold">🟢 Confirmacion</span>',
                                'alerta'    => '<span class="text-warning fw-bold">⚠️ Alerta</span>',
                                'denuncia'  => '<span class="text-danger fw-bold">🚨 Denuncia</span>',
                                default     => '<span class="text-muted fw-bold">💬 Comentario</span>',
                            };
                            $borde = match ($rep['tipo_reporte']) {
                                'valida'    => 'border-start border-success border-4',
                                'alerta'    => 'border-start border-warning border-4',
                                'denuncia'  => 'border-start border-danger border-4',
                                default     => 'border-start border-secondary border-4',
                            };
                        ?>
                            <div class="list-group-item <?= $borde ?> reporte-item" data-id="<?= $rep['id'] ?>">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($rep['nombre_anonimo']) ?></strong>
                                        <span class="mx-1">&middot;</span>
                                        <?= $icono ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted text-nowrap">
                                            <?= date('d/m/Y H:i', strtotime($rep['created_at'])) ?>
                                        </small>
                                        <button class="btn btn-outline-danger btn-sm denunciar-btn"
                                                data-id="<?= $rep['id'] ?>" title="Reportar este comentario">
                                            <i class="bi bi-flag"></i>
                                            <?php if ($rep['denuncias'] > 0): ?>
                                                <span class="badge bg-danger"><?= (int)$rep['denuncias'] ?></span>
                                            <?php endif; ?>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-0 mt-1 small"><?= nl2br(htmlspecialchars($rep['mensaje'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function onTurnstileReporteCallback(token) {
        document.getElementById('turnstile_token_reporte').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var formReporte = document.getElementById('form-reporte');
        if (formReporte) {
            formReporte.addEventListener('submit', function (e) {
                e.preventDefault();
                var tokenInput = document.getElementById('turnstile_token_reporte');
                var token = tokenInput ? tokenInput.value : '';
                if (!token) {
                    Swal.fire({ icon: 'warning', title: 'Verificacion requerida', text: 'Completa la verificacion de seguridad.', confirmButtonColor: '#dc3545' });
                    return;
                }
                var btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
                var formData = new FormData(this);
                var data = {};
                formData.forEach(function (value, key) { data[key] = value; });
                data.turnstile_token = token;
                fetch('/api/reportes.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.error, confirmButtonColor: '#dc3545' });
                    } else {
                        location.reload();
                    }
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-send"></i> Enviar reporte';
                })
                .catch(function () {
                    Swal.fire({ icon: 'error', title: 'Error de conexion', text: 'Error al enviar.', confirmButtonColor: '#dc3545' });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-send"></i> Enviar reporte';
                });
            });
        }

        document.querySelectorAll('.denunciar-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var reporteId = this.dataset.id;
                var tokenEl = document.querySelector('#form-reporte [name="cf-turnstile-response"]');
                var token = tokenEl ? tokenEl.value : '';
                Swal.fire({
                    title: '¿Denunciar comentario?',
                    text: 'Consideras que este comentario es falso o inapropiado?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, denunciar',
                    cancelButtonText: 'Cancelar',
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    fetch('/api/reportes.php?action=denunciar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ reporte_id: reporteId, turnstile_token: token })
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.error) {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.error, confirmButtonColor: '#dc3545' });
                        } else if (res.ocultado) {
                            Swal.fire({ icon: 'info', title: 'Ocultado', text: 'Este comentario ha sido ocultado.', confirmButtonColor: '#dc3545' }).then(function () { location.reload(); });
                        } else {
                            Swal.fire({ icon: 'success', title: 'Denunciado', text: 'Denuncia registrada.', confirmButtonColor: '#dc3545' }).then(function () { location.reload(); });
                        }
                    })
                    .catch(function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Error al denunciar.', confirmButtonColor: '#dc3545' });
                    });
                });
            });
        });
    });
    </script>
</body>
</html>
