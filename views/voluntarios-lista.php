<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntarios Registrados - Apoya Venezuela</title>
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
                        <a class="nav-link" href="/portales"><i class="bi bi-globe2"></i> Portales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/voluntarios/lista"><i class="bi bi-people"></i> Voluntarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="/" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="/voluntarios" class="btn btn-danger">
                <i class="bi bi-plus-circle"></i> Registrarme como voluntario
            </a>
        </div>

        <h2 class="h4 mb-1">Voluntarios Registrados</h2>
        <p class="text-muted mb-4">Personas que han ofrecido su ayuda.</p>

        <form id="filtro-form" class="row g-2 mb-4">
            <div class="col-12 col-md-3">
                <select id="filtro-estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, nombre FROM estados ORDER BY nombre");
                    while ($row = $stmt->fetch()):
                    ?>
                        <option value="<?= htmlspecialchars($row['nombre']) ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select id="filtro-municipio" class="form-select">
                    <option value="">Todos los municipios</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <input type="text" id="filtro-search" class="form-control" placeholder="Buscar por nombre..." value="">
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>

        <div id="lista-voluntarios">
            <div class="text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Apoya Venezuela &mdash; Voluntarios
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const lista = document.getElementById('lista-voluntarios');
        const filtroForm = document.getElementById('filtro-form');
        const searchInput = document.getElementById('filtro-search');
        const estadoSelect = document.getElementById('filtro-estado');
        const municipioSelect = document.getElementById('filtro-municipio');

        // Cargar municipios al cambiar estado
        estadoSelect.addEventListener('change', function () {
            var nombre = this.value;
            municipioSelect.innerHTML = '<option value="">Cargando...</option>';
            if (!nombre) {
                municipioSelect.innerHTML = '<option value="">Todos los municipios</option>';
                return;
            }
            fetch('/api/municipios.php?estado_nombre=' + encodeURIComponent(nombre))
                .then(function (r) { return r.json(); })
                .catch(function () { return { data: [] }; })
                .then(function (data) {
                    municipioSelect.innerHTML = '<option value="">Todos los municipios</option>';
                    if (data.data) {
                        data.data.forEach(function (m) {
                            var opt = document.createElement('option');
                            opt.value = m.nombre;
                            opt.textContent = m.nombre;
                            municipioSelect.appendChild(opt);
                        });
                    }
                });
        });

        function cargar() {
            lista.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Cargando...</span></div></div>';

            var params = new URLSearchParams();
            var search = searchInput.value.trim();
            var estado = estadoSelect.value;
            var municipio = municipioSelect.value;

            if (search) params.set('search', search);
            if (estado) params.set('estado', estado);
            if (municipio) params.set('municipio', municipio);

            var qs = params.toString();
            var url = '/api/voluntarios.php' + (qs ? '?' + qs : '');

            // Actualizar URL sin recargar
            var nuevaUrl = '/voluntarios/lista' + (qs ? '?' + qs : '');
            window.history.pushState({}, '', nuevaUrl);

            fetch(url)
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (!res.data || res.data.length === 0) {
                        lista.innerHTML = '<div class="text-center py-5"><i class="bi bi-people display-1 text-muted"></i><p class="mt-3 text-muted">No hay voluntarios registrados' + (qs ? ' con esos filtros' : '') + '.</p></div>';
                        return;
                    }

                    var html = '<p class="text-muted mb-3">' + res.total + ' voluntario' + (res.total !== 1 ? 's' : '') + ' encontrado' + (res.total !== 1 ? 's' : '') + '</p>';
                    html += '<div class="row g-3">';

                    res.data.forEach(function (v) {
                        var apoyos = Array.isArray(v.tipo_apoyo) ? v.tipo_apoyo.join(', ') : v.tipo_apoyo;

                        html += '<div class="col-12 col-md-6 col-lg-4">';
                        html += '<div class="card h-100 shadow-sm">';
                        html += '<div class="card-body">';
                        html += '<h5 class="card-title"><i class="bi bi-person-circle text-danger me-1"></i>' + escapeHtml(v.nombre) + '</h5>';
                        html += '<p class="card-text small mb-1"><i class="bi bi-telephone"></i> <a href="tel:' + escapeHtml(v.telefono) + '">' + escapeHtml(v.telefono) + '</a></p>';
                        if (v.zona) {
                            html += '<p class="card-text small mb-1"><i class="bi bi-geo-alt"></i> ' + escapeHtml(v.zona) + '</p>';
                        }
                        html += '<div class="mt-2 small">';
                        if (v.tiene_transporte) {
                            html += '<span class="badge bg-success bg-opacity-10 text-success me-1"><i class="bi bi-car-front-fill"></i> Tiene transporte</span> ';
                        }
                        if (v.necesita_transporte) {
                            html += '<span class="badge bg-warning bg-opacity-10 text-warning me-1"><i class="bi bi-question-circle-fill"></i> Necesita transporte</span> ';
                        }
                        html += '</div>';
                        html += '<p class="card-text small mt-2 mb-0"><i class="bi bi-tools"></i> ' + escapeHtml(apoyos) + '</p>';
                        html += '</div></div></div>';
                    });

                    html += '</div>';
                    lista.innerHTML = html;
                })
                .catch(function () {
                    lista.innerHTML = '<div class="alert alert-danger">Error al cargar los voluntarios.</div>';
                });
        }

        function escapeHtml(text) {
            if (!text) return '';
            var d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        // Cargar desde URL
        var params = new URLSearchParams(window.location.search);
        if (params.get('estado')) estadoSelect.value = params.get('estado');
        if (params.get('search')) searchInput.value = params.get('search');
        if (params.get('estado')) {
            var event = new Event('change');
            estadoSelect.dispatchEvent(event).then = undefined;
            // Esperar a que carguen municipios y luego setear
            var checkMunicipio = setInterval(function () {
                if (municipioSelect.options.length > 1 && params.get('municipio')) {
                    for (var i = 0; i < municipioSelect.options.length; i++) {
                        if (municipioSelect.options[i].value === params.get('municipio')) {
                            municipioSelect.value = params.get('municipio');
                            break;
                        }
                    }
                    clearInterval(checkMunicipio);
                    cargar();
                } else if (municipioSelect.options.length > 1) {
                    clearInterval(checkMunicipio);
                    cargar();
                }
            }, 100);
        } else {
            cargar();
        }

        filtroForm.addEventListener('submit', function (e) {
            e.preventDefault();
            cargar();
        });
    });
    </script>
</body>
</html>
