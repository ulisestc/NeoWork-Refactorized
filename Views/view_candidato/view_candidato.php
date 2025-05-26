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
        <h2 class="mb-0"><a id="headerLogo" href="../../index.php"><strong>NeoWork</strong></a></h2>
        <div id="header-buttons">
            <a id="user" href="../user_profile/user_profile.php" class="btn btn-outline-dark me-2">USER</a>
            
        </div>
    </header>

    <main class="container mt-4">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar por puesto, empresa o palabras clave" value="">
                    <button class="btn btn-dark" type="button"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>

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
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-8 mx-auto" id="jobs-container">
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- <h5 class="card-title">Desarrollador Frontend</h5>
                        <h6 class="card-subtitle mb-2 text-muted">TechSolutions S.A.</h6>
                        <p class="card-text"><i class="fas fa-map-marker-alt"></i> Remoto · <i class="fas fa-money-bill-wave"></i> $18,000 - $22,000</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Publicado hace 2 días</small>
                            <a href="../login/login.php" class="btn btn-sm btn-outline-dark">Ver detalles</a>
                        </div> -->
                    </div>
                </div>

            </div>
        </div>
    </main>
    <?php include '../templates/footer.php' ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Script para usurio no registrado -->
    <script src="view_candidato.js"></script>
</body>
</body>
</html>