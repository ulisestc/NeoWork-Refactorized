<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de empresa - NeoWork</title>
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
    <header class="main-header">
        <h1>NeoWork</h1>
    </header>

    <main class="container text-center py-4">
        <div class="company-profile">
            <div class="company-icon mb-3">
                <i class="fas fa-user-circle fa-7x"></i>
            </div>
            <h2 id="company-name" class="fw-bold"></h2>
            <div id="company-rating" class="text-warning fs-4 mb-1"></div>
            <p class="text-muted mb-4">Detalles</p>
            <p id="company-description" class="company-description mx-auto" style="max-width: 600px;"></p>
        </div>

        <section id="reviews-container" class="mt-5">
            <!-- Reseñas se cargarán con AJAX -->
        </section>
    </main>

    <?php include '../templates/footer.php' ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="reviews_company.js"></script>
</body>

</html>
