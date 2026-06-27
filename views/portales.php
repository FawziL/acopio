<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portales - Apoya Venezuela</title>
    <?php require_once __DIR__ . '/partials/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
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
                        <a class="nav-link active" href="/portales"><i class="bi bi-globe2"></i> Portales</a>
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

    <main class="av-main d-flex flex-column">
        <div class="container py-4 flex-grow-1 d-flex flex-column">
        <h1 class="h3 mb-1">Portales Relevantes</h1>
        <p class="text-muted mb-4">Recopilación de portales y recursos útiles durante la emergencia.</p>

        <div class="card shadow-sm border-0 flex-grow-1 mb-4">
            <div class="card-body p-0">
                <div class="accordion" id="accordionPortales">

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDesaparecidas">
                                <i class="bi bi-people-fill text-av-red me-2"></i> Personas Desaparecidas
                            </button>
                        </h2>
                        <div id="collapseDesaparecidas" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="text-muted small mb-3">Portales para reportar y buscar desaparecidos.</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="small">desaparecidosterremotovenezuela</span>
                                        <a href="https://desaparecidosterremotovenezuela.com/" class="btn btn-av-outline-red btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="small">Portal #2</span>
                                        <a href="#" class="btn btn-av-outline-red btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="small">Portal #3</span>
                                        <a href="#" class="btn btn-av-outline-red btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-top">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAsistencia">
                                <i class="bi bi-bandaid-fill text-av-red me-2"></i> Asistencia Médica
                            </button>
                        </h2>
                        <div id="collapseAsistencia" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="text-muted small mb-3">Hospitales, ambulatorios y puntos de atención médica habilitados.</p>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="small">Desaparecidos Terremoto Venezuela</span>
                                        <a href="https://desaparecidosterremotovenezuela.com/" class="btn btn-av-outline-red btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="small">Venezuela te busca</span>
                                        <a href="https://venezuelatebusca.com/" class="btn btn-av-outline-red btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </li>
                                </ul>
                                <a href="#" class="btn btn-av-outline-red" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i> Ingresar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-top">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo">
                                <i class="bi bi-info-circle-fill text-av-blue me-2"></i> Información Oficial
                            </button>
                        </h2>
                        <div id="collapseInfo" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="text-muted small mb-3">Comunicados oficiales del gobierno y Protección Civil.</p>
                                <a href="#" class="btn btn-av-outline-blue" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i> Ingresar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-top">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApoyo">
                                <i class="bi bi-heart-pulse-fill text-av-green me-2"></i> Apoyo Psicológico
                            </button>
                        </h2>
                        <div id="collapseApoyo" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="text-muted small mb-3">Líneas de atención y recursos de apoyo emocional para damnificados.</p>
                                <a href="#" class="btn btn-av-outline-green" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i> Ingresar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-top">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVoluntariado">
                                <i class="bi bi-megaphone-fill text-av-yellow me-2"></i> Voluntariado
                            </button>
                        </h2>
                        <div id="collapseVoluntariado" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="text-muted small mb-3">Regístrate como voluntario o busca grupos de ayuda organizada.</p>
                                <a href="#" class="btn btn-av-outline-yellow" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i> Ingresar
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="alert alert-warning mb-0" role="alert">
            <i class="bi bi-pencil-square"></i>
            <strong>¿Conoces algún portal útil?</strong>
            Los enlaces se actualizarán a medida que la comunidad los reporte.
            <a href="/sugerencias" class="alert-link">Envíanos tus sugerencias</a>.
        </div>
        </div>
    </main>

<<<<<<< HEAD
    <?php require_once __DIR__ . '/partials/footer.php'; ?>
=======
    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Apoya Venezuela &mdash; Portales Relevantes
            &middot; <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/averias/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-exclamation-triangle"></i> Averías</a>
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
        <div class="container text-center text-muted small mt-2">
            <strong>Ambulancias Aéreas:</strong><br>
            <span class="fw-semibold">Aeroambulancias</span> &middot; (0212) 993.25.41 / 992.89.80 / 992.89.90 / 991.79.40<br>
            <span class="fw-semibold">Rescarven</span> &middot; (0212) 993.69.11 / 993.69.91 / 993.13.10 / 993.33.67<br>
            <span class="fw-semibold">Ambulancia Metropolitano</span> &middot; (0212) 545.45.45 / 545.46.55 / 577.92.09
        </div>
    </footer>
>>>>>>> 5594375ef987fc4dc092cb29f6dac57b1c3129c6

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
