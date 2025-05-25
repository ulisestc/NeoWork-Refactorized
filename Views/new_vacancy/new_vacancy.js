$(document).ready(function() {
    const $jobForm = $('#job-form');
    const $statusMessage = $('#status-message');

    if ($jobForm.length) {
        $jobForm.on('submit', function(e) {
            e.preventDefault();
            // Limpiar mensajes anteriores
            $statusMessage.html('');

            const salary = $('#salario').val().trim();
            const decimalPattern = /^\d+(?:\.\d+)?$/;
            if (!decimalPattern.test(salary)) {
                $statusMessage.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Formato de salario incorrecto. Debe ser un número decimal, por ejemplo: 20.0 o 15000.50
                    </div>
                `);
                return;
            }

            // Recoger datos del formulario
            const formData = {
                id_empresa: $('#empresa-info').data('id-empresa'),
                nombre_vacante: $('#nombre_vacante').val(),
                salario: salary,
                prestaciones: $('#prestaciones').val(),
                requerimientos: $('#requerimientos').val()
            };

            // Debug: mostrar datos en consola
            console.log('Datos a enviar:', formData);

            // Mostrar loader
            $statusMessage.html(`
                <div class="alert alert-info">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    Publicando vacante...
                </div>
            `);

            // Llamada AJAX al endpoint del API
            $.ajax({
                url: '/NeoWork_Refactorized/Routes/agregarVacante',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(data) {
                    console.log('Respuesta del servidor:', data);

                    if (data.success) {
                        $statusMessage.html(`
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Vacante publicada exitosamente. ID: ${data.id || ''}
                            </div>
                        `);
                        $jobForm[0].reset();
                    } else {
                        let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                        if (data.errors) {
                            Object.values(data.errors).forEach(msg => {
                                errorHtml += `<li>${msg}</li>`;
                            });
                        } else {
                            errorHtml += `<li>${data.message || 'Error al publicar la vacante.'}</li>`;
                        }
                        errorHtml += '</ul></div>';
                        $statusMessage.html(errorHtml);
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    $statusMessage.html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error de conexión: ${xhr.responseJSON?.message || 'No se pudo conectar al servidor.'}
                        </div>
                    `);
                }
            });
        });
    }
});
