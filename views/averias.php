<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar Avería - Apoya Venezuela</title>
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
                        <a class="nav-link" href="/averias"><i class="bi bi-exclamation-triangle"></i> Averías</a>
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
                    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <a href="/averias/lista" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-list-ul"></i> Ver averías reportadas
                    </a>
                </div>

                <h2 class="h4 mb-1">Reportar avería</h2>
                <p class="text-muted">Reporta inmuebles afectados para coordinar ayudas y evaluaciones.</p>

                <div class="card shadow-sm">
                    <div class="card-body small">
                        <form id="form-averia">
                            <div class="mb-3">
                                <label for="avg-nombre" class="form-label">Nombre del inmueble</label>
                                <input type="text" id="avg-nombre" class="form-control" placeholder="Ej: Edificio Miranda, Casa de la Cultura..." maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label for="avg-estado" class="form-label">Estado</label>
                                <select id="avg-estado" class="form-select" required>
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
                                <label for="avg-municipio" class="form-label">Municipio</label>
                                <select id="avg-municipio" class="form-select" required>
                                    <option value="">Selecciona un estado primero</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="avg-parroquia" class="form-label">Parroquia <small class="text-muted">(opcional)</small></label>
                                <select id="avg-parroquia" class="form-select">
                                    <option value="">Selecciona un municipio primero</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="avg-referencia" class="form-label">Referencia / Dirección</label>
                                <textarea id="avg-referencia" class="form-control" rows="3"
                                          placeholder="Ej: Av. Principal, diagonal a la plaza Bolívar, edificio de 3 pisos color azul" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teléfono de contacto <small class="text-muted">(opcional)</small></label>
                                <div class="input-group">
                                    <select id="avg-tel-operadora" class="form-select" style="max-width: 140px;">
                                        <option value="0414" selected>0414</option>
                                        <option value="0412">0412</option>
                                        <option value="0416">0416</option>
                                        <option value="0422">0422</option>
                                        <option value="0424">0424</option>
                                        <option value="0426">0426</option>
                                    </select>
                                    <input type="text" id="avg-tel-numero" class="form-control"
                                           placeholder="1234567" maxlength="7" pattern="[0-9]{7}"
                                           inputmode="numeric">
                                </div>
                                <div class="form-text">Selecciona la operadora y escribe los 7 dígitos.</div>
                            </div>

                            <div class="mb-3">
                                <label for="avg-foto" class="form-label">Foto del inmueble <small class="text-muted">(opcional)</small></label>
                                <input type="file" id="avg-foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <img id="avg-foto-preview" class="mt-2 rounded d-none" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <div class="form-text">Máximo 5 MB. JPG, PNG o WebP.</div>
                            </div>

                            <div class="mb-3">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileAveriaCallback"></div>
                            </div>

                            <input type="hidden" name="turnstile_token" id="turnstile_token_averia">

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-check-circle"></i> Reportar avería
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
            &middot; <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
    <script>
    function onTurnstileAveriaCallback(token) {
        document.getElementById('turnstile_token_averia').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var estadoSelect = document.getElementById('avg-estado');
        var municipioSelect = document.getElementById('avg-municipio');
        var parroquiaSelect = document.getElementById('avg-parroquia');

        if (estadoSelect) {
            estadoSelect.addEventListener('change', function () {
                var estadoId = this.value;
                municipioSelect.innerHTML = '<option value="">Cargando...</option>';
                parroquiaSelect.innerHTML = '<option value="">Selecciona un municipio primero</option>';

                if (!estadoId) {
                    municipioSelect.innerHTML = '<option value="">Selecciona un estado primero</option>';
                    return;
                }

                fetch('/api/municipios.php?estado_id=' + estadoId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        municipioSelect.innerHTML = '<option value="">Selecciona un municipio</option>';
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

        if (municipioSelect) {
            municipioSelect.addEventListener('change', function () {
                var municipioId = this.value;
                parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';

                if (!municipioId) {
                    parroquiaSelect.innerHTML = '<option value="">Selecciona un municipio primero</option>';
                    return;
                }

                fetch('/api/parroquias.php?municipio_id=' + municipioId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        parroquiaSelect.innerHTML = '<option value="">Sin parroquia</option>';
                        if (data.data) {
                            data.data.forEach(function (p) {
                                var opt = document.createElement('option');
                                opt.value = p.id;
                                opt.textContent = p.nombre;
                                parroquiaSelect.appendChild(opt);
                            });
                        }
                    })
                    .catch(function () {
                        parroquiaSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            });
        }

        // Foto preview
        var fotoInput = document.getElementById('avg-foto');
        var fotoPreview = document.getElementById('avg-foto-preview');
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

        var form = document.getElementById('form-averia');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var tokenInput = document.getElementById('turnstile_token_averia');
            var token = tokenInput ? tokenInput.value : '';
            if (!token) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verificación requerida',
                    text: 'Completa la verificación de seguridad.',
                    confirmButtonColor: '#dc3545',
                });
                return;
            }

            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';

            var fotoFile = document.getElementById('avg-foto').files[0];

            function enviarAveria(fotoUrl) {
                var data = {
                    nombre: document.getElementById('avg-nombre').value.trim(),
                    estado_id: parseInt(document.getElementById('avg-estado').value),
                    municipio_id: parseInt(document.getElementById('avg-municipio').value),
                    parroquia_id: document.getElementById('avg-parroquia').value ? parseInt(document.getElementById('avg-parroquia').value) : null,
                    referencia: document.getElementById('avg-referencia').value.trim(),
                    contacto: document.getElementById('avg-tel-operadora').value + document.getElementById('avg-tel-numero').value.trim(),
                    foto_url: fotoUrl || '',
                    turnstile_token: token,
                };

                fetch('/api/averias.php', {
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
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Reportar avería';
                    } else if (res.averia_id) {
                        window.location.href = '/averia/' + res.averia_id;
                    }
                })
                .catch(function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Error al reportar. Intenta de nuevo.',
                        confirmButtonColor: '#dc3545',
                    });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Reportar avería';
                });
            }

            if (fotoFile) {
                var fd = new FormData();
                fd.append('foto', fotoFile);
                fd.append('folder', 'averias');
                fetch('/api/upload.php', {
                    method: 'POST',
                    body: fd
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    enviarAveria(res.foto_url || '');
                })
                .catch(function () {
                    enviarAveria('');
                });
            } else {
                enviarAveria('');
            }
        });
    });
    </script>
</body>
</html>
