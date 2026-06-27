<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portales Relevantes - Apoya Venezuela</title>
    <?php require_once __DIR__ . '/partials/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <?php $activeNav = 'portales'; ?>
    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

    <main class="av-main d-flex flex-column">
        <div class="container py-4 flex-grow-1 d-flex flex-column">
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
                                <span class="small">Desaparecidos Terremoto Venezuela</span>
                                <a href="https://desaparecidosterremotovenezuela.com/" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Venezuela te busca</span>
                                <a href="https://venezuelatebusca.com/" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Info Central Terremoto Venezuela</span>
                                <a href="https://info-central-terremoto-venezuela.com/index.html" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener">
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
                        <i class="bi bi-megaphone-fill display-3 text-danger"></i>
                        <h5 class="card-title mt-3">Voluntariado</h5>
                        <p class="card-text small text-muted">Regístrate como voluntario o busca grupos de ayuda organizada.</p>
                        <a href="/voluntarios" class="btn btn-outline-danger" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Ingresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-bandaid-fill display-3 text-danger"></i>
                        <h5 class="card-title mt-3">Asistencia Médica</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Ambulancia Metropolitano · (0212) 545.45.45 / 545.46.55 / 577.92.09</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Aeroambulancias · (0212) 993.25.41 / 992.89.80 / 992.89.90 / 991.79.40</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Rescarven · (0212) 993.69.11 / 993.69.91 / 993.13.10 / 993.33.67</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card shadow-sm border-danger text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-share-fill display-4 text-danger"></i>
                        <h4 class="h5 mt-2">¿No sabes cómo colaborar?</h4>
                        <p class="text-muted mb-2">Comparte y difunde esta página para que la ayuda llegue a más personas.</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="https://wa.me/?text=Portales%20%C3%BAtiles%20para%20la%20emergencia%20en%20Venezuela%3A%20https%3A%2F%2Fapoyavenezuela.com%2Fportales"
                               class="btn btn-success" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp"></i> Compartir en WhatsApp
                            </a>
                            <button class="btn btn-outline-danger" onclick="navigator.clipboard.writeText('https://apoyavenezuela.com/portales').then(()=>{this.innerHTML='<i class=\'bi bi-check-lg\'></i> Copiado'})">
                                <i class="bi bi-link-45deg"></i> Copiar enlace
                            </button>
                        </div>
                        <div class="mt-2 text-muted small">
                            <code>https://apoyavenezuela.com/portales</code>
                        </div>
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
