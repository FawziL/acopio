<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portales - Apoya Venezuela</title>
    <?php require_once __DIR__ . '/partials/head.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <?php $activeNav = 'portales'; ?>
    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

    <main class="av-main">
        <div class="container py-4">
        <h1 class="h3 mb-1">Portales Relevantes</h1>
        <p class="text-muted mb-4">Recopilación de portales y recursos útiles durante la emergencia.</p>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-av-red">
                    <div class="card-body py-4">
                        <div class="text-center">
                            <i class="bi bi-people-fill display-3 text-av-red"></i>
                            <h5 class="card-title mt-3">Personas Desaparecidas</h5>
                            <p class="card-text small text-muted mb-3">Portales para reportar y buscar desaparecidos.</p>
                        </div>
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

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-av-red">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-bandaid-fill display-3 text-av-red"></i>
                        <h5 class="card-title mt-3">Asistencia Médica</h5>
                        <p class="card-text small text-muted">Hospitales, ambulatorios y puntos de atención médica habilitados.</p>
                        <a href="#" class="btn btn-av-outline-red" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-av-yellow">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-gift-fill display-3 text-av-yellow"></i>
                        <h5 class="card-title mt-3">Donaciones Nacionales</h5>
                        <p class="card-text small text-muted">Plataformas oficiales y campañas de donación a nivel nacional.</p>
                        <a href="#" class="btn btn-av-outline-yellow" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-av-blue">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-info-circle-fill display-3 text-av-blue"></i>
                        <h5 class="card-title mt-3">Información Oficial</h5>
                        <p class="card-text small text-muted">Comunicados oficiales del gobierno y Protección Civil.</p>
                        <a href="#" class="btn btn-av-outline-blue" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-av-green">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-heart-pulse-fill display-3 text-av-green"></i>
                        <h5 class="card-title mt-3">Apoyo Psicológico</h5>
                        <p class="card-text small text-muted">Líneas de atención y recursos de apoyo emocional para damnificados.</p>
                        <a href="#" class="btn btn-av-outline-green" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-av-yellow">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-megaphone-fill display-3 text-av-yellow"></i>
                        <h5 class="card-title mt-3">Voluntariado</h5>
                        <p class="card-text small text-muted">Regístrate como voluntario o busca grupos de ayuda organizada.</p>
                        <a href="#" class="btn btn-av-outline-yellow" target="_blank" rel="noopener">
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
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
