$(document).ready(function () {
    const $userContainer = $('#header-buttons');
    const $userFullname = $('#user-fullname');

    // Cargar información del usuario
    loadUser();

    function loadUser() {
        if (!window.USER_ID) {
            window.location.href = '../login/login.php';
            return;
        }

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getUser/${window.USER_ID}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log('Respuesta completa del backend:', response);

                // Validar que success está presente y es true
                if (response && response.success === true) {
                    // Acceder al primer elemento del array de respuesta
                    const user = response[0];

                    if (user) {
                        console.log('Usuario recibido:', user);

                        // Mostrar nombre completo en el header
                        renderUserHeader(user.nombre, user.apellidos);

                        // Mostrar nombre completo en el perfil
                        if (user.nombre && user.apellidos) {
                            $userFullname.text(`${user.nombre} ${user.apellidos}`);
                        } else if (user.nombre) {
                            $userFullname.text(user.nombre);
                        } else {
                            $userFullname.text('Usuario');
                        }
                    } else {
                        console.error('No se encontraron datos de usuario:', user);
                        $userFullname.text('Usuario');
                    }
                } else {
                    console.warn('La respuesta no fue exitosa o success es falso.');
                    $userContainer.html(`<span class="text-danger">No se pudo cargar el usuario.</span>`);
                }
            },
            error: function (xhr, status, error) {
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

    /*function renderUserHeader(name) {
        $userContainer.html(`
            <span class="me-2 btn btn-outline-dark">${name || 'Usuario'}</span>
            <a id="logout" href="../login/login.php" class="btn btn-dark">Cerrar sesión</a>
        `);
    }*/
    function renderUserHeader(name) {
        const profileLink = window.USER_ID
            ? `../user_profile/user_profile.php`
            : `../login/login.php`;

        $userContainer.html(`
        <a href="${profileLink}" class="me-2 btn btn-outline-dark">
            <i class=""></i> ${name}
        </a>
        ${window.USER_ID ? `
            <a id="logout" href="../login/logout.php" class="btn btn-dark">
                <i class=""></i> Logout
            </a>
        ` : ''}
    `);
    }
});