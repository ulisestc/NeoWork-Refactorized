<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar vacante - NeoWork</title>
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
    <header class="header">
        <h2><strong>NeoWork</strong></h2>
    </header>

    <main class="job-container">
        <div class="register-title">Agrega una vacante</div>
        <form id="job-form" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="nombre_vacante" class="form-label">Nombre de la vacante*</label>
                <input type="text" name="nombre_vacante" id="nombre_vacante" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salario*</label>
                <input type="text" name="salario" id="salario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="area" class="form-label">Área*</label>
                <select name="area" id="area" class="form-select" required>
                    <option value="" disabled selected>Selecciona un área</option>
                    <option value="tecnologia">Tecnología</option>
                    <option value="diseno">Diseño</option>
                    <option value="finanzas">Finanzas</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="prestaciones" class="form-label">Prestaciones*</label>
                <textarea name="prestaciones" id="prestaciones" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="requerimientos" class="form-label">Requerimientos*</label>
                <textarea name="requerimientos" id="requerimientos" class="form-control" rows="8" required></textarea>
            </div>
            <button type="submit" class="btn btn-dark w-100 py-2">Publicar vacante</button>
        </form>
    </main>
    <?php include '../templates/footer.php' ?>
</body>
</html>