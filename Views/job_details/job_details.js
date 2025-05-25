$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const jobId = urlParams.get('id');

    // Función para cargar detalles del puesto
    function loadJobDetails() {
        $.ajax({
            url: '/NeoWork_Refactorized/Routes/getJobs',
            type: 'GET',
            success: function(jobs) {
                // Filtrar el puesto específico (temporal hasta implementar /verPuesto/{id})
                const job = jobs.find(j => j.id_puesto == jobId); // Ajusta 'id_puesto' según el campo real
                
                if (!job) {
                    alert('Puesto no encontrado');
                    window.location.href = 'unregistered_user.php';
                    return;
                }

                // Mostrar datos del puesto
                $('#job-title').text(job.titulo || 'No especificado');
                $('#job-salary').text('$' + (job.salario?.toLocaleString('es-MX') || 'No especificado'));
                $('#job-description').text(job.descripcion || 'No especificado');
                $('#job-benefits').text(job.prestaciones || 'No especificado');

                // Cargar datos adicionales
                loadCompanyDetails(job.id_empresa);
                loadApplicationsCount(jobId);
                loadCompanyReviews(job.id_empresa);
            },
            error: function(xhr) {
                console.error('Error al cargar el puesto:', xhr);
                alert('Error al cargar los detalles del puesto');
            }
        });
    }

    // Cargar datos de la empresa
    function loadCompanyDetails(companyId) {
        $.ajax({
            url: `/NeoWork_Refactorized/Routes/getCompany/${companyId}`,
            type: 'GET',
            success: function(company) {
                $('#company-name').text(company.nombre || 'Empresa no disponible');
            },
            error: function(xhr) {
                console.error('Error al cargar empresa:', xhr);
                $('#company-name').text('Empresa no disponible');
            }
        });
    }

    // Contar solicitudes
    function loadApplicationsCount(jobId) {
        $.ajax({
            url: `/NeoWork_Refactorized/Routes/getApplications/${jobId}`,
            type: 'GET',
            success: function(applications) {
                $('#applications-count').text(applications.length + ' candidatos');
            },
            error: function(xhr) {
                console.error('Error al cargar aplicaciones:', xhr);
                $('#applications-count').text('No disponible');
            }
        });
    }

    // Cargar reseñas (usando endpoint existente)
    function loadCompanyReviews(companyId) {
        $.ajax({
            url: `/NeoWork_Refactorized/Routes/getReviews/${companyId}`,
            type: 'GET',
            success: function(reviews) {
                const $container = $('#reviews-container');
                $container.empty();
                
                if (!reviews || reviews.length === 0) {
                    $container.html('<p>No hay reseñas disponibles</p>');
                    return;
                }

                reviews.forEach(review => {
                    // Ajusta los campos según lo que devuelva la API
                    const stars = '★★★★★'.slice(0, review.calificacion || 0); // Ejemplo básico
                    $container.append(`
                        <div class="review-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>${review.puesto || 'Ex-empleado'}</strong>
                                <div class="rating">${stars}</div>
                            </div>
                            <p class="mb-1">${review.comentario || 'Sin comentario'}</p>
                        </div>
                    `);
                });
            },
            error: function(xhr) {
                console.error('Error al cargar reseñas:', xhr);
                $('#reviews-container').html('<p>Error al cargar reseñas</p>');
            }
        });
    }

    // Función de aplicación al puesto
    $('#apply-btn').on('click', function() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user || !user.id_candidato) {
            window.location.href = '../auth/login.php?redirect=job_details.php?id=' + jobId;
            return;
        }

        $.ajax({
            url: '/NeoWork_Refactorized/Routes/mandarSolicitud',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ 
                id_puesto: jobId,
                id_candidato: user.id_candidato
            }),
            success: function(response) {
                alert(response.success ? '¡Aplicación enviada!' : response.message);
                if (response.success) {
                    $('#apply-btn').prop('disabled', true).text('Aplicación enviada');
                    loadApplicationsCount(jobId);
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'No se pudo aplicar'));
            }
        });
    });

    // Iniciar carga
    if (jobId) loadJobDetails();
    else window.location.href = 'unregistered_user.php';
});