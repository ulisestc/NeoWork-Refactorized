$(document).ready(function() {
    const $searchInput = $('.input-group input');
    const $searchButton = $('.input-group button');
    const $filters = $('.filters-container select');
    const $jobsContainer = $('.col-md-8');

    // Cargar empleos al iniciar
    loadJobs();

    // Event listeners
    $searchButton.on('click', loadJobs);
    $filters.on('change', loadJobs);

    function loadJobs() {
        const filtersData = {
            search: $searchInput.val(),
            area: $('.filters-container select:nth-child(1)').val(),
            location: $('.filters-container select:nth-child(2)').val(),
            salary: $('.filters-container select:nth-child(3)').val()
        };

        // Debug: mostrar filtros en consola
        console.log('Filtros aplicados:', filtersData);

        // Mostrar loader
        $jobsContainer.html('<div class="alert alert-info">Buscando empleos...</div>');

        // Llamada AJAX al endpoint del API
        $.ajax({
            // url: `/NeoWork_Refactorized/Routes/getJobs?${$.param(filtersData)}`,
            url: `http://localhost/NeoWork_Refactorized/Routes/getJobs`,
            type: 'GET',
            success: function(jobs) {
                console.log('Empleos recibidos:', jobs);
                renderJobs(jobs);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $jobsContainer.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar empleos: ${xhr.responseJSON?.message || 'Intenta nuevamente más tarde.'}
                    </div>
                `);
            }
        });
    }

    function renderJobs(jobs) {
        if (!jobs || jobs.length === 0) {
            $jobsContainer.html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay empleos que coincidan con tu búsqueda.
                </div>
            `);
            return;
        }

        const jobsHtml = jobs.map(job => `
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">${job.titulo || 'Título no disponible'}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">${job.empresa || 'Empresa no disponible'}</h6>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt"></i> ${job.ubicacion || 'Ubicación no disponible'} · 
                        <i class="fas fa-money-bill-wave"></i> ${job.salario || 'Salario no disponible'}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Publicado ${job.fecha_publicacion ? formatDate(job.fecha_publicacion) : 'Fecha no disponible'}</small>
                        <a href="/NeoWork_Refactorized/Routes/verPuesto${job.id || ''}" class="btn btn-sm btn-outline-dark">Ver detalles</a>
                    </div>
                </div>
            </div>
        `).join('');

        $jobsContainer.html(jobsHtml);
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-MX', options);
    }
});