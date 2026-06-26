<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Centro de Acopio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-house-heart-fill"></i> Centros de Acopio
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
                        <a class="nav-link" href="/portales"><i class="bi bi-globe2"></i> Portales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <a href="/" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>

                <h2 class="h4 mb-1">Registrar nuevo centro de acopio</h2>
                <p class="text-muted">Completa los datos del centro de acopio.</p>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="form-crear-centro">
                            <div class="mb-3">
                                <label for="paso2-estado" class="form-label">Estado</label>
                                <select id="paso2-estado" class="form-select" required>
                                    <option value="">Selecciona un estado</option>
                                    <?php
                                    $stmt = $pdo->query("SELECT id, nombre FROM estados ORDER BY nombre");
                                    while ($row = $stmt->fetch()):
                                    ?>
                                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="paso2-municipio" class="form-label">Municipio</label>
                                <select id="paso2-municipio" class="form-select" required>
                                    <option value="">Selecciona un estado primero</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="paso2-parroquia" class="form-label">Parroquia <small class="text-muted">(opcional)</small></label>
                                <select id="paso2-parroquia" class="form-select">
                                    <option value="">Selecciona un municipio primero</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="paso2-direccion" class="form-label">Dirección</label>
                                <textarea id="paso2-direccion" class="form-control" rows="3"
                                          placeholder="Ej: Av. Principal, al lado de la plaza Bolívar" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <select id="paso2-tel-operadora" class="form-select" style="max-width: 140px;" required>
                                        <option value="">Operadora</option>
                                        <option value="0412">0412</option>
                                        <option value="0414">0414</option>
                                        <option value="0416">0416</option>
                                        <option value="0422">0422</option>
                                        <option value="0424">0424</option>
                                        <option value="0426">0426</option>
                                    </select>
                                    <input type="text" id="paso2-tel-numero" class="form-control"
                                           placeholder="1234567" maxlength="7" pattern="[0-9]{7}"
                                           inputmode="numeric" required>
                                </div>
                                <div class="form-text">Selecciona la operadora y escribe los 7 dígitos.</div>
                            </div>

                            <div class="mb-3">
                                <label for="paso2-foto" class="form-label">Foto del centro <small class="text-muted">(opcional)</small></label>
                                <input type="file" id="paso2-foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <img id="paso2-foto-preview" class="mt-2 rounded d-none" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <div class="form-text">Máximo 5 MB. JPG, PNG o WebP.</div>
                            </div>

                            <div class="mb-3">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileCentroCallback"></div>
                            </div>

                            <input type="hidden" name="turnstile_token" id="turnstile_token_centro">

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-check-circle"></i> Registrar centro
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Centros de Acopio &mdash; Terremoto Venezuela
            &middot; <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
        <div class="container text-center text-muted small mt-1">
            Proyecto libre de uso, sin fines de lucro ni monetización. No nos hacemos responsables por la veracidad de la información. Solo colaboramos por la situación de Venezuela.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function onTurnstileCentroCallback(token) {
        document.getElementById('turnstile_token_centro').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-crear-centro');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            const tokenInput = document.getElementById('turnstile_token_centro');
            const token = tokenInput ? tokenInput.value : '';
            if (!token) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verificación requerida',
                    text: 'Completa la verificación de seguridad.',
                    confirmButtonColor: '#dc3545',
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrar centro';
                return;
            }

            const fotoInput = document.getElementById('paso2-foto');
            const fotoFile = fotoInput ? fotoInput.files[0] : null;

            const paso2Estado = document.getElementById('paso2-estado');
            const paso2Municipio = document.getElementById('paso2-municipio');
            const paso2Parroquia = document.getElementById('paso2-parroquia');

            function enviarCentro(fotoUrl) {
                const data = {
                    estado_id: parseInt(paso2Estado.value),
                    municipio_id: parseInt(paso2Municipio.value),
                    parroquia_id: paso2Parroquia.value ? parseInt(paso2Parroquia.value) : null,
                    direccion: document.getElementById('paso2-direccion').value.trim(),
                    telefono: document.getElementById('paso2-tel-operadora').value + document.getElementById('paso2-tel-numero').value.trim(),
                    foto_url: fotoUrl || '',
                    turnstile_token: token,
                };

                fetch('/api/centros.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.error,
                            confirmButtonColor: '#dc3545',
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrar centro';
                    } else if (res.centro_id) {
                        window.location.href = '/centro-acopio/' + res.centro_id;
                    }
                })
                .catch(function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Error al registrar. Intenta de nuevo.',
                        confirmButtonColor: '#dc3545',
                    });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrar centro';
                });
            }

            if (fotoFile) {
                var fd = new FormData();
                fd.append('foto', fotoFile);
                fetch('/api/upload.php', {
                    method: 'POST',
                    body: fd
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.foto_url) {
                        enviarCentro(res.foto_url);
                    } else {
                        enviarCentro('');
                    }
                })
                .catch(function () {
                    enviarCentro('');
                });
            } else {
                enviarCentro('');
            }
        });
    });
    </script>
</body>
</html>
