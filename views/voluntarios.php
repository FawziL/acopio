<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntarios - Apoya Venezuela</title>
    <?php require_once __DIR__ . '/partials/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
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
                        <a class="nav-link" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="av-main">
        <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                    <a href="/" class="btn btn-av-outline-blue btn-sm">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <a href="/voluntarios/lista" class="btn btn-av-outline-red btn-sm">
                        <i class="bi bi-list-ul"></i> Ver voluntarios registrados
                    </a>
                </div>

                <h2 class="h4 mb-1">Ofrérceme como voluntario</h2>
                <p class="text-muted">
                    Registra tus datos para coordinar la ayuda. No es una donación, es tu tiempo y tus habilidades.
                </p>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="form-voluntario">

                            <div class="mb-3">
                                <label for="vol-nombre" class="form-label">Nombre completo</label>
                                <input type="text" id="vol-nombre" class="form-control" placeholder="Tu nombre" maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <select id="vol-tel-operadora" class="form-select" style="max-width: 140px;" required>
                                        <option value="0414" selected>0414</option>
                                        <option value="0412">0412</option>
                                        <option value="0416">0416</option>
                                        <option value="0422">0422</option>
                                        <option value="0424">0424</option>
                                        <option value="0426">0426</option>
                                    </select>
                                    <input type="text" id="vol-tel-numero" class="form-control"
                                           placeholder="1234567" maxlength="7" pattern="[0-9]{7}"
                                           inputmode="numeric" required>
                                </div>
                                <div class="form-text">Selecciona la operadora y escribe los 7 dígitos.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Zonas donde puedes ayudar</label>
                                <div id="vol-zonas-container">
                                    <div class="vol-zona-item border rounded p-2 mb-2">
                                        <div class="row g-2">
                                            <div class="col-12 col-md-5">
                                                <select class="form-select vol-estado" required>
                                                    <option value="">Estado</option>
                                                    <?php
                                                    $stmt = $pdo->query("SELECT id, nombre FROM estados ORDER BY nombre");
                                                    $estados = $stmt->fetchAll();
                                                    foreach ($estados as $row):
                                                    ?>
                                                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <select class="form-select vol-municipio" required>
                                                    <option value="">Selecciona un estado primero</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-2 d-flex align-items-start">
                                                <button type="button" class="btn btn-av-outline-red btn-sm vol-remover-zona" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="vol-agregar-zona" class="btn btn-av-outline-red btn-sm">
                                    <i class="bi bi-plus-circle"></i> Agregar otra zona
                                </button>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Transporte</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="tiene" id="vol-transp-tiene">
                                        <label class="form-check-label" for="vol-transp-tiene">
                                            <i class="bi bi-car-front-fill text-av-green"></i> Puedo trasladarme por mis propios medios
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="necesita" id="vol-transp-necesita">
                                        <label class="form-check-label" for="vol-transp-necesita">
                                            <i class="bi bi-question-circle-fill text-av-yellow"></i> Necesito que me busquen
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Tipo de apoyo que puedes ofrecer</label>
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Carga y descarga" id="apoyo-carga">
                                            <label class="form-check-label" for="apoyo-carga">Carga y descarga</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Transporte de suministros" id="apoyo-transporte">
                                            <label class="form-check-label" for="apoyo-transporte">Transporte de suministros</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Clasificacion de donaciones" id="apoyo-clasificacion">
                                            <label class="form-check-label" for="apoyo-clasificacion">Clasificación de donaciones</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Distribucion de ayuda" id="apoyo-distribucion">
                                            <label class="form-check-label" for="apoyo-distribucion">Distribución de ayuda</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Asistencia medica" id="apoyo-medica">
                                            <label class="form-check-label" for="apoyo-medica">Asistencia médica / primeros auxilios</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Cocina" id="apoyo-cocina">
                                            <label class="form-check-label" for="apoyo-cocina">Cocina / preparación de alimentos</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Limpieza y saneamiento" id="apoyo-limpieza">
                                            <label class="form-check-label" for="apoyo-limpieza">Limpieza y saneamiento</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Logistica" id="apoyo-logistica">
                                            <label class="form-check-label" for="apoyo-logistica">Logística y organización</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Rescate" id="apoyo-rescate">
                                            <label class="form-check-label" for="apoyo-rescate">Rescate / búsqueda</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Apoyo psicologico" id="apoyo-psicologico">
                                            <label class="form-check-label" for="apoyo-psicologico">Apoyo psicológico</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Comunicaciones" id="apoyo-comunicaciones">
                                            <label class="form-check-label" for="apoyo-comunicaciones">Comunicaciones / radio</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Alojamiento temporal" id="apoyo-alojamiento">
                                            <label class="form-check-label" for="apoyo-alojamiento">Alojamiento temporal</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Cuidado de personas" id="apoyo-cuidado">
                                            <label class="form-check-label" for="apoyo-cuidado">Cuidado de niños / adultos mayores</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Otro" id="apoyo-otro">
                                            <label class="form-check-label" for="apoyo-otro">Otro</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" id="vol-otro-container" style="display:none;">
                                    <input type="text" id="vol-otro-texto" class="form-control" placeholder="Especifica en qué más puedes ayudar" maxlength="200">
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <?php $siteKey = defined('TURNSTILE_SITE_KEY') ? TURNSTILE_SITE_KEY : 'TU_SITE_KEY_AQUI'; ?>
                                <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>"
                                     data-callback="onTurnstileVoluntarioCallback"></div>
                            </div>

                            <input type="hidden" name="turnstile_token" id="turnstile_token_voluntario">

                            <button type="submit" class="btn btn-av-red w-100">
                                <i class="bi bi-check-circle"></i> Registrarme como voluntario
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
<<<<<<< HEAD
=======
    </div>

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Apoya Venezuela &mdash; Centros de Acopio y Refugios
            &middot; <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
