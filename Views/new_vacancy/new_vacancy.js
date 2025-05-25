// add_review.js
$(document).ready(function() {
  const $ratingForm    = $('#ratingForm');
  const $statusMessage = $('<div id="status-message" class="mb-3"></div>').prependTo($ratingForm);
  const $submitBtn     = $ratingForm.find('button[type=submit]');

  // Sistema de calificación por estrellas (igual que antes)
  const ratings = { ambiente: 0, prestaciones: 0, salario: 0 };
  $('.stars').each(function() {
    const $container = $(this);
    const type = $container.data('rating');
    const $stars = $container.find('.star');

    $stars.on('click', function() {
      const val = $(this).data('value');
      ratings[type] = val;
      $stars.each((i, s) => $(s).toggleClass('active', $(s).data('value') <= val));
    });

    $stars.on('mouseenter', function() {
      const hoverVal = $(this).data('value');
      $stars.each((i, s) => $(s).css('color', $(s).data('value') <= hoverVal ? '#ffc107' : '#ddd'));
    });

    $container.on('mouseleave', function() {
      $stars.each((i, s) => {
        const v = $(s).data('value');
        $(s).css('color', v <= ratings[type] ? '#ffc107' : '#ddd');
      });
    });
  });

  // Submit del formulario
  $ratingForm.on('submit', function(e) {
    e.preventDefault();
    $statusMessage.empty();

    // Validar calificaciones
    const missing = Object.keys(ratings).filter(r => ratings[r] === 0);
    if (missing.length) {
      $statusMessage.html(
        `<div class="alert alert-warning">
           <i class="fas fa-exclamation-triangle me-2"></i>
           Por favor califica todos los aspectos antes de enviar.
         </div>`
      );
      return;
    }

    // Payload
    const payload = {
      id_empresa:   $('input[name="id_empresa"]').val(),
      id_candidato: $('input[name="id_candidato"]').val(),
      puesto_desempenado:      $('#puesto').val().trim(),
      tiempo_laborado_meses:   $('#meses').val(),
      comentario:              $('#comentario').val().trim(),
      ambiente_laboral:        ratings.ambiente,
      prestaciones:            ratings.prestaciones,
      salario:                 ratings.salario
    };

    console.log('[SUBMIT] Payload:', payload);

    // AJAX POST
    fetch('/NeoWork_Refactorized/Routes/addReview', {
      method: 'POST',
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
          title: 'Reseña enviada',
          text: data.message || '',
          timer: 2000,
          showConfirmButton: false
        });
        // Opcional: resetear formulario
        $ratingForm[0].reset();
      } else {
        throw new Error(data.message || 'Error al guardar reseña');
      }
    })
    .catch(err => {
      console.error('[SUBMIT] Error:', err);
      $statusMessage.html(
        `<div class="alert alert-danger">
           <i class="fas fa-exclamation-triangle me-2"></i>
           ${err.message}
         </div>`
      );
    });
  });
});
  


  
