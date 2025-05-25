$(document).ready(function() {
  // Cargar vacantes al iniciar la página
  cargarVacantes();
  
  // Función para cargar las vacantes usando AJAX
  function cargarVacantes() {
      const id = $('#empresa-info').data('id-empresa');
      
      // Validar que el ID existe
      if (!id) {
          console.error('Error: No se encontró el ID de la empresa');
          $('#job-listings').html('<div class="alert alert-danger">Error: No se pudo identificar la empresa.</div>');
          return;
      }
      
      $.ajax({
          url: `/NeoWork_Refactorized/Routes/getJobsCompany/${id}`, // Cambio en la URL
          type: 'GET',
          dataType: 'json',
          beforeSend: function() {
              $('#job-listings').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
          },
          success: function(response) {
              console.log('Respuesta completa recibida:', response);
              console.log('response.success:', response.success);
              console.log('response.data:', response.data);
              console.log('Tipo de response.data:', typeof response.data);
              console.log('Es array response.data?:', Array.isArray(response.data));
              
              if (response.success && response.data) {
                  console.log('Condición cumplida, mostrando vacantes');
                  mostrarVacantes(response.data);
              } else {
                  console.log('Condición NO cumplida - Motivo:', response.message || 'Desconocido');
                  $('#job-listings').html(`<div class="alert alert-info">${response.message || 'No se encontraron vacantes disponibles.'}</div>`);
              }
          },
          error: function(xhr, status, error) {
              console.error('Error al cargar vacantes:', error);
              console.error('Status:', status);
              console.error('Respuesta del servidor:', xhr.responseText);
              
              // Mejorar el manejo de errores
              let errorMessage = 'Error desconocido';
              if (xhr.status === 404) {
                  errorMessage = 'Endpoint no encontrado (404)';
              } else if (xhr.status === 500) {
                  errorMessage = 'Error interno del servidor (500)';
              } else if (xhr.responseText) {
                  // Intentar extraer el mensaje JSON si está presente
                  try {
                      const jsonStart = xhr.responseText.indexOf('{"');
                      if (jsonStart !== -1) {
                          const jsonPart = xhr.responseText.substring(jsonStart);
                          const parsed = JSON.parse(jsonPart);
                          errorMessage = parsed.message || error;
                      } else {
                          errorMessage = error;
                      }
                  } catch (e) {
                      errorMessage = error;
                  }
              }
              
              $('#job-listings').html(`
                  <div class="alert alert-danger">
                      <strong>Error al cargar las vacantes:</strong><br>
                      <small>${errorMessage}</small><br>
                      <details>
                          <summary>Ver detalles técnicos</summary>
                          <pre style="max-height: 200px; overflow-y: auto; font-size: 11px; white-space: pre-wrap;">${xhr.responseText}</pre>
                      </details>
                  </div>
              `);
          }
      });
  }
  
  // Función para mostrar las vacantes en el DOM
  function mostrarVacantes(vacantes) {
      console.log('mostrarVacantes llamada con:', vacantes);
      console.log('vacantes.length:', vacantes.length);
      
      let html = '';
      
      if (!vacantes || vacantes.length === 0) {
          console.log('Array de vacantes está vacío o es null');
          html = '<div class="alert alert-info">No se encontraron vacantes que coincidan con tu búsqueda.</div>';
      } else {
          console.log('Procesando', vacantes.length, 'vacantes');
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
                                      <button class="btn btn-danger btn-sm me-2 btn-eliminar" 
                                              data-id-puesto="${vacante.id_puesto}">
                                          Eliminar Vacante
                                      </button>
                                      <button class="btn btn-outline-primary btn-sm btn-edit-page" 
                                              data-id-puesto="${vacante.id_puesto}">
                                          Editar
                                      </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              `;
          });
      }
      
      // Después de insertar el HTML
    $('#job-listings').html(html);

    // Agregar event listener a los botones "Eliminar Vacante"
    $('.btn-eliminar').on('click', function () {
        const idPuesto = $(this).data('id-puesto');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la vacante permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/NeoWork_Refactorized/Routes/deleteJob/${idPuesto}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al eliminar la vacante');
                    }

                    Swal.fire({
                        title: '¡Eliminada!',
                        text: 'La vacante fue eliminada correctamente.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    cargarVacantes(); // recargar lista de vacantes
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar la vacante.',
                        icon: 'error'
                    });
                });
            }
        });
    });

    $('.btn-edit-page').on('click', function() {
        const id = $(this).data('id-puesto');
        // ajusta la ruta si tu new_vacancy.php no está en raíz
        window.location.href = `/NeoWork_Refactorized/Views/new_vacancy/new_vacancy.php?edit=true&id=${id}`;
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
  
  // Botón para recargar vacantes manualmente (opcional)
  $(document).on('click', '.btn-reload-jobs', function() {
      cargarVacantes();
  });
  
  // Manejar clic en botón "Ver más"
  $(document).on('click', '.view-btn', function() {
      const idPuesto = $(this).data('id-puesto');
      mostrarDetalleVacante(idPuesto);
  });
  
  // Función para mostrar detalle de vacante
  function mostrarDetalleVacante(idPuesto) {
      $.ajax({
          url: '/NeoWork_Refactorized/Routes/getjob_edit',
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
  <button type="button" class="btn btn-danger btn-eliminar" data-id-puesto="${vacante.id_vacante}">
    Eliminar Puesto
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
  
  // Recargar vacantes cada 5 minutos (opcional)
  setInterval(function() {
      cargarVacantes();
  }, 300000); // 5 minutos
});