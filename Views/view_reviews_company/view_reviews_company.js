$(document).ready(function() {
    // Obtener ID de empresa
    const urlParams = new URLSearchParams(window.location.search);
    const companyId = urlParams.get('id') || window.USER_ID;

    console.log('ID de empresa:', companyId); // Debug

    if (!companyId) {
        showError('No se pudo identificar la empresa');
        return;
    }

    loadReviews(companyId);

    function loadReviews(companyId) {
        showLoading();

        $.ajax({
            url: `http://localhost:8080/NeoWork_Refactorized/Routes/getReviews/${companyId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta completa:', response); // Debug

                const reseñas = Object.keys(response)
                    .filter(key => !isNaN(parseInt(key)))
                    .map(key => response[key]);

                if (reseñas.length > 0) {
                    renderReviews(reseñas);
                } else {
                    showMessage('No hay reseñas disponibles para esta empresa');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                showError('Error al cargar reseñas. Intente nuevamente más tarde.');
            }
        });
    }

    function renderReviews(reviews) {
        let html = '';
        
        reviews.forEach(review => {
            // Asegura que todos los campos tengan valores por defecto
            const tiempoLaborado = formatWorkTime(review.tiempo_laborado_meses || 0);
            const nombreUsuario = review.nombre_usuario || 'Usuario anónimo';
            const puesto = review.puesto_desempenado || 'No especificado';
            const comentario = review.comentario || '';

            html += `
                <div class="review-card mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-1">${escapeHtml(nombreUsuario)}</h5>
                            <p class="mb-1 text-muted">${escapeHtml(puesto)}</p>
                        </div>
                        <small class="text-muted">${formatDate(review.fecha)}</small>
                    </div>
                    
                    <p class="mb-2"><strong>Tiempo laborado:</strong> ${tiempoLaborado}</p>
                    
                    ${comentario ? `<div class="mb-3 p-2 bg-light rounded"><p>${escapeHtml(comentario)}</p></div>` : ''}
                    
                    <div class="rating-section">
                        <div class="d-flex align-items-center mb-1">
                            <span class="me-2"><strong>Ambiente laboral:</strong></span>
                            ${convertToStars(review.ambiente_laboral)}
                            <span class="ms-2">(${(review.ambiente_laboral * 5).toFixed(1)}/5)</span>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="me-2"><strong>Prestaciones:</strong></span>
                            ${convertToStars(review.prestaciones)}
                            <span class="ms-2">(${(review.prestaciones * 5).toFixed(1)}/5)</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="me-2"><strong>Salario:</strong></span>
                            ${convertToStars(review.salario)}
                            <span class="ms-2">(${(review.salario * 5).toFixed(1)}/5)</span>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#reviews-container').html(html);
    }

    // Funciones auxiliares
    function formatWorkTime(months) {
        if (months >= 12) {
            return `${Math.floor(months / 12)} año(s) ${months % 12} mes(es)`;
        }
        return `${months} mes(es)`;
    }

    function convertToStars(rating) {
        const scaled = Math.round((rating || 0) * 5);
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= scaled ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
        }
        return stars;
    }

    function formatDate(dateString) {
        if (!dateString) return 'Fecha no disponible';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-MX', options);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function showLoading() {
        $('#reviews-container').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando reseñas...</p>
            </div>
        `);
    }

    function showMessage(message) {
        $('#reviews-container').html(`
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                ${message}
            </div>
        `);
    }

    function showError(message) {
        $('#reviews-container').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `);
    }
});