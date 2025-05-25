<?php
session_start();
$idCandidato = $_SESSION['id_candidato'] ?? null;
if (!$idCandidato) {
    header('Location: /NeoWork_Refactorized/Views/login/login.php');
    exit;
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css" />
</head>
<body>
    <!-- Datos ocultos para el JS -->
    <div id="empresa-info" 
         data-id-candidato="<?= htmlspecialchars($idCandidato) ?>" 
         data-id-empresa="<?= htmlspecialchars($id_empresa) ?>"
         style="display:none;">
    </div>

    <div class="main-container">
        <div class="form-card">
            <div class="logo">NeoWork</div>
            <h2 class="form-title">Calificar esta empresa</h2>
            
            <form id="ratingForm">
                <!-- Puesto desempeñado -->
                <div class="form-group">
                    <label class="form-label" for="puesto">Puesto desempeñado</label>
                    <input type="text" class="form-control" id="puesto" name="puesto" 
                           placeholder="Ej: Desarrollador Frontend" required>
                </div>
                
                <!-- Meses laborados -->
                <div class="form-group">
                    <label class="form-label" for="meses">Tiempo en la empresa (meses)</label>
                    <input type="number" class="form-control" id="meses" name="meses" 
                           placeholder="Ej: 6" min="1" max="120" required>
                    <small class="form-text text-muted">Ingresa el número de meses que trabajaste en la empresa</small>
                </div>
                
                <!-- Comentario -->
                <div class="form-group">
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
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane me-2"></i> Enviar
                </button>
            </form>
        </div>
    </div>

    <footer>
        <div><strong>NeoWork</strong></div>
        <div>
            <a href="#">Aviso de privacidad</a>
            <a href="#">Términos y condiciones</a>
            <a href="#">Mapa de sitio</a>
        </div>
        <div class="footer-social">
            <a href="#" title="Instagram">📸</a>
            <a href="#" title="Facebook">📘</a>
            <a href="#" title="Twitter">🐦</a>
        </div>
        <div class="footer-copyright">
            &copy; 2025 NeoWork. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="add_review.js"></script>
</body>
</html>