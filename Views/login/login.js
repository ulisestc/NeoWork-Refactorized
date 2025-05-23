$(document).ready(function () {
    $('#login-form').on('submit', function (e) {
        e.preventDefault(); // Evita el envío normal del formulario

        const email = $('#email').val();
        const password = $('#password').val();
        console.log({ email, password });

        $.ajax({
            url: 'http://localhost/NeoWork_Refactorized/Routes/loginUser', // Correct route
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.success === true) {
                    console.log('Login successful');
                    // window.location.href = '../holamundo.html';
                } else {
                    console.log('Login failed');
                    // window.location.href = '../holamundo.html';
                }
            },
            error: function () {
                console.log('Error during login');
                // window.location.href = '../holamundo.html';
            }
        });
    });
});