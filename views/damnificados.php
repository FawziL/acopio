<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Damnificado - Apoya Venezuela</title>
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
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                    <a href="/damnificados/lista" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Ver listado
                    </a>
                </div>

                <h2 class="h4 mb-1">Registrar damnificado</h2>
                <p class="text-muted">Registra a personas afectadas para coordinar asistencia.</p>

                <div class="card shadow-sm">
                    <div class="card-body small">
                        <form id="form-damnificado">

                            <div class="mb-3">
                                <label for="dam-nombre" class="form-label">Nombre y apellido</label>
                                <input type="text" id="dam-nombre" class="form-control" placeholder="Nombre completo" maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label for="dam-cedula" class="form-label">Cédula de identidad</label>
                                <input type="text" id="dam-cedula" class="form-control" placeholder="12345678" maxlength="8" pattern="[0-9]{1,8}" inputmode="numeric" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label for="dam-edad" class="form-label">Edad</label>
                                    <input type="number" id="dam-edad" class="form-control" placeholder="35" min="0" max="150" maxlength="3" oninput="this.value=this.value.slice(0,3)" required>
                                </div>
                                <div class="col-6">
                                    <label for="dam-tel-operadora" class="form-label">Teléfono <small class="text-muted">(opcional)</small></label>
                                    <div class="input-group">
                                        <select id="dam-tel-operadora" class="form-select" style="max-width: 100px;">
                                            <option value="0414" selected>0414</option>
                                            <option value="0412">0412</option>
                                            <option value="0416">0416</option>
                                            <option value="0422">0422</option>
                                            <option value="0424">0424</option>
                                            <option value="0426">0426</option>
                                        </select>
                                        <input type="text" id="dam-tel-numero" class="form-control" placeholder="1234567" maxlength="7" pattern="[0-9]{7}" inputmode="numeric">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="dam-ubicacion" class="form-label">Refugio o ubicación <small class="text-muted">(opcional)</small></label>
                                <input type="text" id="dam-ubicacion" class="form-control" placeholder="Ej: Refugio La Salle, o sector Los Samanes" maxlength="300">
                            </div>

                            <div class="mb-3">
                                <label for="dam-foto" class="form-label">Foto <small class="text-muted">(opcional)</small></label>
                                <input type="file" id="dam-foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <img id="dam-foto-preview" class="mt-2 rounded d-none" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <div class="form-text">Máximo 5 MB. JPG, PNG o WebP. Si no hay foto, se mostrarán las iniciales.</div>
                            </div>

                            <div class="mb-3">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileDamCallback"></div>
                            </div>

                            <input type="hidden" name="turnstile_token" id="turnstile_token_dam">

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-check-circle"></i> Registrar damnificado
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
            Apoya Venezuela &mdash; Centros de Acopio y Refugios
            &middot; <a href="/damnificados/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Damnificados</a>
            <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function onTurnstileDamCallback(token) {
        document.getElementById('turnstile_token_dam').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var fotoInput = document.getElementById('dam-foto');
        var fotoPreview = document.getElementById('dam-foto-preview');
        if (fotoInput && fotoPreview) {
            fotoInput.addEventListener('change', function () {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        fotoPreview.src = e.target.result;
                        fotoPreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    fotoPreview.classList.add('d-none');
                    fotoPreview.src = '';
                }
            });
        }

        var form = document.getElementById('form-damnificado');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var tokenInput = document.getElementById('turnstile_token_dam');
            var token = tokenInput ? tokenInput.value : '';
            if (!token) {
                Swal.fire({ icon: 'warning', title: 'Verificación requerida', text: 'Completa la verificación de seguridad.', confirmButtonColor: '#dc3545' });
                return;
            }

            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            var fotoFile = document.getElementById('dam-foto').files[0];

            function enviar(fotoUrl) {
                var data = {
                    nombre: document.getElementById('dam-nombre').value.trim(),
                    cedula: document.getElementById('dam-cedula').value.trim(),
                    edad: parseInt(document.getElementById('dam-edad').value),
                    telefono: document.getElementById('dam-tel-operadora').value + document.getElementById('dam-tel-numero').value.trim(),
                    ubicacion: document.getElementById('dam-ubicacion').value.trim(),
                    foto_url: fotoUrl || '',
                    turnstile_token: token,
                };

                fetch('/api/damnificados.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.error, confirmButtonColor: '#dc3545' });
                    } else {
                        Swal.fire({ icon: 'success', title: 'Registrado', text: res.mensaje || 'Damnificado registrado.', confirmButtonColor: '#dc3545' }).then(function () {
                            window.location.href = '/damnificados/lista';
                        });
                    }
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrar damnificado';
                })
                .catch(function () {
                    Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'Error al registrar.', confirmButtonColor: '#dc3545' });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrar damnificado';
                });
            }

            if (fotoFile) {
                var fd = new FormData();
                fd.append('foto', fotoFile);
                fd.append('folder', 'damnificados');
                fetch('/api/upload.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (res) { enviar(res.foto_url || ''); })
                .catch(function () { enviar(''); });
            } else {
                enviar('');
            }
        });
    });
    </script>
</body>
</html>
