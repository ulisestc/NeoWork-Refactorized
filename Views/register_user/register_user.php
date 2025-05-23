<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrarse - NeoWork</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- estilos propios -->
<link rel="stylesheet" type="text/css" href="../styles/styles.css" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

</head>

<body>
    <header>
    <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="register-container">
        <div class="register-title">Regístrate</div>

        <!-- Mensajes de estado -->
        <div id="status-message"></div>

        <form id="register-form" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="nombres" class="form-label">Nombre(s)*</label>
                <input type="text" name="nombres" id="nombres" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos*</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" name="edad" id="edad" class="form-control" min="0" required />
            </div>

            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select name="sexo" id="sexo" class="form-select">
                    <option value="">Selecciona una opción</option>
                    <option value="femenino">Femenino</option>
                    <option value="masculino">Masculino</option>
                    <option value="otro">Otro</option>
                    <option value="prefiero-no-decirlo">Prefiero no decirlo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico*</label>
                <input type="email" name="email" id="email" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña*</label>
                <input type="password" name="password" id="password" class="form-control" required />
                <div class="form-text">Debe tener al menos 8 caracteres, contener una letra mayúscula, una minúscula y un número.</div>
            </div>

            <button type="submit" class="btn btn-register">Registrarse</button>
        </form>

        <div class="form-text mt-3 text-center">
            Al registrarse, acepta nuestros <a href="#">Términos de servicio</a> y <a href="#">Política de privacidad</a>.
        </div>

        <div class="additional-links">
            ¿Ya te registraste? <a href="../login/login.php">Inicia sesión</a><br />
            ¿Buscas talento? <a href="../register_company/register_company.php">Ir a NeoWork Empresas</a>
        </div>
    </main>

    <?php include '..\templates\footer.php' ?>

    <!-- Script para el registro con AJAX -->
    <script src="/neowork/public/assets/js/register.js"></script>
</body>

</html>