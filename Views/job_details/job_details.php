<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Puesto - NeoWork</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="../styles/favicon.ico" />
</head>
<body>
    <header class="header">
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <!-- Encabezado con título y empresa -->
                <h1 class="card-title">Desarrollador Frontend</h1>
                <h4 class="card-subtitle mb-3 text-muted">TechSolutions S.A.</h4>

                <!-- Detalles principales -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="detail-box p-3 border rounded">
                            <h5><i class="fas fa-money-bill-wave me-2"></i> SALARIO</h5>
                            <p>$18,000 - $22,000 MXN</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-box p-3 border rounded">
                            <h5><i class="fas fa-list-alt me-2"></i> REQUERIMIENTOS</h5>
                            <p>3+ años de experiencia en React</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-box p-3 border rounded">
                            <h5><i class="fas fa-users me-2"></i> APLICACIONES</h5>
                            <p>15 candidatos</p>
                        </div>
                    </div>
                </div>

                <!-- Descripción y reseñas -->
                <div class="mb-4">
                    <h4 class="mb-3">Descripción del puesto</h4>
                    <p class="text-justify">Buscamos un desarrollador Frontend con experiencia en React y TypeScript para unirse a nuestro equipo remoto. Deberá colaborar en el diseño de interfaces modernas y optimizar el rendimiento.</p>
                </div>

                <div class="mb-4">
                    <h4 class="mb-3">Reseñas de la empresa</h4>
                    <div class="review-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between">
                            <strong>Juan Pérez</strong>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <p class="mb-0">"Buen ambiente laboral, pero el salario podría mejorar."</p>
                    </div>
                    <!-- Más reseñas repetidas según diseño -->
                    <div class="review-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between">
                            <strong>Ana López</strong>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="mb-0">"Excelentes prestaciones y oportunidades de crecimiento."</p>
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="mb-4">
                    <h4 class="mb-3">Comentarios sobre la publicación</h4>
                    <div class="comment-item mb-3 p-3 border rounded">
                        <strong>Carlos R.:</strong> "¿El horario es flexible?"
                    </div>
                    <div class="comment-item mb-3 p-3 border rounded">
                        <strong>Empresa:</strong> "Sí, ofrecemos horarios flexibles y home office."
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de acción -->
        <div class="text-center mb-4">
            <button id="apply-btn" class="btn btn-dark btn-lg">Aplicar al puesto</button>
        </div>
    </main>

    <?php include '../templates/footer.php' ?>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para manejar aplicaciones -->
    <script src="job_details.js"></script>
</body>
</html>