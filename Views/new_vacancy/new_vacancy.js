$(document).ready(function() {
    const $jobForm = $('#job-form');
    const $statusMessage = $('#status-message');

    if ($jobForm.length) {
        $jobForm.on('submit', function(e) {
            e.preventDefault();
            
            // Limpiar mensajes anteriores
            $statusMessage.html('');
            
            // Validación básica
            const salary = $('#salario').val();
            if (!salary.includes('$')) {
                $statusMessage.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Formato de salario incorrecto. Ejemplo: $15,000 - $20,000
                    </div>
                `);
                return;
            }

            // Recoger datos del formulario
            const formData = {
                nombre_vacante: $('#nombre_vacante').val(),
                salario: salary,
                area: $('#area').val(),
                prestaciones: $('#prestaciones').val(),
                requerimientos: $('#requerimientos').val()
            };

            // Debug: mostrar datos en consola
            console.log('Datos a enviar:', formData);

            // Mostrar loader
            $statusMessage.html('<div class="alert alert-info">Publicando vacante...</div>');

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
                            Object.keys(data.errors).forEach(key => {
                                errorHtml += `<li>${data.errors[key]}</li>`;
                            });
                        } else {
                            errorHtml += `<li>${data.message || 'Error al publicar la vacante.'}</li>`;
                        }
                        errorHtml += '</ul></div>';
                        $statusMessage.html(errorHtml);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
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