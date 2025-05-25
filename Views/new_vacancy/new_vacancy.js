$(document).ready(function() {
    const $jobForm       = $('#job-form');
    const $statusMessage = $('#status-message');
    const $submitBtn     = $jobForm.find('button[type=submit]');
    const params         = new URLSearchParams(window.location.search);
    const isEdit         = params.get('edit') === 'true';
    const editId         = params.get('id');
  
    // Si estamos en modo edición, precargamos los datos
    if (isEdit && editId) {
      console.log('[EDIT MODE] ID a editar =', editId);
      $submitBtn
        .text('Editar Vacante')
        .removeClass('btn-dark')
        .addClass('btn-primary');
  
      const urlGet = `${window.location.origin}/NeoWork_Refactorized/Routes/getJob/${editId}`;
      fetch(urlGet)
        .then(r => {
          if (!r.ok) throw new Error(`HTTP ${r.status}`);
          return r.json();
        })
        .then(json => {
          console.log('[EDIT MODE] JSON recibido:', json);
          if (json.success && json.data) {
            const v = json.data;
            $('#nombre_vacante').val(v.titulo      || '');
            $('#requerimientos').val(v.descripcion || '');
            $('#salario').val(v.salario           || '');
            $('#prestaciones').val(v.prestaciones  || '');
          } else {
            $statusMessage.html(`
              <div class="alert alert-warning">${json.message || 'No se pudo cargar los datos.'}</div>
            `);
          }
        })
        .catch(err => {
          console.error('[EDIT MODE] Error al precargar:', err);
          $statusMessage.html(`
            <div class="alert alert-danger">Error al cargar datos: ${err.message}</div>
          `);
        });
    }
  
    // Al hacer submit
    $jobForm.on('submit', function(e) {
      e.preventDefault();
      $statusMessage.html('');
  
      // Validación de salario decimal
      const salary = $('#salario').val().trim();
      if (!/^\d+(?:\.\d+)?$/.test(salary)) {
        $statusMessage.html(`
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Formato de salario incorrecto. Ejemplo: 15000.00
          </div>
        `);
        return;
      }
  
      // Payload común
      const payload = {
        id_empresa:      $('#empresa-info').data('id-empresa'),
        nombre_vacante:  $('#nombre_vacante').val(),
        requerimientos:  $('#requerimientos').val(),
        salario:         salary,
        prestaciones:    $('#prestaciones').val()
      };
  
      console.log(isEdit ? '[EDIT MODE] Payload:' : '[CREATE MODE] Payload:', payload);
  
      // URL de destino: POST para ambos casos
      const url = isEdit
        ? `/NeoWork_Refactorized/Routes/editJob/${editId}`
        : `/NeoWork_Refactorized/Routes/agregarVacante`;
  
  
      fetch(url, {
        method: 'POST',  // siempre POST
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(r => {
        console.log('[SUBMIT] Status HTTP:', r.status);
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then(data => {
        console.log('[SUBMIT] JSON recibido:', data);
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: isEdit ? 'Vacante editada' : 'Vacante publicada',
            text: data.message || '',
            timer: 2000,
            showConfirmButton: false
          });
          if (!isEdit) $jobForm[0].reset();
        } else {
          throw new Error(data.message || 'Error al guardar');
        }
      })
      .catch(err => {
        console.error('[SUBMIT] Error:', err);
        $statusMessage.html(`
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${err.message}
          </div>
        `);
      });
    });
  });
  
  


  
