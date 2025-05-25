<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Puesto - NeoWork</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- estilos propios -->
    <link rel="stylesheet" type="text/css" href="../styles/styles.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- icon -->
    <link rel="icon" type="image/x-icon" href="../styles/favicon.ico">
</head>
<body>
    <header class="header">
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <!-- Título y empresa -->
                <h1 class="card-title" id="job-title">Cargando...</h1>
                <h4 class="card-subtitle mb-3 text-muted" id="company-name">Cargando...</h4>

                <!-- Detalles principales -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="detail-box p-3 border rounded">
                            <h5><i class="fas fa-money-bill-wave me-2"></i> SALARIO</h5>
                            <p id="job-salary">Cargando...</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-box p-3 border rounded">
                            <h5><i class="fas fa-list-alt me-2"></i> REQUERIMIENTOS</h5>
                            <p id="job-description">Cargando...</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-box p-3 border rounded">
                            <h5><i class="fas fa-users me-2"></i> APLICACIONES</h5>
                            <p id="applications-count">Cargando...</p>
                        </div>
                    </div>
                </div>

                <!-- Prestaciones -->
                <div class="mb-4">
                    <h4 class="mb-3">Prestaciones</h4>
                    <p class="text-justify" id="job-benefits">Cargando...</p>
                </div>

                <!-- Reseñas de la empresa -->
                <div class="mb-4" id="company-reviews-section">
                    <h4 class="mb-3">Reseñas de la empresa</h4>
                    <div id="reviews-container">
                        <!-- Las reseñas se cargarán aquí dinámicamente -->
                    </div>
                </div>

                <!-- Comentarios sobre el puesto -->
                <div class="mb-4" id="job-comments-section">
                    <h4 class="mb-3">Comentarios sobre el puesto</h4>
                    <div id="comments-container">
                        <!-- Los comentarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloque para agregar empresa -->
<div class="card mb-4">
    <div class="card-body text-center">
        <h5 class="card-title">¿Ya trabajaste aquí?</h5>
        <p class="card-text">Comparte tu opinión con los demás para ayudarlos a tomar mejores decisiones.</p>
        <a href="/../view_review/view_review.php" class="btn btn-outline-primary">
            <i class="fas fa-building-circle-plus me-2"></i>Agregar Reseña
        </a>
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
    
    <!-- Script para cargar datos -->
    <script src="../public/js/job_details.js"></script>
</body>
</html>