<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugerencias</title>
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
                        <a class="nav-link" href="/portales"><i class="bi bi-globe2"></i> Portales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/voluntarios/lista"><i class="bi bi-people"></i> Voluntarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
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

                <h2 class="h4 mb-1">Sugerencias</h2>
                <p class="text-muted">
                    ¿Tienes una idea, reportaste un error o quieres preguntar algo? Escríbenos.
                </p>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="form-sugerencia">
                            <div class="mb-3">
                                <label for="sug-nombre" class="form-label">Tu nombre <small class="text-muted">(opcional)</small></label>
                                <input type="text" id="sug-nombre" class="form-control" placeholder="Anónimo" maxlength="100">
                            </div>

                            <div class="mb-3">
                                <label for="sug-email" class="form-label">Correo electrónico <small class="text-muted">(opcional)</small></label>
                                <input type="email" id="sug-email" class="form-control" placeholder="tucorreo@ejemplo.com" maxlength="200">
                            </div>

                            <div class="mb-3">
                                <label for="sug-mensaje" class="form-label">Mensaje</label>
                                <textarea id="sug-mensaje" class="form-control" rows="4"
                                          placeholder="Ej: Sería útil poder filtrar por tipo de ayuda..."
                                          maxlength="2000" required></textarea>
                            </div>

                            <div class="mb-3">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileSugerenciaCallback"></div>
                            </div>

                            <input type="hidden" name="turnstile_token" id="turnstile_token_sugerencia">

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-send"></i> Enviar sugerencia
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
            &middot; <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function onTurnstileSugerenciaCallback(token) {
        document.getElementById('turnstile_token_sugerencia').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-sugerencia');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const tokenInput = document.getElementById('turnstile_token_sugerencia');
            const token = tokenInput ? tokenInput.value : '';
            if (!token) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verificación requerida',
                    text: 'Completa la verificación de seguridad.',
                    confirmButtonColor: '#dc3545',
                });
                return;
            }

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';

            const data = {
                nombre: document.getElementById('sug-nombre').value.trim(),
                email: document.getElementById('sug-email').value.trim(),
                mensaje: document.getElementById('sug-mensaje').value.trim(),
                turnstile_token: token,
            };

            fetch('/api/sugerencias.php', {
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
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Enviado',
                        text: res.mensaje || '¡Gracias por tu sugerencia!',
                        confirmButtonColor: '#dc3545',
                    }).then(function () {
                        form.reset();
                        turnstile.reset();
                    });
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send"></i> Enviar sugerencia';
            })
            .catch(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Error al enviar. Intenta de nuevo.',
                    confirmButtonColor: '#dc3545',
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send"></i> Enviar sugerencia';
            });
        });
    });
    </script>
</body>
</html>
