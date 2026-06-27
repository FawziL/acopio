<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntarios Registrados - Apoya Venezuela</title>
    <?php require_once __DIR__ . '/partials/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <?php $activeNav = 'voluntarios'; ?>
    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

    <main class="av-main">
        <div class="container py-4">

        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <h1 class="h3">Voluntarios Registrados</h1>
                <p class="text-muted">Personas que han ofrecido su ayuda.</p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="/voluntarios" class="btn btn-av-blue">
                    <i class="bi bi-plus-circle"></i> Registrarme
                </a>
            </div>
        </div>

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
                <button type="submit" class="btn btn-av-red w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>

        <div id="lista-voluntarios">
            <div class="text-center py-5">
                <div class="spinner-border text-av-red" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const lista = document.getElementById('lista-voluntarios');
        const filtroForm = document.getElementById('filtro-form');
        const searchInput = document.getElementById('filtro-search');
        const estadoSelect = document.getElementById('filtro-estado');
        const municipioSelect = document.getElementById('filtro-municipio');

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
            lista.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-av-red" role="status"><span class="visually-hidden">Cargando...</span></div></div>';

            var params = new URLSearchParams();
            var search = searchInput.value.trim();
            var estado = estadoSelect.value;
            var municipio = municipioSelect.value;

            if (search) params.set('search', search);
            if (estado) params.set('estado', estado);
            if (municipio) params.set('municipio', municipio);

            var qs = params.toString();
            var url = '/api/voluntarios.php' + (qs ? '?' + qs : '');

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
                        html += '<h5 class="card-title"><i class="bi bi-person-circle text-av-red me-1"></i>' + escapeHtml(v.nombre) + '</h5>';
                        html += '<p class="card-text small mb-1"><i class="bi bi-telephone"></i> <a href="tel:' + escapeHtml(v.telefono) + '">' + escapeHtml(v.telefono) + '</a></p>';
                        if (v.zona) {
                            html += '<p class="card-text small mb-1"><i class="bi bi-geo-alt"></i> ' + escapeHtml(v.zona) + '</p>';
                        }
                        html += '<div class="mt-2 small">';
                        if (v.tiene_transporte) {
                            html += '<span class="badge badge-av-green-light me-1"><i class="bi bi-car-front-fill"></i> Tiene transporte</span> ';
                        }
                        if (v.necesita_transporte) {
                            html += '<span class="badge badge-av-yellow-light me-1"><i class="bi bi-question-circle-fill"></i> Necesita transporte</span> ';
                        }
                        html += '</div>';
                        html += '<p class="card-text small mt-2 mb-0"><i class="bi bi-tools"></i> ' + escapeHtml(apoyos) + '</p>';
                        html += '</div></div></div>';
                    });

                    html += '</div>';
                    lista.innerHTML = html;
                })
                .catch(function () {
                    lista.innerHTML = '<div class="alert alert-av-red">Error al cargar los voluntarios.</div>';
                });
        }

        function escapeHtml(text) {
            if (!text) return '';
            var d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        var params = new URLSearchParams(window.location.search);
        if (params.get('estado')) estadoSelect.value = params.get('estado');
        if (params.get('search')) searchInput.value = params.get('search');
        if (params.get('estado')) {
            var event = new Event('change');
            estadoSelect.dispatchEvent(event).then = undefined;
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
