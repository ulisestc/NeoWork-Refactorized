$(document).ready(function () {
    $('#login-form').on('submit', function (e) {
        e.preventDefault(); // Evita el envío normal del formulario

        const email = $('#email').val();
        const password = $('#password').val();
        console.log({ email, password });

        $.ajax({
            url: '../../Routes/Api.php', // Endpoint del servidor
            type: 'POST',
            data: {
                action: 'login', // Acción a realizar
                email: email,
                password: password
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    // Redirige a success.html si el login es exitoso
                    window.location.href = '../holamundo.html';
                } else {
                    // Redirige a fail.html si hay un error
                    window.location.href = '../holamundo.html';
                }
            },
            error: function () {
                // Redirige a fail.html en caso de error en la solicitud
                window.location.href = '../holamundo.html';
            }
        });
    });
});