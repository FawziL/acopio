<?php require_once __DIR__ . '/config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apoya Venezuela - Centros de Acopio y Refugios</title>
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

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">

                <h1 class="display-5 fw-bold mb-3">Apoya Venezuela</h1>
                <p class="lead text-muted mb-5">
                    Plataforma colaborativa para conectar a la comunidad durante la emergencia.
                </p>

                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-box-seam display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Centros de Acopio</h3>
                                <p class="text-muted">
                                    Puntos de recolección de donaciones.
                                </p>
                                <a href="/centros-acopio" class="btn btn-danger">
                                    <i class="bi bi-box-seam"></i> Ver centros
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-house-heart-fill display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Refugios</h3>
                                <p class="text-muted">
                                    Albergues para damnificados.
                                </p>
                                <a href="/refugios" class="btn btn-danger">
                                    <i class="bi bi-house-heart-fill"></i> Ver refugios
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-people-fill display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Voluntarios</h3>
                                <p class="text-muted">
                                    Ofrece tu ayuda como voluntario.
                                </p>
                                <a href="/voluntarios" class="btn btn-danger">
                                    <i class="bi bi-people-fill"></i> Ser voluntario
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="text-muted w-80 mb-4 font-weight-normal">Registrar un centro de acopio o refugio también es colaborar</h2>

                <div class="row g-3">
                    <div class="col-12">
                        <a href="/registrar" class="btn btn-outline-danger w-100">
                            <i class="bi bi-plus-circle"></i> Registrar centro de acopio o refugio
                        </a>
                    </div>
                </div>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-danger text-center">
                            <div class="card-body py-4">
                                <i class="bi bi-share-fill display-4 text-danger"></i>
                                <h4 class="h5 mt-2">¿No sabes cómo colaborar?</h4>
                                <p class="text-muted mb-2">Comparte y difunde esta página para que la ayuda llegue a más personas.</p>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="https://wa.me/?text=Plataforma%20de%20ayuda%20para%20la%20emergencia%20en%20Venezuela%3A%20Centros%20de%20Acopio%2C%20Refugios%20y%20m%C3%A1s%20%E2%80%94%20https%3A%2F%2Fapoyavenezuela.com"
                                       class="btn btn-success" target="_blank" rel="noopener">
                                        <i class="bi bi-whatsapp"></i> Compartir en WhatsApp
                                    </a>
                                    <button class="btn btn-outline-danger" onclick="navigator.clipboard.writeText('https://apoyavenezuela.com').then(()=>{this.innerHTML='<i class=\'bi bi-check-lg\'></i> Copiado'})">
                                        <i class="bi bi-link-45deg"></i> Copiar enlace
                                    </button>
                                </div>
                                <div class="mt-2 text-muted small">
                                    <code>https://apoyavenezuela.com</code>
                                </div>
                            </div>
                        </div>
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
            <a href="/voluntarios/lista" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-people"></i> Voluntarios</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
        <div class="container text-center text-muted small mt-1">
            Proyecto libre de uso, sin fines de lucro ni monetización. Solo colaboramos por la situación de Venezuela.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>