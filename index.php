<?php require_once __DIR__ . '/config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terremoto Venezuela - Centros de Acopio y Refugios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-house-heart-fill"></i> Terremoto Venezuela
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
                        <a class="nav-link" href="/sugerencias"><i class="bi bi-chat-dots"></i> Sugerencias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">

                <h1 class="display-5 fw-bold mb-3">Terremoto Venezuela</h1>
                <p class="lead text-muted mb-5">
                    Plataforma colaborativa para conectar a la comunidad durante la emergencia.
                </p>

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-box-seam display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Centros de Acopio</h3>
                                <p class="text-muted">
                                    Puntos de recolección de donaciones. Consulta qué falta y qué sobra en cada centro.
                                </p>
                                <a href="/centros-acopio" class="btn btn-danger btn-lg">
                                    <i class="bi bi-box-seam"></i> Ver centros de acopio
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm h-100 border-danger">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-house-heart-fill display-1 text-danger"></i>
                                <h3 class="h4 mt-3">Refugios</h3>
                                <p class="text-muted">
                                    Albergues y refugios disponibles para personas damnificadas. Reporta y consulta información.
                                </p>
                                <a href="/refugios" class="btn btn-danger btn-lg">
                                    <i class="bi bi-house-heart-fill"></i> Ver refugios
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-5">

                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <a href="/registrar-centro-acopio" class="btn btn-outline-danger w-100">
                            <i class="bi bi-plus-circle"></i> Registrar centro de acopio
                        </a>
                    </div>
                    <div class="col-12 col-sm-6">
                        <a href="/registrar-refugio" class="btn btn-outline-danger w-100">
                            <i class="bi bi-plus-circle"></i> Registrar refugio
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center text-muted small">
            <i class="bi bi-house-heart-fill text-danger"></i>
            Terremoto Venezuela &mdash; Centros de Acopio y Refugios
            &middot; <a href="/portales" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-globe2"></i> Portales</a>
            <a href="/sugerencias" class="badge bg-danger bg-opacity-10 text-danger text-decoration-none ms-1"><i class="bi bi-chat-dots"></i> Sugerencias</a>
        </div>
        <div class="container text-center text-muted small mt-1">
            Proyecto libre de uso, sin fines de lucro ni monetización. No nos hacemos responsables por la veracidad de la información. Solo colaboramos por la situación de Venezuela.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>