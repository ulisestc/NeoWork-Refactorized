$(document).ready(function() {
  $('.btn-solicitar').click(function() {
    const idPuesto = $(this).data('id-puesto');
    const idCandidato = $('#candidato-info').data('id-candidato');

    if (!idPuesto || !idCandidato) {
      alert("Faltan datos del puesto o candidato.");
      return;
    }

    $.ajax({
      url: 'http://localhost:8080/NeoWork_Refactorized/Routes/mandarSolicitud',
      method: 'POST',
      data: {
        id_puesto: idPuesto,
        id_candidato: idCandidato
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('¡Solicitud enviada con éxito!');
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function(xhr, status, error) {
        alert('Error al procesar la solicitud.');
        console.error(error);
      }
    });
  });
});


