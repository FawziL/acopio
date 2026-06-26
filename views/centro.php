<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Acopio - Detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/centros-acopio">
                <i class="bi bi-house-heart-fill"></i> Centros de Acopio
            </a>
        </div>
    </nav>

    <?php
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        echo '<div class="container py-5"><div class="alert alert-danger">ID de centro no valido.</div></div>';
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT c.*, e.nombre AS estado, m.nombre AS municipio, p.nombre AS parroquia
        FROM centros c
        JOIN estados e ON e.id = c.estado_id
        JOIN municipios m ON m.id = c.municipio_id
        LEFT JOIN parroquias p ON p.id = c.parroquia_id
        WHERE c.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $centro = $stmt->fetch();

    if (!$centro) {
        echo '<div class="container py-5"><div class="alert alert-danger">Centro no encontrado.</div></div>';
        exit;
    }

    $stmtInv = $pdo->prepare("
        SELECT id, item, tipo, cantidad, updated_at
        FROM inventario
        WHERE centro_id = :centro_id AND activo = 1
        ORDER BY tipo, item
    ");
    $stmtInv->execute([':centro_id' => $id]);
    $items = $stmtInv->fetchAll();

    $falta = array_filter($items, fn($i) => $i['tipo'] === 'falta');
    $sobra = array_filter($items, fn($i) => $i['tipo'] === 'sobra');
    ?>

    <div class="container py-4">
        <a href="/centros-acopio" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>

        <div class="row">
            <div class="col-12 col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <?php if ($centro['foto_url']): ?>
                        <img src="<?= htmlspecialchars($centro['foto_url']) ?>"
                             class="card-img-top" alt="Foto del centro"
                             style="max-height: 300px; object-fit: cover;"
                             onerror="this.style.display='none'">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="h5 card-title">Centro de Acopio</h3>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="ps-0" style="width:100px;">Estado</th>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger"><?= htmlspecialchars($centro['estado']) ?></span></td>
                            </tr>
                            <tr>
                                <th class="ps-0">Municipio</th>
                                <td><?= htmlspecialchars($centro['municipio']) ?></td>
                            </tr>
                            <?php if ($centro['parroquia']): ?>
                            <tr>
                                <th class="ps-0">Parroquia</th>
                                <td><?= htmlspecialchars($centro['parroquia']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="ps-0">Direccion</th>
                                <td><?= nl2br(htmlspecialchars($centro['direccion'])) ?></td>
                            </tr>
                            <?php if ($centro['telefono']): ?>
                            <tr>
                                <th class="ps-0">Telefono</th>
                                <td>
                                    <a href="tel:<?= htmlspecialchars($centro['telefono']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($centro['telefono']) ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="ps-0">Registrado</th>
                                <td><small class="text-muted"><?= htmlspecialchars($centro['created_at']) ?></small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <i class="bi bi-exclamation-triangle-fill"></i> Lo que falta
                            </div>
                            <div class="card-body p-3">
                                <?php if (count($falta) > 0): ?>
                                    <div class="d-flex flex-column gap-2">
                                        <?php foreach ($falta as $item): ?>
                                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 eliminar-item"
                                                 data-id="<?= $item['id'] ?>">
                                                <span class="badge bg-danger rounded-circle p-1 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                                    <i class="bi bi-exclamation-circle text-white small"></i>
                                                </span>
                                                <div class="flex-grow-1 min-w-0">
                                                    <strong class="d-block text-truncate"><?= htmlspecialchars($item['item']) ?></strong>
                                                    <?php if ($item['cantidad']): ?>
                                                        <small class="text-danger-emphasis"><?= htmlspecialchars($item['cantidad']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <button class="btn btn-outline-danger btn-sm border-0 flex-shrink-0" title="Eliminar">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0 text-center py-3">No hay articulos registrados como faltantes.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm h-100 border-success">
                            <div class="card-header bg-success text-white">
                                <i class="bi bi-check-circle-fill"></i> Lo que sobra
                            </div>
                            <div class="card-body p-3">
                                <?php if (count($sobra) > 0): ?>
                                    <div class="d-flex flex-column gap-2">
                                        <?php foreach ($sobra as $item): ?>
                                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 eliminar-item"
                                                 data-id="<?= $item['id'] ?>">
                                                <span class="badge bg-success rounded-circle p-1 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                                    <i class="bi bi-check-circle text-white small"></i>
                                                </span>
                                                <div class="flex-grow-1 min-w-0">
                                                    <strong class="d-block text-truncate"><?= htmlspecialchars($item['item']) ?></strong>
                                                    <?php if ($item['cantidad']): ?>
                                                        <small class="text-success-emphasis"><?= htmlspecialchars($item['cantidad']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <button class="btn btn-outline-success btn-sm border-0 flex-shrink-0" title="Eliminar">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0 text-center py-3">No hay articulos registrados como sobrantes.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <i class="bi bi-plus-circle"></i> Agregar articulo
                    </div>
                    <div class="card-body">
                        <form id="form-agregar-item">
                            <input type="hidden" name="centro_id" value="<?= $id ?>">
                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <select name="tipo" class="form-select" required>
                                        <option value="falta">❌ Falta</option>
                                        <option value="sobra">✅ Sobra</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <input type="text" name="item" class="form-control" list="lista-items"
                                           placeholder="Agua, medicinas..." required autocomplete="off">
                                    <datalist id="lista-items">
                                        <option value="Agua embotellada">
                                        <option value="Agua potable">
                                        <option value="Alimentos no perecederos">
                                        <option value="Arroz">
                                        <option value="Atún enlatado">
                                        <option value="Aceite de cocina">
                                        <option value="Azúcar">
                                        <option value="Sal">
                                        <option value="Café">
                                        <option value="Harina de maíz">
                                        <option value="Harina de trigo">
                                        <option value="Pasta">
                                        <option value="Lentejas">
                                        <option value="Caraotas">
                                        <option value="Leche en polvo">
                                        <option value="Leche infantil">
                                        <option value="Fórmula láctea">
                                        <option value="Agua">
                                        <option value="Comida enlatada">
                                        <option value="Comida para bebés">
                                        <option value="Galletas">
                                        <option value="Pan">
                                        <option value="Cereal">
                                        <option value="Jugo">
                                        <option value="Manteca">
                                        <option value="Margarina">
                                        <option value="Queso">
                                        <option value="Huevos">
                                        <option value="Medicinas">
                                        <option value="Analgésicos">
                                        <option value="Antiinflamatorios">
                                        <option value="Antibióticos">
                                        <option value="Suero oral">
                                        <option value="Vitaminas">
                                        <option value="Vendas">
                                        <option value="Gasa">
                                        <option value="Alcohol">
                                        <option value="Agua oxigenada">
                                        <option value="Yodo">
                                        <option value="Curitas">
                                        <option value="Esparadrapo">
                                        <option value="Guantes quirúrgicos">
                                        <option value="Tapabocas">
                                        <option value="Jabón">
                                        <option value="Cloro">
                                        <option value="Desinfectante">
                                        <option value="Limpieza">
                                        <option value="Pañales desechables">
                                        <option value="Toallas sanitarias">
                                        <option value="Papel higiénico">
                                        <option value="Cepillo de dientes">
                                        <option value="Pasta dental">
                                        <option value="Shampoo">
                                        <option value="Ropa">
                                        <option value="Ropa infantil">
                                        <option value="Ropa adulto">
                                        <option value="Zapatos">
                                        <option value="Cobijas">
                                        <option value="Frazadas">
                                        <option value="Sábanas">
                                        <option value="Colchonetas">
                                        <option value="Hamacas">
                                        <option value="Carpa">
                                        <option value="Linterna">
                                        <option value="Velas">
                                        <option value="Fósforos">
                                        <option value="Pilas">
                                        <option value="Baterías">
                                        <option value="Lámparas">
                                        <option value="Radio">
                                        <option value="Combustible">
                                        <option value="Gasolina">
                                        <option value="Agua">
                                        <option value="Comida preparada">
                                        <option value="Comida caliente">
                                    </datalist>
                                </div>
                                <div class="col-12 col-md-4">
                                    <input type="text" name="cantidad" class="form-control"
                                           placeholder="Cantidad (opcional)">
                                </div>
                            </div>
                            <div class="mt-2">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileItemCallback"></div>
                            </div>
                            <div class="mt-2">
                                <input type="hidden" name="turnstile_token" id="turnstile_token">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-plus-circle"></i> Agregar
                                </button>
                            </div>
                        </form>
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
            WHERE r.centro_id = :centro_id AND r.activo = 1
            ORDER BY r.created_at DESC LIMIT 50
        ");
        $stmtRep->execute([':centro_id' => $id]);
        $reportes = $stmtRep->fetchAll();
        ?>

        <div class="row mt-5">
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
                            <input type="hidden" name="centro_id" value="<?= $id ?>">
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
                                          placeholder="Ej: Si estan recibiendo, yo acabo de dejar agua"
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
                        <p class="mt-2">No hay reportes aun. Se el primero en verificar este centro.</p>
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
            Centros de Acopio &mdash; Terremoto Venezuela
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
    <script>
    function onTurnstileItemCallback(token) {
        document.getElementById('turnstile_token').value = token;
    }

    function onTurnstileReporteCallback(token) {
        document.getElementById('turnstile_token_reporte').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.eliminar-item').forEach(function (el) {
            el.addEventListener('click', function (e) {
                var btn = e.target.closest('button');
                if (!btn) return;
                e.stopPropagation();
                const itemId = this.dataset.id;
                const tokenInput = document.querySelector('#form-agregar-item [name="turnstile_token"]');
                const token = tokenInput ? tokenInput.value : '';
                Swal.fire({
                    title: '¿Eliminar artículo?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    fetch('/api/inventario.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: itemId, turnstile_token: token })
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.error) {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.error, confirmButtonColor: '#dc3545' });
                        } else {
                            location.reload();
                        }
                    })
                    .catch(function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Error al eliminar.', confirmButtonColor: '#dc3545' });
                    });
                });
            });
        });

        var formReporte = document.getElementById('form-reporte');
        if (formReporte) {
            formReporte.addEventListener('submit', function (e) {
                e.preventDefault();
                var tokenInput = document.getElementById('turnstile_token_reporte');
                var token = tokenInput ? tokenInput.value : '';
                if (!token) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Verificacion requerida',
                        text: 'Completa la verificacion de seguridad.',
                        confirmButtonColor: '#dc3545',
                    });
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
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error al enviar.', confirmButtonColor: '#dc3545' });
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
                            Swal.fire({
                                icon: 'info', title: 'Ocultado',
                                text: 'Este comentario ha sido ocultado.',
                                confirmButtonColor: '#dc3545'
                            }).then(function () { location.reload(); });
                        } else {
                            Swal.fire({
                                icon: 'success', title: 'Denunciado',
                                text: 'Denuncia registrada.',
                                confirmButtonColor: '#dc3545'
                            }).then(function () { location.reload(); });
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
