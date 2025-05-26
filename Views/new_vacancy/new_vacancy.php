<?php
  session_start();
  $idEmpresa = $_SESSION['id_empresa'] ?? null;
  // Si no está logueado, redirige o muestra error
  if (!$idEmpresa) {
    header('Location: /NeoWork_Refactorized/Views/login/login.php');
    exit;
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script>
        window.USER_ID = <?php echo json_encode($idEmpresa); ?>;
        window.USER_TYPE = 'company';
    </script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Publicar vacante - NeoWork</title>

  <!-- Bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

  <!-- Estilos propios -->
  <link rel="stylesheet" type="text/css" href="../styles/styles.css" />

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="../styles/favicon.ico" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header class="header">
    <h2><a id="headerLogo" href="../../index.php"><strong>NeoWork</strong></a></h2>
  </header>

  <!-- Oculto: información de la empresa logueada -->
  <div
    id="empresa-info"
    data-id-empresa="<?= htmlspecialchars($idEmpresa) ?>"
    style="display:none;"
  ></div>

  <main class="job-container">
    <div class="register-title">Agrega una vacante</div>

    <!-- Contenedor para mensajes de estado (errores, éxito, loader) -->
    <div id="status-message"></div>

    <form id="job-form" method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="nombre_vacante" class="form-label"
          >Nombre de la vacante</label
        >
        <input
          type="text"
          name="nombre_vacante"
          id="nombre_vacante"
          class="form-control"
          required
        />
      </div>

      <div class="mb-3">
        <label for="requerimientos" class="form-label">Descripción</label>
        <textarea
          name="requerimientos"
          id="requerimientos"
          class="form-control"
          rows="8"
          required
        ></textarea>
      </div>

      <div class="mb-3">
        <label for="salario" class="form-label">Salario</label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input
            type="number"
            name="salario"
            id="salario"
            class="form-control"
            required
          />
        </div>
      </div>


      <div class="mb-3">
        <label for="prestaciones" class="form-label">Prestaciones</label>
        <textarea
          name="prestaciones"
          id="prestaciones"
          class="form-control"
          rows="3"
          required
        ></textarea>
      </div>

      <button type="submit" id="btn-submit" class="btn btn-dark w-100 py-2">
        Publicar vacante
      </button>
      <br><br>
      <a href="../view_company/view_company.php" class="btn btn-outline-dark w-100 py-2">
        Regresar
      </a>
    </form>
  </main>

  <?php include '../templates/footer.php' ?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Script para nueva vacante -->
  <script src="new_vacancy.js"></script>
</body>
</html>

