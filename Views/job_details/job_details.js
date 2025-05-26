$(document).ready(function () {
    const userId = window.USER_ID;
    const userType = window.USER_TYPE;
    console.log('Tipo de usuario:', userType);
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

    loadJob();


    if (userType !== 'candidato') {
        $('#add-review').hide();
        $('#apply-btn').hide();
        $('#add-comment-form').hide();
        $('#apply_and_back').append(
            `<a href="../view_company/view_company.php" class="btn btn-outline-dark btn-lg">Regresar</a>`
        );
        $('#applied-label').hide();
    } else {
        $('#apply_and_back').append(
            `<a href="../view_candidato/view_candidato.php" class="btn btn-outline-dark btn-lg">Regresar</a>`
        );
    }

    // Evento de aplicar al trabajo
    $('#apply-btn').on('click', function () {
        const data = {
            id_puesto: jobId,
            id_candidato: userId
        };

        $.ajax({
            url: 'http://localhost/NeoWork_Refactorized/Routes/mandarSolicitud',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {
                console.log('Aplicación enviada con éxito:', response);
                $('#applied-label').show(); // Mostrar la etiqueta de "Aplicado"
                $('#apply-btn').prop('disabled', true); // Deshabilitar el botón
                // alert('Has aplicado con éxito al puesto.');
            },
            error: function (xhr, status, error) {
                console.error('Error al aplicar:', error);
                alert('Ocurrió un error al aplicar. Intenta nuevamente.');
            }
        });
    });

    $('#add-comment-form').on('submit', function (e) {
        e.preventDefault();

        const commentData = {
            id_puesto: jobId,
            id_candidato: userId,
            comment: $('#comment-text').val()
        };

        $.ajax({
            url: 'http://localhost/NeoWork_Refactorized/Routes/addComment',
            type: 'POST',
            data: JSON.stringify(commentData),
            contentType: 'application/json',
            success: function (response) {
                console.log('Comentario enviado con éxito:', response);
                alert('Comentario enviado con éxito.');
                $('#add-comment-form')[0].reset();
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
            success: function (response) {
                console.log('Respuesta completa del backend:', response);
                if (!response) {
                    alert('Puesto no encontrado');
                }

                $jobTitle.text(response.data.titulo || 'No especificado');
                $companyName.text(response.data.nombre_empresa || 'No especificado');
                $jobSalary.text('$' + (response.data.salario?.toLocaleString('es-MX') || 'No especificado'));
                $jobDescription.text(response.data.descripcion || 'No especificado');
                $jobBenefits.text(response.data.prestaciones || 'No especificado');
                $btnReview.prop('disabled', false);
            },
            error: function (xhr) {
                console.error('Error al cargar el puesto:', xhr);
                alert('Error al cargar los detalles del puesto');
            }
        });

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getApplications/${jobId}`,
            type: 'GET',
            success: function (response) {
                const solicitudes = Object.keys(response).filter(key => !isNaN(parseInt(key)));
                console.log('Solicitudes:', solicitudes.length);
                $jobApplicationsCount.text(solicitudes.length || '0');

                // checa si el uisuario ya envió solicitud, y desabilita el botón si es verdad
                let found = false;
                Object.values(response).forEach(item => {
                    if (typeof item === 'object' && item.id_candidato == userId && userType === 'candidato') {
                        found = true;
                    }
                });

                if (found) {
                    $('#applied-label').show();
                    $('#apply-btn').prop('disabled', true);
                }

            },
            error: function (xhr) {
                console.error('Error al cargar el número de aplicaciones:', xhr);
                $jobApplicationsCount.text('Error al cargar');
            }
        });

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getReviews/${companyId}`,
            type: 'GET',
            success: function (response) {
                console.log('Reseñas de la empresa:', response);
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
            error: function (xhr) {
                console.error('Error al cargar las reseñas de la empresa:', xhr);
                $companyReviewsContainer.html('<p>Error al cargar las reseñas.</p>');
            }
        });

        $.ajax({
            url: `http://localhost/NeoWork_Refactorized/Routes/getComments/${jobId}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log('Comentarios del puesto:', response);
                $commentsContainer.empty();
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
            error: function (xhr) {
                console.error('Error al cargar los comentarios:', xhr);
                $commentsContainer.html('<p>Error al cargar los comentarios.</p>');
            }
        });
    }

});
