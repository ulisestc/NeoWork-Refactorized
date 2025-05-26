$(document).ready(function() {
    const $searchInput = $('.input-group input');
    const $searchButton = $('.input-group button');
    const $filters = $('.filters-container select');
    const $jobsContainer = $('#jobs-container');
    const $userContainer = $('#header-buttons');
    const $addJobBtn = $('#add-job-btn');

    // Cargar datos al iniciar
    loadUser();
    loadJobs();

    // Event listeners
    $searchInput.on('input', loadJobs);
    $searchButton.on('click', loadJobs);
    $filters.on('change', loadJobs);
    $addJobBtn.on('click', function() {
        window.location.href = '../post_job/post_job.php';
    });

    function loadUser() {
    $.ajax({
        url: `http://localhost/NeoWork_Refactorized/Routes/getCompany/${window.USER_ID}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta completa del backend:', response);

            // Validar que success está presente y es true
            if (response && response.success === true) {
                // Directly access the user object using the key "0"
                const user = response[0];

                if (user && user.nombre_empresa) {
                    console.log('Usuario recibido:', user);
                    renderUser(user.nombre_empresa);
                } else {
                    console.error('No se encontró nombre en el objeto de usuario:', user);
                }
            } else {
                console.warn('La respuesta no fue exitosa o success es falso.');
                $userContainer.html(`<span class="text-danger">No se pudo cargar el usuario.</span>`);
            }
        },
        error: function(xhr, status, error) {
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

    function renderUser(name) {
        $userContainer.html(`
            <a id="user" href="../view_profile_company/view_profile_company.php" class="btn btn-outline-dark me-2">${name}</a>
            <a id="logout" href="../login/login.php" class="btn btn-dark">Logout</a>
        `);
    }

    function renderJobs(jobs) {
        if (!jobs || jobs.length === 0) {
            $jobsContainer.html(`
                <div class="alert alert-info">
                    No hay empleos que coincidan con tu búsqueda.
                </div>
            `);
            return;
        }
    
        const jobsHtml = jobs.map(job => `
            <div class="card mb-3 job-card" data-job-id="${job.id_puesto}">
                <div class="card-body">
                    <h5 class="card-title">${job.titulo || 'Título no disponible'}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">${job.nombre_empresa || 'Empresa no disponible'}</h6>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt"></i> ${job.direccion || 'Ubicación no disponible'} · 
                        <i class="fas fa-money-bill-wave"></i> ${job.salario || 'Salario no disponible'}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Publicado ${job.fecha_publicacion ? formatDate(job.fecha_publicacion) : 'Fecha no disponible'}</small>
                        <div class="btn-group">
                            <a href="../job_details/job_details.php?id=${job.id_puesto}&id_empresa=${job.id_empresa}" class="btn btn-sm btn-outline-dark">Ver detalles</a>
                            ${window.USER_TYPE === 'company' ? `
                            <button class="btn btn-sm btn-outline-primary edit-job" data-job-id="${job.id_puesto}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-job" data-job-id="${job.id_puesto}">
                                <i class="fas fa-trash"></i>
                            </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    
        $jobsContainer.html(jobsHtml);

        // Agregar event listeners para los botones de empresa
        if (window.USER_TYPE === 'company') {
            $('.edit-job').on('click', function(e) {
                e.preventDefault();
                const jobId = $(this).data('job-id');
                editJob(jobId);
            });
            
            $('.delete-job').on('click', function(e) {
                e.preventDefault();
                const jobId = $(this).data('job-id');
                deleteJob(jobId);
            });
        }
    }

    function editJob(jobId) {
        if (confirm('¿Editar esta vacante?')) {
            window.location.href = `../post_job/post_job.php?edit=${jobId}`;
        }
    }

    function deleteJob(jobId) {
        if (confirm('¿Eliminar permanentemente esta vacante?')) {
            $.ajax({
                url: `http://localhost/NeoWork_Refactorized/Routes/deleteJob/${jobId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        alert('Vacante eliminada correctamente');
                        loadJobs(); // Recargar la lista
                    } else {
                        alert('Error al eliminar la vacante');
                    }
                },
                error: function(error) {
                    console.error('Error al eliminar vacante:', error);
                    alert('Error al eliminar la vacante');
                }
            });
        }
    }

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
            url: `http://localhost/NeoWork_Refactorized/Routes/getJobs`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                if (response.success) {
                    // GUARDAMOS lista de emplkeos
                    const jobs = response.data;
                    // Seleccionamos los filtros
                    const $areaSelect = $('.filters-container select:nth-child(1)');
                    const $locationSelect = $('.filters-container select:nth-child(2)');
                    const $salarySelect = $('.filters-container select:nth-child(3)');
                    //recordamos valor seleccionado de cada filtro 
                    const areaValue = $areaSelect.val();
                    const locationValue = $locationSelect.val();
                    const salaryValue = $salarySelect.val();
                    // Limpiamos los selectores para evitar duplicados (bug lista infinita)
                    $areaSelect.html('<option value="">Todas las áreas</option>');
                    $locationSelect.html('<option value="">Todas las ubicaciones</option>');
                    $salarySelect.html('<option value="">Todos los salarios</option>');
                    // Obtenemos los valores únicos de cada filtro
                    const areas = new Set();
                    const locations = new Set();
                    const salaries = new Set();

                    jobs.forEach(jobs => {
                        if (jobs.area) areas.add(jobs.area);
                        if (jobs.direccion) locations.add(jobs.direccion);
                        if (jobs.salario) salaries.add(jobs.salario);
                    });
                    // Metemos los valores únicos en los selectores
                    areas.forEach(area => $areaSelect.append(`<option value="${area}">${area}</option>`));
                    locations.forEach(location => $locationSelect.append(`<option value="${location}">${location}</option>`));
                    salaries.forEach(salary => $salarySelect.append(`<option value="${salary}">+$${parseInt(salary).toLocaleString()}</option>`));

                    // Seleccionamos el valor que eligió el user
                    $areaSelect.val(areaValue);
                    $locationSelect.val(locationValue);
                    $salarySelect.val(salaryValue);
                    // Filtramos los empleos según los filtros seleccionados
                    if(filtersData.search!== '' || filtersData.area !== null || filtersData.location !== null || filtersData.salary !== null) {
                    response.data = response.data.filter(job => {
                        
                        const search = filtersData.search?.toLowerCase() || "";
                        const jobText = Object.values(job).join(" ").toLowerCase();


                        return (!filtersData.search || jobText.includes(search)) &&
                                (!filtersData.area || job.area === filtersData.area) &&
                                (!filtersData.location || job.direccion === filtersData.location) &&
                                (!filtersData.salary || job.salario === filtersData.salary);
                    });
                }
                renderJobs(response.data);
                } else {
                    console.error('Error: La respuesta del servidor no contiene un array de empleos:', response);
                    $jobsContainer.html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar empleos: Respuesta inesperada del servidor.
                        </div>
                    `);
                }
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


    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-MX', options);
    }
});