<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar empresa - NeoWork</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css" />
    <style>
        
    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-card">
            <div class="logo">NeoWork</div>
            
            <h2 class="form-title">Calificar esta empresa</h2>
            
            <form id="ratingForm">
                <!-- Puesto desempeñado -->
                <div class="form-group">
                    <label class="form-label" for="puesto">Puesto desempeñado</label>
                    <input type="text" class="form-control" id="puesto" name="puesto" placeholder="Ej: Desarrollador Frontend" required>
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
                    <textarea class="form-control" id="comentario" name="comentario" rows="4" placeholder="Comparte tu experiencia trabajando en esta empresa..." required></textarea>
                </div>
                
                <!-- Calificaciones -->
                <div class="rating-section">
                    <div class="rating-title">Califica tu experiencia</div>
                    
                    <!-- Ambiente laboral -->
                    <div class="rating-row">
                        <div class="rating-label">
                            <i class="fas fa-smile"></i>
                            Ambiente laboral
                        </div>
                        <div class="stars" data-rating="ambiente">
                            <i class="fas fa-star star" data-value="1"></i>
                            <i class="fas fa-star star" data-value="2"></i>
                            <i class="fas fa-star star" data-value="3"></i>
                            <i class="fas fa-star star" data-value="4"></i>
                            <i class="fas fa-star star" data-value="5"></i>
                        </div>
                    </div>
                    
                    <!-- Prestaciones -->
                    <div class="rating-row">
                        <div class="rating-label">
                            <i class="fas fa-medal"></i>
                            Prestaciones
                        </div>
                        <div class="stars" data-rating="prestaciones">
                            <i class="fas fa-star star" data-value="1"></i>
                            <i class="fas fa-star star" data-value="2"></i>
                            <i class="fas fa-star star" data-value="3"></i>
                            <i class="fas fa-star star" data-value="4"></i>
                            <i class="fas fa-star star" data-value="5"></i>
                        </div>
                    </div>
                    
                    <!-- Salario -->
                    <div class="rating-row">
                        <div class="rating-label">
                            <i class="fas fa-coins"></i>
                            Salario
                        </div>
                        <div class="stars" data-rating="salario">
                            <i class="fas fa-star star" data-value="1"></i>
                            <i class="fas fa-star star" data-value="2"></i>
                            <i class="fas fa-star star" data-value="3"></i>
                            <i class="fas fa-star star" data-value="4"></i>
                            <i class="fas fa-star star" data-value="5"></i>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane me-2"></i>
                    Enviar
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sistema de calificación por estrellas
        const ratings = {
            ambiente: 0,
            prestaciones: 0,
            salario: 0
        };

        // Manejar clics en las estrellas
        document.querySelectorAll('.stars').forEach(starsContainer => {
            const ratingType = starsContainer.getAttribute('data-rating');
            const stars = starsContainer.querySelectorAll('.star');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const rating = index + 1;
                    ratings[ratingType] = rating;
                    
                    // Actualizar visualización de estrellas
                    stars.forEach((s, i) => {
                        s.classList.toggle('active', i < rating);
                    });
                });
                
                // Efecto hover
                star.addEventListener('mouseenter', () => {
                    stars.forEach((s, i) => {
                        s.style.color = i <= index ? '#ffc107' : '#ddd';
                    });
                });
            });
            
            // Restaurar colores al salir del hover
            starsContainer.addEventListener('mouseleave', () => {
                stars.forEach((s, i) => {
                    s.style.color = i < ratings[ratingType] ? '#ffc107' : '#ddd';
                });
            });
        });

        // Manejar envío del formulario
        document.getElementById('ratingForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Validar que todas las calificaciones estén hechas
            const requiredRatings = ['ambiente', 'prestaciones', 'salario'];
            const missingRatings = requiredRatings.filter(rating => ratings[rating] === 0);
            
            if (missingRatings.length > 0) {
                alert('Por favor, califica todos los aspectos antes de enviar.');
                return;
            }
            
            // Recopilar datos del formulario
            const formData = {
                puesto: document.getElementById('puesto').value,
                meses: document.getElementById('meses').value,
                comentario: document.getElementById('comentario').value,
                ambiente_laboral: ratings.ambiente,
                prestaciones: ratings.prestaciones,
                salario: ratings.salario
            };
            
            console.log('Datos a enviar:', formData);
            
            // Aquí iría la lógica para enviar los datos al servidor
            alert('¡Gracias por tu reseña! Tu opinión es muy valiosa.');
            
        });

        // Validación en tiempo real
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', () => {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            field.addEventListener('input', () => {
                if (field.classList.contains('is-invalid') && field.value.trim()) {
                    field.classList.remove('is-invalid');
                }
            });
        });
    </script>
</body>
</html>