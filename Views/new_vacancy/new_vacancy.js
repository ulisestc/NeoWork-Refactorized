$(document).ready(function() {
  const params = new URLSearchParams(window.location.search);
  const editId = params.get('edit');
  const companyId = window.USER_ID;
console.log('ID de la empresa:', companyId);

  console.log('ID para editar:', editId);

  if (editId) {
    $('.register-title').text('Editar Vacante');
    $('#btn-submit').text('Actualizar Vacante');
    $.ajax({
      url: 'http://localhost/NeoWork_Refactorized/Routes/getJob/' + editId,
      type: 'GET',
      success: function(response) {
        console.log(response);
        let data = response.data; 

        $('#nombre_vacante').val(data.titulo);
        $('#requerimientos').val(data.descripcion);
        $('#salario').val(data.salario);
        $('#prestaciones').val(data.prestaciones);
      },
      error: function(xhr, status, error) {
        console.error('Error fetching vacancy:', error);
      }
    });

    $('#job-form').on('submit', function(e) {
      e.preventDefault();
      // cpmvertir a JSONNNN
        const payload = {
          id: editId,
          nombre_vacante: $('#nombre_vacante').val(),
          requerimientos: $('#requerimientos').val(),
          salario: $('#salario').val(),
          prestaciones: $('#prestaciones').val()
        };
        console.log('Payload a editar:', payload);

      $.ajax({
        url: 'http://localhost/NeoWork_Refactorized/Routes/editJob/' + editId,
        type: 'POST',
        contentType: 'application/json', // ✅ 
        data: 
          JSON.stringify(payload),
        success: function(response) {
          if (response.success) {
            if (confirm('Vacante actualizada correctamente.')) {
              window.location.href = '../view_company/view_company.php';
            }
          } else {
            alert('Ocurrió un error al actualizar la vacante:\n' + response.message);
            console.error('Error en respuesta:', response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error updating vacancy:', error);
        }
      });
    });
  }else{
    $('#job-form').on('submit', function(e) {
      e.preventDefault();
  
      const payload = {
        id_empresa: companyId,
        nombre_vacante: $('#nombre_vacante').val(),    
        requerimientos: $('#requerimientos').val(),   
        salario: $('#salario').val(),
        prestaciones: $('#prestaciones').val()
      };

      console.log('Payload a enviar:', payload);

      $.ajax({
        url: 'http://localhost/NeoWork_Refactorized/Routes/agregarVacante',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(response) {
          console.log('Respuesta del servidor:', response);  
          if (response.success) {
            if (confirm('Vacante agregada correctamente.')) { 
              window.location.href = '../view_company/view_company.php';
            }
          } else {
            alert('Error al agregar la vacante: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error updating vacancy:', error);
        }
      });
    });
  }
});




