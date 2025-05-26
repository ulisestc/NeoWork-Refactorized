$(document).ready(function () {
  const ratings = { ambiente: 0, prestaciones: 0, salario: 0 };
  const $empresaInfo = $('#empresa-info');

  // Inicializar sistema de estrellas
  $('.stars').each(function () {
    const $container = $(this);
    const type = $container.data('rating');
    const $stars = $container.find('.star');

    // Asegurarse de que todas las estrellas comienzan sin selección
    $stars.removeClass('active hover');

    // CLICK: fija rating y marca 'active'
    $stars.on('click', function () {
      const val = parseInt($(this).data('value'));
      ratings[type] = val;

      // Marcar estrellas <= val como active
      $stars.removeClass('active hover').each(function () {
        const sv = parseInt($(this).data('value'));
        $(this).toggleClass('active', sv <= val);
      });
    });

    // HOVER: marca 'hover' sin tocar 'active'
    $stars.on('mouseenter', function () {
      const hv = parseInt($(this).data('value'));
      $stars.each(function () {
        const sv = parseInt($(this).data('value'));
        $(this).toggleClass('hover', sv <= hv && !$(this).hasClass('active'));
      });
    }).on('mouseleave', function () {
      // Al salir, quitamos solo la clase hover
      $stars.removeClass('hover');
    });
  });

  // Resto del código permanece igual...
  // Validación en tiempo real de inputs y textarea
  $('#ratingForm').on('input change', 'input, textarea', function () {
    if ($(this).val().trim()) {
      $(this).removeClass('is-invalid');
    }
  });

  // Envío del formulario
  $('#ratingForm').on('submit', function (e) {
    e.preventDefault();
    let valid = true;

    // Validar campos requeridos
    $('#ratingForm [required]').each(function () {
      if (!$(this).val().trim()) {
        $(this).addClass('is-invalid');
        valid = false;
      }
    });

    // Validar que todas las categorías estén calificadas
    if (valid && Object.values(ratings).some(r => r === 0)) {
      alert('Por favor, califica todos los aspectos antes de enviar.');
      valid = false;
    }

    if (!valid) return;

    // Construir payload
    const payload = {
      id_puesto: $empresaInfo.data('id-puesto'),
      id_empresa: $empresaInfo.data('id-empresa'),
      id_candidato: $empresaInfo.data('id-candidato'),
      puesto_desempenado: $('#puesto').val().trim(),
      tiempo_laborado_meses: $('#meses').val(),
      comentario: $('#comentario').val().trim(),
      ambiente_laboral: ratings.ambiente,
      prestaciones: ratings.prestaciones,
      salario: ratings.salario
    };

    console.log('[ADD_REVIEW] Enviando:', payload);

    $.ajax({
      url: '/NeoWork_Refactorized/Routes/addReview',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(payload),
      success: function (res) {
        console.log('[ADD_REVIEW] Respuesta:', res);
        if (res.success) {
          alert('¡Reseña guardada exitosamente!');
          // Redirige a detalles del puesto
          window.location.href = `/NeoWork_Refactorized/Views/job_details/job_details.php?id_puesto=${payload.id_puesto}&id_empresa=${payload.id_empresa}`;
        } else {
          alert('Error: ' + (res.message || 'No se pudo guardar la reseña'));
        }
      },
      error: function (xhr) {
        console.error('[ADD_REVIEW] Error AJAX:', xhr);
        const msg = xhr.responseJSON?.message || 'Error de conexión con el servidor';
        alert('Error: ' + msg);
      }
    });
  });
});