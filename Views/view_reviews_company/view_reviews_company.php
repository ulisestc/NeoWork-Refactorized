<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas de la empresa - NeoWork</title>
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

    <main class="container py-4">
        <h2 class="text-center fw-bold mb-4">Reseñas de la empresa</h2>
        <section id="reviews-container" class="d-flex flex-column gap-4">
            <!-- Reseñas se cargarán con AJAX -->
        </section>
    </main>

    <?php include '../templates/footer.php' ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="view_reviews_company.js"></script>
</body>

</html>