>>>>>>> 5594375ef987fc4dc092cb29f6dac57b1c3129c6
        </div>
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function onTurnstileVoluntarioCallback(token) {
        document.getElementById('turnstile_token_voluntario').value = token;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var otroCheckbox = document.getElementById('apoyo-otro');
        var otroContainer = document.getElementById('vol-otro-container');
        if (otroCheckbox && otroContainer) {
            otroCheckbox.addEventListener('change', function () {
                otroContainer.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    document.getElementById('vol-otro-texto').value = '';
                }
            });
        }

        function initZonaCascada(estadoSelect, municipioSelect) {
            if (!estadoSelect || !municipioSelect) return;
            estadoSelect.addEventListener('change', function () {
                var estadoId = this.value;
                municipioSelect.innerHTML = '<option value="">Cargando...</option>';
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

        function crearItemZona() {
            var container = document.getElementById('vol-zonas-container');
            var template = container.querySelector('.vol-zona-item');
            var nuevo = template.cloneNode(true);

            nuevo.querySelector('.vol-estado').value = '';
            nuevo.querySelector('.vol-municipio').innerHTML = '<option value="">Selecciona un estado primero</option>';

            var remover = nuevo.querySelector('.vol-remover-zona');
            remover.disabled = false;
            remover.addEventListener('click', function () {
                nuevo.remove();
            });

            initZonaCascada(nuevo.querySelector('.vol-estado'), nuevo.querySelector('.vol-municipio'));
            container.appendChild(nuevo);
        }

        var primerItem = document.querySelector('.vol-zona-item');
        if (primerItem) {
            initZonaCascada(primerItem.querySelector('.vol-estado'), primerItem.querySelector('.vol-municipio'));
        }

        document.getElementById('vol-agregar-zona').addEventListener('click', crearItemZona);

        var transpTiene = document.getElementById('vol-transp-tiene');
        var transpNecesita = document.getElementById('vol-transp-necesita');
        if (transpTiene && transpNecesita) {
            transpTiene.addEventListener('change', function () {
                if (this.checked) transpNecesita.checked = false;
            });
            transpNecesita.addEventListener('change', function () {
                if (this.checked) transpTiene.checked = false;
            });
        }

        var form = document.getElementById('form-voluntario');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var tokenInput = document.getElementById('turnstile_token_voluntario');
            var token = tokenInput ? tokenInput.value : '';
            if (!token) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verificación requerida',
                    text: 'Completa la verificación de seguridad.',
                    confirmButtonColor: '#1E3A8A',
                });
                return;
            }

            var apoyos = [];
            document.querySelectorAll('#form-voluntario input[type="checkbox"][value]').forEach(function (cb) {
                if (cb.checked) {
                    if (cb.id === 'apoyo-otro') {
                        var texto = document.getElementById('vol-otro-texto').value.trim();
                        if (texto) apoyos.push('Otro: ' + texto);
                    } else {
                        apoyos.push(cb.value);
                    }
                }
            });

            if (apoyos.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tipo de apoyo requerido',
                    text: 'Selecciona al menos un tipo de apoyo.',
                    confirmButtonColor: '#1E3A8A',
                });
                return;
            }

            var transporte = [];
            if (document.getElementById('vol-transp-tiene').checked) transporte.push('tiene');
            if (document.getElementById('vol-transp-necesita').checked) transporte.push('necesita');

            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Registrando...';

            var zonas = [];
            document.querySelectorAll('.vol-zona-item').forEach(function (item) {
                var e = item.querySelector('.vol-estado');
                var m = item.querySelector('.vol-municipio');
                if (e && m && e.value && m.value) {
                    zonas.push(e.options[e.selectedIndex].text + ', ' + m.options[m.selectedIndex].text);
                }
            });

            var data = {
                nombre: document.getElementById('vol-nombre').value.trim(),
                telefono: document.getElementById('vol-tel-operadora').value + document.getElementById('vol-tel-numero').value.trim(),
                zona: zonas.join('; '),
                transporte: transporte,
                tipo_apoyo: apoyos,
                turnstile_token: token,
            };

            fetch('/api/voluntarios.php', {
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
                        confirmButtonColor: '#1E3A8A',
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado',
                        text: res.mensaje || '¡Gracias por ofrecer tu apoyo!',
                        confirmButtonColor: '#1E3A8A',
                    }).then(function () {
                        form.reset();
                        document.getElementById('vol-otro-container').style.display = 'none';
                        turnstile.reset();
                    });
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrarme como voluntario';
            })
            .catch(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Error al registrar. Intenta de nuevo.',
                    confirmButtonColor: '#1E3A8A',
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Registrarme como voluntario';
            });
        });
    });
    </script>
</body>
</html>
