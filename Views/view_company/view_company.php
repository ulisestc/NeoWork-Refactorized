<?php
    session_start();
    $user_id = isset($_SESSION['id_empresa']) ? $_SESSION['id_empresa'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script>
        window.USER_ID = <?php echo json_encode($user_id); ?>;
        window.USER_TYPE = 'company';
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar empleos - NeoWork</title>
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
            <a id="user" href="../company_profile/company_profile.php" class="btn btn-outline-dark me-2">USER</a>
            <a id="logout" href="../login/login.php" class="btn btn-dark">Logout</a>
        </div>
    </header>

    <main class="container mt-4">
        <!-- Barra de búsqueda idéntica a view_candidato -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar por puesto, empresa o palabras clave" value="">
                    <button class="btn btn-dark" type="button"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>

        <!-- Filtros  -->
        <div class="row mb-4" id="filters">
            <div class="col-12">
                <div class="filters-container">
                    <select class="form-select">
                        <option value="">Todas las áreas</option>
                    </select>
                    <select class="form-select">
                        <option value="">Todas las ubicaciones</option>
                    </select>
                    <select class="form-select">
                        <option value="">Todos los salarios</option>
                    </select>
                    <!-- Botón adicional solo para empresas -->
                    <button id="add-job-btn" class="btn btn-success ms-2">
                        <i class="fas fa-plus"></i> Nueva Vacante
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenedor de trabajos  -->
        <div class="row">
            <div class="col-md-8 mx-auto" id="jobs-container">
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- Los trabajos se cargarán aquí dinámicamente -->
                        <div class="alert alert-info">Cargando empleos...</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php' ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script para la vista de empresa -->
    <script src="view_company.js"></script>
</body>
</html>