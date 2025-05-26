<?php
session_start();
$idCandidato = $_SESSION['id_candidato'] ?? null;
$userType = $_SESSION['user_type'] ?? null;

if (!$idCandidato || $userType !== 'candidato') {
    header('Location: /NeoWork_Refactorized/Views/login/login.php');
    exit;
}

// Obtener nombre del usuario para mostrar en el header
$userName = $_SESSION['user_name'] ?? 'Usuario';

// Capturar parámetros de la URL
$id_puesto  = $_GET['id_puesto']  ?? null;
$id_empresa = $_GET['id_empresa'] ?? null;
if (!$id_puesto || !$id_empresa) {
    die('Faltan parámetros necesarios para agregar reseña.');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar empresa - NeoWork</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- estilos propios -->
    <link rel="stylesheet" type="text/css" href="../styles/styles.css" />
    <!-- icon -->
    <link rel="icon" type="image/x-icon" href="../styles/favicon.ico">
    <!-- Estilos específicos para la página de reseñas -->
    <link rel="stylesheet" type="text/css" href="styles/styles.css" />
</head>

<body>
    <header class="header d-flex justify-content-between align-items-center">
        <h2 class="mb-0"><a id="headerLogo" href="../../index.php"><strong>NeoWork</strong></a></h2>
        <div id="header-buttons">
            <a id="user" href="../user_profile/user_profile.php" class="btn btn-outline-dark me-2"><?= htmlspecialchars($userName) ?></a>
            <a id="logout" href="../login/login.php" class="btn btn-dark">Logout</a>
        </div>
    </header>

    <main class="container mt-4">
        <!-- Datos ocultos para el JS -->
        <div id="empresa-info"
            data-id-candidato="<?= htmlspecialchars($idCandidato) ?>"
            data-id-empresa="<?= htmlspecialchars($id_empresa) ?>"
            data-id-puesto="<?= htmlspecialchars($id_puesto) ?>"
            style="display:none;">
        </div>

        <div class="review-form-container">
            <h2 class="text-center mb-4">Calificar esta empresa</h2>

            <form id="ratingForm">
                <!-- Puesto desempeñado -->
                <div class="form-group mb-3">
                    <label class="form-label" for="puesto">Puesto desempeñado</label>
                    <input type="text" class="form-control" id="puesto" name="puesto"
                        placeholder="Ej: Desarrollador Frontend" required>
                </div>

                <!-- Meses laborados -->
                <div class="form-group mb-3">
                    <label class="form-label" for="meses">Tiempo en la empresa (meses)</label>
                    <input type="number" class="form-control" id="meses" name="meses"
                        placeholder="Ej: 6" min="1" max="120" required>
                    <small class="form-text text-muted">Ingresa el número de meses que trabajaste en la empresa</small>
                </div>

                <!-- Comentario -->
                <div class="form-group mb-4">
                    <label class="form-label" for="comentario">Reseña</label>
                    <textarea class="form-control" id="comentario" name="comentario" rows="4"
                        placeholder="Comparte tu experiencia trabajando en esta empresa..." required></textarea>
                </div>

                <!-- Sistema de calificaciones -->
                <div class="rating-section">
                    <div class="rating-title">Califica tu experiencia</div>

                    <!-- Ambiente laboral -->
                    <div class="rating-row">
                        <div class="rating-label">
                            <i class="fas fa-smile"></i> Ambiente laboral
                        </div>
                        <div class="stars" data-rating="ambiente">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star star" data-value="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Prestaciones -->
                    <div class="rating-row">
                        <div class="rating-label">
                            <i class="fas fa-medal"></i> Prestaciones
                        </div>
                        <div class="stars" data-rating="prestaciones">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star star" data-value="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Salario -->
                    <div class="rating-row">
                        <div class="rating-label">
                            <i class="fas fa-coins"></i> Salario
                        </div>
                        <div class="stars" data-rating="salario">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star star" data-value="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-paper-plane me-2"></i> Enviar reseña
                    </button>
                    <a href="../job_details/job_details.php?id=<?= $id_puesto ?>&id_empresa=<?= $id_empresa ?>" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-2"></i> Volver al puesto
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../templates/footer.php'; ?>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para manejar la reseña -->
    <script src="add_review.js"></script>
</body>

</html>