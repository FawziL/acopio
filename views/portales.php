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
    <?php $activeNav = 'portales'; ?>
    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

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
                                </ul>
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

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
