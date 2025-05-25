$(document).ready(function() {
    $.ajax({
        url: "../../Controllers/get_reviews.php",
        method: "GET",
        success: function(data) {
            const reviews = JSON.parse(data);
            if (reviews.length === 0) {
                $("#reviews-container").html("<p>No hay reseñas disponibles.</p>");
                return;
            }

            let html = "";
            reviews.forEach(review => {
                html += `
                    <div class="review-card">
                        <h3>${review.titulo}</h3>
                        <p><strong>Opinión:</strong> ${review.comentario}</p>
                        <p><strong>Calificación:</strong> ${review.calificacion}/5</p>
                    </div>
                `;
            });
            $("#reviews-container").html(html);
        },
        error: function() {
            $("#reviews-container").html("<p>Error al cargar las reseñas.</p>");
        }
    });
});
