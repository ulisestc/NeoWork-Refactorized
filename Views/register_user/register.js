document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const statusMessage = document.getElementById('status-message');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Limpiar mensajes anteriores
            statusMessage.innerHTML = '';
            
            // Recoger datos del formulario
            const formData = new FormData(registerForm);
            
            // Validar que los campos requeridos no estén vacíos
            const nombre = formData.get('nombre')?.trim();
            const apellidos = formData.get('apellidos')?.trim();
            const email = formData.get('email')?.trim();
            const password = formData.get('password')?.trim();
            const edad = formData.get('edad');
            const sexo = formData.get('sexo');
            
            // Validación básica
            if (!nombre || !apellidos || !email || !password) {
                statusMessage.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Por favor, complete todos los campos obligatorios.
                    </div>
                `;
                return;
            }
            
            const userData = {
                nombre: nombre,
                apellidos: apellidos,
                edad: edad || null,
                sexo: sexo || null,
                email: email,
                contraseña: password
            };
            
            // Debug: mostrar datos que se enviarán
            console.log('Datos a enviar:', userData);
            
            // Mostrar loader
            statusMessage.innerHTML = '<div class="alert alert-info">Procesando registro...</div>';
            
            // Realizar petición AJAX a la API
            fetch('http://localhost/NeoWork_Refactorized/Routes/registerUser', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(userData)
            })
            .then(response => {
                console.log('Status HTTP:', response.status, response.statusText);
                console.log('Content-Type header:', response.headers.get('Content-Type'));
                return response.text();
            })
            .then(text => {
                console.log('--- Respuesta CRUDA del servidor: ---');
                console.log(text);

                // Intentar parsear como JSON
                try {
                    const data = JSON.parse(text);
                    console.log('JSON parseado OK:', data);

                    if (data.status === 'success') {
                        statusMessage.innerHTML = `
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                ¡Registro exitoso! Serás redirigido en breve.
                            </div>
                        `;
                        registerForm.reset();
                        setTimeout(() => {
                            window.location.href = '/NeoWork_Refactorized/Views/login/login.php';
                        }, 2000);
                    } else {
                        let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                errorHtml += `<li>${data.errors[key]}</li>`;
                            });
                        } else {
                            errorHtml += `<li>${data.message || 'Error desconocido al procesar la solicitud.'}</li>`;
                        }
                        errorHtml += '</ul></div>';
                        statusMessage.innerHTML = errorHtml;
                    }
                } catch (err) {
                    console.error('ERROR al parsear JSON:', err);
                    statusMessage.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error inesperado: respuesta inválida del servidor.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('💥 Fetch falló:', error);
                statusMessage.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${error.message || 'Error de conexión: No se pudo conectar al servidor.'}
                    </div>
                `;
            });
        });
    }
});