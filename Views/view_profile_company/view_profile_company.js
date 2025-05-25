$(document).ready(function () {
    // Cargar datos de la empresa
    $.ajax({
        url: "../../Controllers/get_company_info.php",
        method: "GET",
        success: function (data) {
            const company = JSON.parse(data);

            $("#company-name").text(company.nombre);
            $("#company-description").text(company.descripcion);

            let estrellas = "";
            for (let i = 1; i <= 5; i++) {
                if (i <= company.calificacion) {
                    estrellas += '<i class="fas fa-star"></i>';
                } else {
                    estrellas += '<i class="far fa-star"></i>';
                }
            }
            $("#company-rating").html(estrellas);
        },
        error: function () {
            $("#company-name").text("Nombre no disponible");
            $("#company-description").text("No se pudo cargar la descripción.");
        }
    });

    // Cargar reseñas
    $.ajax({
        url: "../../Controllers/get_reviews.php",
        method: "GET",
        success: function (data) {
            const reviews = JSON.parse(data);
            if (reviews.length === 0) {
                $("#reviews-container").html("<p>No hay reseñas disponibles.</p>");
                return;
            }

            let html = "";
            reviews.forEach(review => {
                html += `
                    <div class="review-card mb-4 text-start mx-auto" style="max-width: 600px;">
                        <h5 class="fw-bold">${review.titulo}</h5>
                        <p><strong>Opinión:</strong> ${review.comentario}</p>
                        <p><strong>Calificación:</strong> ${review.calificacion}/5</p>
                    </div>
                `;
            });
            $("#reviews-container").html(html);
        },
        error: function () {
            $("#reviews-container").html("<p>Error al cargar las reseñas.</p>");
        }
    });
});
