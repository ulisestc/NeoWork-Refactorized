<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar empresa - NeoWork</title>
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
        <div class="register-title">Registra tu empresa</div>

        <!-- Mensajes de estado -->
        <div id="status-message"></div>

        <form id="register-form" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la empresa</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección física</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="area" class="form-label">Area</label>
                <select name="area" class="form-select" id="area" required>
                    <option value="" selected disabled>Selecciona una opción</option>
                    <option value="tecnologia">Tecnologia</option>
                    <option value="automotriz">Automotriz</option>
                    <option value="alimentos">Servicio de alimentos</option>
                    <option value="diseño-grafico">Diseño grafico</option>
                    <option value="Salud">Salud</option>
                </select>
            </div>


            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico*</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="correo@empresa.com" required />

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
            ¿Buscas trabajo? <a href="../register_user/register_user.php">Ir a Candidatos</a>
        </div>
    </main>

    <?php include '..\templates\footer.php' ?>

</body>

</html>