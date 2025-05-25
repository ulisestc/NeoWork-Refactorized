<?php
    session_start();
    $user_id = isset($_SESSION['id_candidato']) ? $_SESSION['id_candidato'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        window.USER_ID = <?php echo json_encode($user_id); ?>;
    </script>
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
        <div class="register-title" id="Nombre_Completo">Full Name</div>

        <div id="status-message"></div>
            <h5 class="form-label" id="correo">Correo</h5>
            <div class="mb-3">
                <div class="text-center">
                    <h6 id="edad" class="form-label">Edad</h6>
                    <p id="fecha_registro">Fecha</p>
            </div>
            <div class="mb-3 text-center">
                <a href="../view_candidato/view_candidato.php" class="btn btn-dark">
                    <i class="fa fa-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
        


    </main>

    <?php include '..\templates\footer.php' ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="user_profile.js"></script>
    
</body>

</html>