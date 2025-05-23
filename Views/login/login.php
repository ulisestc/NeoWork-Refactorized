<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Iniciar sesión - NeoWork</title>
    <!-- icon -->
    <!-- <link rel="icon" type="image/x-icon" href="../../../public/assets/favicon.ico"> -->
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome para el ícono de ojo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Estilos personalizados -->
    <link rel="stylesheet" type="text/css" href="../styles/styles.css" />
</head>
<body>
    <header class="header">
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="login-container">
        <div class="login-title">Iniciar sesión</div>
        <form id="login-form" action="/api/login" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico*</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus />
            </div>
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Contraseña*</label>
                <input type="password" name="password" id="password" class="form-control" required />
                <button type="button" class="password-toggle-btn" aria-label="Mostrar contraseña" onclick="togglePassword()" tabindex="-1">
                    <i id="eye-icon" class="fa-solid fa-eye"></i>
                </button>
            </div>
            <div class="mb-3 text-end forgot-password">
                <a href="#">Olvidé mi contraseña</a>
            </div>
            <button type="submit" class="btn btn-login">Iniciar sesión</button>
        </form>

        <div class="additional-links">
            ¿No tienes una cuenta? <a href="../register_user/register_user.php">Regístrate</a><br />
            ¿Buscas talento? <a href="../register_company/register_company.php">Ir a NeoWork Empresas</a>
        </div>
    </main>

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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
    </script>

    <!-- Lógica del Frontend -->
    <script src="login.js"></script>

    <!-- Mostrar/ocultar contraseña -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");

            const isVisible = passwordInput.type === "text";
            passwordInput.type = isVisible ? "password" : "text";

            eyeIcon.classList.toggle("fa-eye", !isVisible);
            eyeIcon.classList.toggle("fa-eye-slash", isVisible);
        }
    </script>
</body>
</html>
