$(document).ready(function() {
    const urlParams  = new URLSearchParams(window.location.search);
    const jobId      = urlParams.get('id_puesto') || urlParams.get('id');
    const companyId  = urlParams.get('id_empresa');
    let currentJob   = null;

    const $btnReview = $('#btn-agregar-reseña');

    // Click en Agregar reseña (versión modificada)
    $btnReview.on('click', function() {
        const idPuesto = $(this).data('id-puesto');
        const idEmpresa = $(this).data('id-empresa');
        
        window.location.href = `/NeoWork_Refactorized/Views/add_review/add_review.php?id_puesto=${idPuesto}&id_empresa=${idEmpresa}`;
    });

    // Cargar detalles del puesto
    function loadJobDetails() {
        $.ajax({
            url: '/NeoWork_Refactorized/Routes/getJobs',
            type: 'GET',
            success: function(jobs) {
                const job = jobs.find(j => j.id_puesto == jobId);
                if (!job) {
                    alert('Puesto no encontrado');
                    return window.location.href = 'unregistered_user.php';
                }

                currentJob = job;

                $('#job-title').text(job.titulo || 'No especificado');
                $('#job-salary').text('$' + (job.salario?.toLocaleString('es-MX') || 'No especificado'));
                $('#job-description').text(job.descripcion || 'No especificado');
                $('#job-benefits').text(job.prestaciones || 'No especificado');

                // Habilitar botón de reseña
                $btnReview.prop('disabled', false);

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

    // ... demás funciones (loadCompanyDetails, loadApplicationsCount, loadCompanyReviews, apply)

    if (jobId && companyId) {
        loadJobDetails();
    } else {
        window.location.href = 'unregistered_user.php';
    }
});