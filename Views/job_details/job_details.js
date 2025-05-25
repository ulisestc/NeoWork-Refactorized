$(document).ready(function() {
    // Manejar clic en "Aplicar al puesto"
    $('#apply-btn').on('click', function() {
        // Verificar si el usuario está autenticado (simulado)
        const isAuthenticated = localStorage.getItem('token') !== null;

        if (!isAuthenticated) {
            window.location.href = '../auth/login.php?redirect=job_details';
            return;
        }

        // Llamada AJAX para aplicar (endpoint ficticio)
        $.ajax({
            url: '/NeoWork_Refactorized/Routes/mandarSolicitud',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ puesto_id: 123 }), // ID hardcodeado (se obtendrá dinámicamente)
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token'));
            },
            success: function(response) {
                alert('¡Aplicación enviada con éxito!');
                $('#apply-btn').prop('disabled', true).text('Aplicación enviada');
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'No se pudo aplicar'));
            }
        });
    });
});