<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar empresa</title>
    <!-- icon -->
    <!-- <link rel="icon" type="image/x-icon" href="../../../public/assets/favicon.ico"> -->
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Estrellas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- estilos propios -->
    <link rel="stylesheet" type="text/css" href="../styles/styles.css" />
</head>

<body>
    <header class="header">
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="login-container">
        <div class="login-title">Calificar esta empresa</div>
        <form action="/api/" method="POST" autocomplete="off" id="ratingForm">
            <div class="mb-3">
                <label for="puesto" class="form-label">Puesto de trabajo</label>
                <input type="text" name="puesto" class="form-control" id="puesto" required autofocus
                    placeholder="Ej: Desarrollador Frontend" />
            </div>
            <div class="form-text">Indica el puesto que ocupaste en esta empresa</div>
            <div class="mb-3 position-relative">
                <label for="tiempo_emp" class="form-label">Tiempo en la empresa</label>
                <input type="tiempo_emp" name="tiempo_emp" class="form-control" id="tiempo_emp" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Calificación</label>
                <div class="rating mb-2">
                    <input type="hidden" name="rating" id="ratingValue" required>
                    <i class="fa-star far" data-value="1"></i>
                    <i class="fa-star far" data-value="2"></i>
                    <i class="fa-star far" data-value="3"></i>
                    <i class="fa-star far" data-value="4"></i>
                    <i class="fa-star far" data-value="5"></i>
                </div>
                <div class="form-text">1 estrella = Poco safisfecho, 5 estrellas = Muy satisfecho</div>
            </div>
            <div class="mb-3">
                <label for="reseña" class="form-label">Reseña</label>
                <textarea name="reseña" class="form-control" id="reseña" rows="5"
                    placeholder="Describe tu experiencia en la empresa..." required></textarea>
                <div class="form-text">Sé específico sobre tu experiencia</div>
            </div>
            <button type="submit" class="btn btn-login">Enviar</button>
        </form>

    </main>
    <?php include '..\templates\footer.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating i');
            const ratingValue = document.getElementById('ratingValue');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingValue.value = value;

                    stars.forEach((s, index) => {
                        if (index < value) {
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                });
            });

            // Validación del formulario
            document.getElementById('ratingForm').addEventListener('submit', function(e) {
                if (!ratingValue.value) {
                    e.preventDefault();
                    alert('Por favor selecciona una calificación con estrellas');
                }
            });
        });
    </script>
</body>

</html>