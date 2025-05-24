<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWork - ODS 8</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- estilos propios -->
    <link rel="stylesheet" type="text/css" href="Views/styles/styles.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- icon -->
    <link rel="icon" type="image/x-icon" href="Views/styles/favicon.ico">
    
</head>
<body>
    <header class="header">
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main>
        <div class="intro-container">
            <h1>Bienvenido a NeoWork</h1>
            <p>
                NeoWork apoya el Objetivo de Desarrollo Sostenible 8: Trabajo Decente y Crecimiento Económico.
                Buscamos conectar a candidatos con empleos que promuevan el crecimiento económico inclusivo y sostenible,
                el empleo pleno y productivo y el trabajo decente para todos.
            </p>
            <a href="Views/unregistered_user/unregistered_user.php" class="btn btn-primary">Buscar empleos</a>
        </div>
    </main>

    <?php include 'Views/templates/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>