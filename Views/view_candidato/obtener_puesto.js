$(document).ready(function() {
  // Cargar vacantes al iniciar la página
  cargarVacantes();
  
  // Función para cargar las vacantes usando AJAX
  function cargarVacantes(termino = '') {
      $.ajax({
          url: '/NeoWork_Refactorized/Routes/getJobs',
          type: 'GET',
          data: {
              search: termino
          },
          dataType: 'json',
          beforeSend: function() {
              $('#job-listings').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
          },
          success: function(response) {
              console.log('Respuesta completa recibida:', response); // Debug
              console.log('response.success:', response.success); // Debug
              console.log('response.data:', response.data); // Debug
              console.log('Tipo de response.data:', typeof response.data); // Debug
              console.log('Es array response.data?:', Array.isArray(response.data)); // Debug
              
              if (response.success && response.data) {
                  console.log('Condición cumplida, mostrando vacantes'); // Debug
                  mostrarVacantes(response.data);
              } else {
                  console.log('Condición NO cumplida'); // Debug
                  console.log('response.success es:', response.success); // Debug
                  console.log('response.data es:', response.data); // Debug
                  $('#job-listings').html('<div class="alert alert-info">No se encontraron vacantes disponibles.</div>');
              }
          },
          error: function(xhr, status, error) {
              console.error('Error al cargar vacantes:', error);
              console.error('Respuesta del servidor:', xhr.responseText); // Debug
              $('#job-listings').html(`
                  <div class="alert alert-danger">
                      <strong>Error al cargar las vacantes:</strong><br>
                      <small>${error}</small><br>
                      <details>
                          <summary>Ver respuesta del servidor</summary>
                          <pre style="max-height: 200px; overflow-y: auto; font-size: 11px;">${xhr.responseText}</pre>
                      </details>
                  </div>
              `);
          }
      });
  }
  
  // Función para mostrar las vacantes en el DOM
  function mostrarVacantes(vacantes) {
      console.log('mostrarVacantes llamada con:', vacantes); // Debug
      console.log('vacantes.length:', vacantes.length); // Debug
      
      let html = '';
      
      if (vacantes.length === 0) {
        console.log('Array de vacantes está vacío'); // Debug
        html = '<div class="alert alert-info">No se encontraron vacantes que coincidan con tu búsqueda.</div>';
    } else {
        console.log('Procesando', vacantes.length, 'vacantes'); // Debug
        vacantes.forEach(function(vacante) {
            html += `
                <div class="col">
                    <div class="card job-card p-3">
                        <div class="d-flex">
                            <img src="/NeoWork_Refactorized/assets/img/default-company.png" 
                                 alt="Logo empresa" 
                                 class="me-3 rounded">
                            <div class="flex-grow-1">
                                <h5 class="card-title">${escapeHtml(vacante.titulo)}</h5>
                                <p class="card-text text-muted">ID Empresa: ${vacante.id_empresa}</p>
                                <p class="card-text"><small class="text-muted">Publicado: ${formatearFecha(vacante.fecha_publicacion)}</small></p>
                                <div class="mt-2">
                                    <span class="badge bg-success me-2">$${formatearSalario(vacante.salario)}</span>
                                    ${vacante.prestaciones ? `<span class="badge bg-info">${escapeHtml(vacante.prestaciones)}</span>` : ''}
                                </div>
                                <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm btn-ver-detalles" 
                                    data-id-puesto="${vacante.id_puesto}"
                                    data-id-empresa="${vacante.id_empresa}">
                                Ver más
                              </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    }
      
      $('#job-listings').html(html);

      $('.btn-ver-detalles').on('click', function() {
        const idPuesto = $(this).data('id-puesto');
        const idEmpresa = $(this).data('id-empresa');
        window.location.href = `/NeoWork_Refactorized/Views/job_details/job_details.php?id_puesto=${idPuesto}&id_empresa=${idEmpresa}`;
    });
  }
  
  // Función para escapar HTML y prevenir XSS
  function escapeHtml(text) {
      if (!text) return '';
      const map = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }
  
  // Función para formatear fecha
  function formatearFecha(fecha) {
      if (!fecha) return 'Fecha no disponible';
      const date = new Date(fecha);
      const opciones = { 
          year: 'numeric', 
          month: 'short', 
          day: 'numeric' 
      };
      return date.toLocaleDateString('es-ES', opciones);
  }
  
  // Función para formatear salario
  function formatearSalario(salario) {
      if (!salario) return 'No especificado';
      return new Intl.NumberFormat('es-MX').format(salario);
  }
  
  // Manejar búsqueda
  $('.search-bar').on('submit', function(e) {
      e.preventDefault();
      const termino = $(this).find('input[type="search"]').val().trim();
      cargarVacantes(termino);
  });
  
  // Búsqueda en tiempo real (opcional)
  let timeoutId;
  $('.search-bar input[type="search"]').on('input', function() {
      clearTimeout(timeoutId);
      const termino = $(this).val().trim();
      
      timeoutId = setTimeout(function() {
          cargarVacantes(termino);
      }, 500); // Esperar 500ms después de que el usuario deje de escribir
  });
  
  // Manejar clic en botón "Solicitar trabajo"
  $(document).on('click', '.btn-solicitar', function() {
      const idPuesto = $(this).data('id-puesto');
      const idCandidato = $('#candidato-info').data('id-candidato');
      const boton = $(this);
      
      if (!idCandidato) {
          alert('Error: No se pudo identificar al candidato. Por favor, inicia sesión nuevamente.');
          return;
      }
      
      // Deshabilitar el botón mientras se procesa
      boton.prop('disabled', true).text('Solicitando...');
      
      $.ajax({
          url: '/NeoWork_Refactorized/Routes/mandarSolicitud',
          type: 'POST',
          data: {
              id_puesto: idPuesto,
              id_candidato: idCandidato
          },
          dataType: 'json',
          success: function(response) {
              if (response.success) {
                  boton.removeClass('btn-primary')
                       .addClass('btn-success')
                       .text('Solicitado')
                       .prop('disabled', true);
                  
                  // Mostrar mensaje de éxito
                  mostrarMensaje('Solicitud enviada correctamente', 'success');
              } else {
                  boton.prop('disabled', false).text('Solicitar trabajo');
                  mostrarMensaje(response.message || 'Error al enviar la solicitud', 'danger');
              }
          },
          error: function(xhr, status, error) {
              console.error('Error al solicitar trabajo:', error);
              boton.prop('disabled', false).text('Solicitar trabajo');
              mostrarMensaje('Error al procesar la solicitud. Intenta de nuevo.', 'danger');
          }
      });
  });
  
  // Manejar clic en botón "Ver más"
  $(document).on('click', '.view-btn', function() {
      const idPuesto = $(this).data('id-puesto');
      mostrarDetalleVacante(idPuesto);
  });
  
  // Función para mostrar detalle de vacante
  function mostrarDetalleVacante(idPuesto) {
      $.ajax({
          url: '/NeoWork_Refactorized/Routes/getjob_detail',
          type: 'GET',
          data: { id_puesto: idPuesto },
          dataType: 'json',
          success: function(response) {
              if (response.success && response.data) {
                  const vacante = response.data;
                  const modalHtml = `
                      <div class="modal fade" id="detalleModal" tabindex="-1">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title">${escapeHtml(vacante.titulo)}</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body">
                                      <div class="row">
                                          <div class="col-md-8">
                                              <h6>Descripción del puesto:</h6>
                                              <p>${escapeHtml(vacante.descripcion || 'Sin descripción disponible')}</p>
                                              
                                              <h6>Prestaciones:</h6>
                                              <p>${escapeHtml(vacante.prestaciones || 'No especificadas')}</p>
                                          </div>
                                          <div class="col-md-4">
                                              <h6>Información adicional:</h6>
                                              <p><strong>Salario:</strong> $${formatearSalario(vacante.salario)}</p>
                                              <p><strong>Fecha de publicación:</strong> ${formatearFecha(vacante.fecha_publicacion)}</p>
                                              <p><strong>ID Empresa:</strong> ${vacante.id_empresa}</p>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                      <button type="button" class="btn btn-primary btn-solicitar" data-id-puesto="${vacante.id_puesto}">
                                          Solicitar trabajo
                                      </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  `;
                  
                  // Remover modal existente si hay uno
                  $('#detalleModal').remove();
                  
                  // Agregar y mostrar el nuevo modal
                  $('body').append(modalHtml);
                  $('#detalleModal').modal('show');
                  
                  // Limpiar el modal cuando se cierre
                  $('#detalleModal').on('hidden.bs.modal', function() {
                      $(this).remove();
                  });
              } else {
                  mostrarMensaje('No se pudo cargar el detalle de la vacante', 'warning');
              }
          },
          error: function(xhr, status, error) {
              console.error('Error al cargar detalle:', error);
              mostrarMensaje('Error al cargar el detalle de la vacante', 'danger');
          }
      });
  }
  
  // Función para mostrar mensajes
  function mostrarMensaje(mensaje, tipo) {
      const alertHtml = `
          <div class="alert alert-${tipo} alert-dismissible fade show position-fixed" 
               style="top: 80px; right: 20px; z-index: 1050; min-width: 300px;">
              ${mensaje}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      `;
      
      $('body').append(alertHtml);
      
      // Auto-remover después de 5 segundos
      setTimeout(function() {
          $('.alert').fadeOut();
      }, 5000);
  }
  
  // Recargar vacantes cada 30 segundos (opcional)
  setInterval(function() {
      const terminoBusqueda = $('.search-bar input[type="search"]').val().trim();
      cargarVacantes(terminoBusqueda);
  }, 30000);
});