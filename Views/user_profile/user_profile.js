$(document).ready(function() {
    console.log('ID de usuario:', window.USER_ID);
    $Name = $('#Nombre_Completo');
    $Email = $('#correo');
    $Age = $('#edad');
    $Date = $('#fecha_registro');

    loadUser();
    // renderUser(window.USER_NAME);

    function loadUser() {
        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getUser/${window.USER_ID}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta completa del backend:', response);

                // Validar que success está presente y es true
                if (response && response.success === true) {
                    // Directly access the user object using the key "0"
                    const user = response[0];

                    if (user) {
                        console.log('Usuario recibido:', user);
                        renderUser(user);
                    } else {
                        console.error('No se encontró nombre en el objeto de usuario:', user);
                    }
                } else {
                    console.warn('La respuesta no fue exitosa o success es falso.');
                    $userContainer.html(`<span class="text-danger">No se pudo cargar el usuario.</span>`);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', error, xhr.responseJSON);
                $userContainer.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar usuario: ${xhr.responseJSON?.message || 'Intenta nuevamente más tarde.'}
                    </div>
                `);
            }
        });
    }

    function renderUser(user) {
        console.log('Nombre de usuario:', user.nombre + ' ' + user.apellidos);
        $Name.html(`${user.nombre} ${user.apellidos}`);
        $Email.html(`${user.correo}`);
        $Age.html(`${user.edad}`);
        // FECHA TO DD/MM/YYYY
        const fecha = new Date(user.fecha_registro);
        const fechaLegible = fecha.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
        $Date.html("Registrado el " + fechaLegible);
        $('#logout').attr('href', '../login/login.php');
    }
});