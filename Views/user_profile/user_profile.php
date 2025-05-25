<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
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
    <header>
        <div><img src="" alt=""></div>
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="register-container">
        <div class="img_perfil"><img src="perfil.png" alt=""></div>
        <div class="register-title">Full Name</div>

        <!-- Mensajes de estado -->
        <div id="status-message"></div>
        <h5 class="form-label">Experiencia</h5>
        <form id="register-form" method="POST" autocomplete="off">

            <div class="mb-3">
                <h6 class="form-label">Experiencia en ventas</h6>
                <p>6 años como agente de ventas en la empresa Technova</p>
            </div>
        </form>


    </main>

    <?php include '..\templates\footer.php' ?>
</body>

</html>