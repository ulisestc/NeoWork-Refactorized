<?php
  session_start();
  $idEmpresa = $_SESSION['id_empresa'] ?? null;
  if (!$idEmpresa) {
    header('Location: /NeoWork_Refactorized/Views/login/login.php');
    exit;
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NeoWork - Vacantes</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
  <style>
    body { padding-top: 70px; }
    .job-card img { width: 100px; height: 100px; object-fit: cover; }
    .search-bar { max-width: 600px; }
    .btn-add-vacante {
      position: relative;
      float: right;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <!-- Oculto: información del candidato logueado -->
  <div id="empresa-info" data-id-empresa="<?= htmlspecialchars($idEmpresa) ?>" style="display:none;"></div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">NeoWork</a>
      <div class="dropdown ms-auto">
        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="me-2"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
          <i class="fas fa-user-circle fa-2x"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="userMenu">
          <li><a class="dropdown-item" href="#">Mi perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/NeoWork_Refactorized/Routes/logout">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    <!-- Botón Agregar Vacante -->
    <div class="row">
      <div class="col text-end">
        <a href="/NeoWork_Refactorized/Views/new_vacancy/new_vacancy.php" class="btn btn-primary btn-add-vacante">
          <i class="fas fa-plus"></i> Agregar Vacante
        </a>
      </div>
    </div>

    <!-- Búsqueda -->
    <div class="row mb-4 mt-2">
      <div class="col">
        <form class="d-flex search-bar mx-auto">
          <input class="form-control me-2" type="search" placeholder="Buscar vacantes..." aria-label="Buscar">
          <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </div>

    <!-- Listado de vacantes -->
    <div class="row row-cols-1 gy-3" id="job-listings">
      <!-- Las vacantes se cargarán aquí dinámicamente con AJAX -->
      <div class="col-12 text-center" id="loading">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Cargando vacantes...</span>
        </div>
        <p class="mt-2 text-muted">Cargando vacantes disponibles...</p>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-light mt-5 py-4">
    <div class="container text-center">
      <p class="mb-1">&copy; 2025 NeoWork. Todos los derechos reservados.</p>
      <small>
        <a href="#">Aviso de privacidad</a> |
        <a href="#">Términos y condiciones</a> |
        <a href="#">Mapa de sitio</a>
      </small>
      <div class="mt-2">
        <a href="#" class="me-2"><i class="fab fa-facebook"></i></a>
        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="view_company.js"></script>

  <script>
    // Eliminar vacante
    $(document).on('click', '.eliminar-vacante', function () {
      const id = $(this).data('id');
      if (confirm('¿Estás seguro de que deseas eliminar esta vacante?')) {
        $.ajax({
          url: `/NeoWork_Refactorized/Routes/eliminarVacante/${id}`,
          type: 'DELETE',
          success: function () {
            alert('Vacante eliminada correctamente');
            $(`#vacante-${id}`).remove();
          },
          error: function (xhr) {
            alert('Error al eliminar la vacante');
            console.error(xhr.responseText);
          }
        });
      }
    });
  </script>
</body>
</html>
