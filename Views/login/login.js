// Asegúrate de tener esta línea en el <head> de tu HTML:
// <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

$(document).ready(function () {
    $('#login-form').on('submit', function (e) {
        e.preventDefault();
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const payload = { email, password };
        
        console.log("=== INICIO DE SESIÓN ===");
        console.log("Datos enviados:", JSON.stringify(payload, null, 2));

        // Función para registrar respuestas
        const logResponse = (source, rawResponse) => {
            console.log(`=== RESPUESTA ${source} ===`);
            console.log("Respuesta RAW:", rawResponse);
            console.log("Tipo de respuesta:", typeof rawResponse);
            
            // Forzar parsing del JSON
            let response;
            if (typeof rawResponse === 'string') {
                try {
                    response = JSON.parse(rawResponse);
                    console.log("JSON parseado exitosamente");
                } catch (e) {
                    console.error("Error parseando JSON:", e);
                    response = rawResponse;
                }
            } else {
                response = rawResponse;
                console.log("✅ Respuesta ya es objeto");
            }
            
            console.log("Estado:", response.success ? "Éxito" : "Fallo");
            console.log("Mensaje:", response.message);
            console.log("Tipo de success:", typeof response.success);
            console.log("Valor exacto success:", response.success);
            console.log("----------------------");
            return response;
        };

        // Función para mostrar errores
        function showError(message) {
            // Limpiar el formulario también en caso de error
            $('#password').val(''); // Solo limpiar contraseña por seguridad
            
            alert('Error: ' + message); // Reemplaza esto con Swal cuando lo importes
            /*
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
            */
        }

        // Función para intentar login como empresa
        function tryCompanyLogin() {
            console.log("Intentando login como Empresa...");
            
            $.ajax({
                url: 'http://localhost/NeoWork_Refactorized/Routes/loginCompany',
                type: 'POST',
                dataType: 'json',
                data: payload,
                success: function(companyResponse) {
                    const company = logResponse('EMPRESA', companyResponse);
                    
                    // Verificación más robusta
                    if (company.success === true || company.success === "true") {
                        console.log("🎉 LOGIN EMPRESA EXITOSO - Redirigiendo...");
                        // Redirige a dashboard empresa
                        window.location.href = '/NeoWork_Refactorized/Views/view_company/view_company.php';
                    } else {
                        console.log("❌ LOGIN EMPRESA FALLÓ");
                        showError('Credenciales incorrectas para ambos tipos de cuenta');
                    }
                },
                error: function(xhr) {
                    console.error("Error en loginCompany:", xhr.responseText);
                    showError('Error al contactar al servidor de empresas');
                }
            });
        }

        // 1. Primero intenta como Candidato
        $.ajax({
            url: 'http://localhost/NeoWork_Refactorized/Routes/loginUser',
            type: 'POST',
            dataType: 'json',
            data: payload,
            success: function(userResponse) {
                const response = logResponse('CANDIDATO', userResponse);
                
                // Verificación más robusta
                if (response.success === true || response.success === "true") {
                    console.log("🎉 LOGIN CANDIDATO EXITOSO - Redirigiendo...");
                    // Redirige a dashboard candidato
                    window.location.href = '/NeoWork_Refactorized/Views/view_candidato/view_candidato.php';
                } else {
                    console.log("LOGIN CANDIDATO FALLÓ - Intentando empresa...");
                    // Si falla candidato, intenta empresa (sin importar el mensaje exacto)
                    tryCompanyLogin();
                }
            },
            error: function(xhr) {
                console.error("Error en loginUser:", xhr.responseText);
                // Si hay error de conexión con candidatos, intenta empresas
                tryCompanyLogin();
            }
        });
    });
});