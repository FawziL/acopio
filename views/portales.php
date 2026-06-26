<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portales Relevantes - Apoya Venezuela</title>
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
                        <a class="nav-link active" href="/portales"><i class="bi bi-globe2"></i> Portales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h1 class="h3 mb-1">Portales Relevantes</h1>
        <p class="text-muted mb-4">Recopilación de portales y recursos útiles durante la emergencia.</p>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body py-4">
                        <div class="text-center">
                            <i class="bi bi-people-fill display-3 text-danger"></i>
                            <h5 class="card-title mt-3">Personas Desaparecidas</h5>
                            <p class="card-text small text-muted mb-3">Portales para reportar y buscar desaparecidos.</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">desaparecidosterremotovenezuela</span>
                                <a href="https://desaparecidosterremotovenezuela.com/" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Portal #2</span>
                                <a href="#" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Portal #3</span>
                                <a href="#" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-bandaid-fill display-3 text-danger"></i>
                        <h5 class="card-title mt-3">Asistencia Médica</h5>
                        <p class="card-text small text-muted">Hospitales, ambulatorios y puntos de atención médica habilitados.</p>
                        <a href="#" class="btn btn-outline-danger" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-gift-fill display-3 text-danger"></i>
                        <h5 class="card-title mt-3">Donaciones Nacionales</h5>
                        <p class="card-text small text-muted">Plataformas oficiales y campañas de donación a nivel nacional.</p>
                        <a href="#" class="btn btn-outline-danger" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-info-circle-fill display-3 text-danger"></i>
                        <h5 class="card-title mt-3">Información Oficial</h5>
                        <p class="card-text small text-muted">Comunicados oficiales del gobierno y Protección Civil.</p>
                        <a href="#" class="btn btn-outline-danger" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-megaphone-fill display-3 text-danger"></i>
                        <h5 class="card-title mt-3">Voluntariado</h5>
                        <p class="card-text small text-muted">Regístrate como voluntario o busca grupos de ayuda organizada.</p>
                        <a href="#" class="btn btn-outline-danger" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="alert alert-warning mt-4 mb-0" role="alert">
            <i class="bi bi-pencil-square"></i>
            <strong>¿Conoces algún portal útil?</strong>
            Los enlaces se actualizarán a medida que la comunidad los reporte.
            <a href="/sugerencias" class="alert-link">Envíanos tus sugerencias</a>.
        </div>
    </div>

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Apoya Venezuela &mdash; Portales Relevantes
            &middot; <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
        <div class="container text-center text-muted small mt-2">
            <strong>Ambulancias Aéreas:</strong><br>
            <span class="fw-semibold">Aeroambulancias</span> &middot; (0212) 993.25.41 / 992.89.80 / 992.89.90 / 991.79.40<br>
            <span class="fw-semibold">Rescarven</span> &middot; (0212) 993.69.11 / 993.69.91 / 993.13.10 / 993.33.67<br>
            <span class="fw-semibold">Ambulancia Metropolitano</span> &middot; (0212) 545.45.45 / 545.46.55 / 577.92.09
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
