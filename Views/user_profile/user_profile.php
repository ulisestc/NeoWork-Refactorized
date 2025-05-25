<?php
session_start();
$user_id = isset($_SESSION['id_candidato']) ? $_SESSION['id_candidato'] : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script>
        window.USER_ID = <?php echo json_encode($user_id); ?>;
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario - NeoWork</title>
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
    <header class="header d-flex justify-content-between align-items-center">
        <h2 class="mb-0"><strong>NeoWork</strong></h2>
        <div id="header-buttons">
            <!-- Aquí se mostrará el nombre del usuario y el botón de logout -->
        </div>
    </header>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="img_perfil mb-3">
                            <img src="perfil.png" alt="Foto de perfil" class="rounded-circle" width="150">
                        </div>
                        <h3 id="user-fullname" class="card-title">Cargando...</h3>

                        <div class="mt-4 text-start">
                            <h5 class="form-label"><i class="fas fa-briefcase me-2"></i>Experiencia</h5>
                            <div class="mb-3">
                                <h6 class="form-label">Experiencia en ventas</h6>
                                <p>6 años como agente de ventas en la empresa Technova</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../templates/footer.php' ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script para cargar los datos del usuario -->
    <script src="user_profile.js"></script>
</body>

</html>