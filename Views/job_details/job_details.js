$(document).ready(function() {
    const userId = window.USER_ID;
    console.log('ID de usuario:', userId);
    const urlParams = new URLSearchParams(window.location.search);
    const jobId = urlParams.get('id_puesto') || urlParams.get('id');
    const companyId = urlParams.get('id_empresa');
    console.log('Job ID:', jobId);
    console.log('Company ID:', companyId);
    const $btnReview = $('#btn-agregar-reseña');
    const $jobTitle = $('#job-title');
    const $companyName = $('#company-name');
    const $jobSalary = $('#job-salary');
    const $jobDescription = $('#job-description');
    const $jobApplicationsCount = $('#applications-count');
    const $jobBenefits = $('#job-benefits');
    const $companyReviewsContainer = $('#reviews-container');
    const $commentsContainer = $('#comments-container');
    // const $applyButton = $('#apply-btnn');

    loadJob();

    $('#add-comment-form').on('submit', function (e) {
        e.preventDefault(); // Evitar el envío del formulario por defecto

        const commentData = {
            id_puesto: jobId,
            id_candidato: userId, // Asegúrate de contar con este campo oculto en el formulario
            comment: $('#comment-text').val()  // Renombrado para ajustarse a la API
        };

        $.ajax({
            url: 'http://localhost/NeoWork_Refactorized/Routes/addComment', // URL de la API
            type: 'POST',
            data: JSON.stringify(commentData), // Convertir los datos a JSON
            contentType: 'application/json', // Especificar el tipo de contenido
            success: function (response) {
                console.log('Comentario enviado con éxito:', response);

                // Mostrar un mensaje de éxito al usuario
                alert('Comentario enviado con éxito.');

                // Limpiar el formulario
                $('#add-comment-form')[0].reset();

                // Recargar los comentarios (opcional)
                loadJob();
            },
            error: function (xhr, status, error) {
                console.error('Error al enviar el comentario:', error);
                alert('Error al enviar el comentario. Intenta nuevamente.');
            }
        });
    });

    function loadJob() {
        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getJob/${jobId}`,
            type: 'GET',
            success: function(response) {
                console.log('Respuesta completa del backend:', response);
                if (!response) {
                    alert('Puesto no encontrado');
                }

                $jobTitle.text(response.data.titulo || 'No especificado');
                $companyName.text(response.data.nombre_empresa || 'No especificado');
                $jobSalary.text('$' + (response.data.salario?.toLocaleString('es-MX') || 'No especificado'));
                $jobDescription.text(response.data.descripcion || 'No especificado');
                $jobBenefits.text(response.data.prestaciones || 'No especificado');

                // Habilitar botón de reseña
                $btnReview.prop('disabled', false);

                // loadCompanyDetails(job.id_empresa);
                // loadApplicationsCount(jobId);
                // loadCompanyReviews(job.id_empresa);
            },
            error: function(xhr) {
                console.error('Error al cargar el puesto:', xhr);
                alert('Error al cargar los detalles del puesto');
            }
        });

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getApplications/${jobId}`,
            type: 'GET',
            success: function(response) {           
                // Filtra las claves numéricas (índices) del objeto
                const solicitudes = Object.keys(response).filter(key => !isNaN(parseInt(key)));
                console.log('Solicitudes:', solicitudes.length);
                $jobApplicationsCount.text(solicitudes.length || '0');
            },
            error: function(xhr) {
                console.error('Error al cargar el número de aplicaciones:', xhr);
                $jobApplicationsCount.text('Error al cargar');
            }
        });

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getReviews/${companyId}`,
            type: 'GET',
            success: function(response) {
                console.log('Reseñas de la empresa:', response);

                // Filtramos solo las claves numéricas (que contienen las reseñas)
                const reseñas = Object.keys(response)
                    .filter(key => !isNaN(parseInt(key)))
                    .map(key => response[key]);

                if (reseñas.length > 0) {
                    $companyReviewsContainer.empty();
                    reseñas.forEach(review => {
                        $companyReviewsContainer.append(`
                            <div class="review border rounded p-3 mb-2">
                                <h5>${review.puesto_desempenado || 'Sin título'}</h5>
                                <p>${review.comentario || 'Sin comentario'}</p>
                                <small>
                                    Calificación (ambiente): ${review.ambiente_laboral || 'N/A'} |
                                    Prestaciones: ${review.prestaciones || 'N/A'} |
                                    Salario: ${review.salario || 'N/A'} |
                                    Tiempo: ${review.tiempo_laborado_meses || 'N/A'} meses
                                </small><br>
                                <small class="text-muted">Fecha: ${new Date(review.fecha).toLocaleDateString()}</small>
                            </div>
                        `);
                    });
                } else {
                    $companyReviewsContainer.html('<p>No hay reseñas disponibles.</p>');
                }
            },
            error: function(xhr) {
                console.error('Error al cargar las reseñas de la empresa:', xhr);
                $companyReviewsContainer.html('<p>Error al cargar las reseñas.</p>');
            }
        });

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getComments/${jobId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Comentarios del puesto:', response);
                $commentsContainer.empty();

                // Extraer solo los comentarios (claves numéricas)
                const comentarios = Object.keys(response)
                    .filter(key => !isNaN(parseInt(key)))
                    .map(key => response[key]);

                console.log('Comentarios filtrados:', comentarios);
                
                if (comentarios.length > 0) {
                    comentarios.forEach(comment => {
                        const nombreCompleto = `${comment.nombre || ''} ${comment.apellidos || ''}`.trim();
                        $commentsContainer.append(`
                            <div class="comment border rounded p-3 mb-2">
                                <p>${comment.comentario || 'Sin comentario'}</p>
                                <small class="text-muted">Por: ${nombreCompleto || 'Anónimo'} | Fecha: ${new Date(comment.fecha).toLocaleDateString()}</small>
                            </div>
                        `);
                    });
                } else {
                    $commentsContainer.html('<p>No hay comentarios disponibles.</p>');
                }
            },
            error: function(xhr) {
                console.error('Error al cargar los comentarios:', xhr);
                $commentsContainer.html('<p>Error al cargar los comentarios.</p>');
            }
        });

    }



});