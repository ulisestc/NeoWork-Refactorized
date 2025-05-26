<?php
// Capturar parámetros GET
$id_puesto  = $_GET['id_puesto'] ?? $_GET['id'] ?? null;
$id_empresa = $_GET['id_empresa'] ?? null;

if (!$id_puesto || !$id_empresa) {
    // Redirigir si faltan parámetros
    header('Location: unregistered_user.php');
    exit;
}

session_start();
$user_type = $_SESSION['user_type'] ?? null;

if ($user_type === 'candidato') {
    $user_id = $_SESSION['id_candidato'] ?? null;
} elseif ($user_type === 'empresa') {
    $user_id = $_SESSION['id_empresa'] ?? null;
} else {
    $user_id = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script>
        window.USER_ID = <?php echo json_encode($user_id); ?>;
        window.USER_TYPE = <?php echo json_encode($user_type); ?>;
    </script>
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
        <h2><a id="headerLogo" href="../../index.php"><strong>NeoWork</strong></a></h2>
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
                    <!-- Sección para agregar comentario -->
                    <form id="add-comment-form">
                        <div class="mb-3">
                            <label for="comment-text" class="form-label">Comentario</label>
                            <textarea class="form-control" id="comment-text" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar comentario</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bloque para agregar reseña -->
        <div id="add-review" class="card mb-4">
            <div class="card-body text-center">
                <h5 class="card-title">¿Ya trabajaste aquí?</h5>
                <p class="card-text">Comparte tu opinión con los demás para ayudarlos a tomar mejores decisiones.</p>
                <button id="btn-agregar-reseña"
                        class="btn btn-outline-primary"
                        <?= ($id_puesto && $id_empresa) ? '' : 'disabled' ?>
                        data-id-puesto="<?= htmlspecialchars($id_puesto) ?>"
                        data-id-empresa="<?= htmlspecialchars($id_empresa) ?>">
                    <i class="fas fa-building-circle-plus me-2"></i>Agregar Reseña
                </button>
            </div>
        </div>

        <!-- Botón de acción -->
        <div id="apply_and_back" class="text-center mb-4">
            <button id="apply-btn" class="btn btn-dark btn-lg">Aplicar al puesto</button>
            <br><br>
            <span id="applied-label" style="display: none; color: green; font-weight: bold;">
                ¡Ya aplicaste a este puesto!
            </span>
        </div>
    </main>

    <?php include '../templates/footer.php'; ?>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para cargar detalles -->
    <script src="job_details.js"></script>

    <!-- Script de aplicación al puesto -->
    <script>
        $(document).ready(function () {
            const userId = window.USER_ID;
            const userType = window.USER_TYPE;
            const jobId = new URLSearchParams(window.location.search).get('id_puesto') || new URLSearchParams(window.location.search).get('id');

            if (userType === 'candidato') {
                $('#apply-btn').on('click', function () {
                    $.ajax({
                        url: 'http://localhost/NeoWork_Refactorized/Routes/aplicaratrabajo',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ id_puesto: jobId, id_candidato: userId }),
                        success: function () {
                            $('#applied-label').show();
                            $('#apply-btn').prop('disabled', true);
                        },
                        error: function () {
                            alert('Error al aplicar. Intenta nuevamente.');
                        }
                    });
                });
            } else {
                $('#apply-btn').hide();
            }
        });
    </script>
</body>
</html>
