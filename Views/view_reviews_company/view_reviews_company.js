// view_reviews_company.js
$(document).ready(function () {
  $.ajax({
    url: "../../Controllers/get_reviews.php",
    method: "GET",
    dataType: "json",
    success: function (data) {
      const container = $("#reviews-container");
      data.forEach(review => {
        const stars = "★".repeat(review.rating) + "☆".repeat(5 - review.rating);
        const html = `
          <div class="review">
            <img src="../../Assets/icons/user.svg" alt="User icon">
            <div class="review-content">
              <div class="review-name">${review.name}</div>
              <div class="stars">${stars}</div>
              <div class="review-text">${review.text}</div>
            </div>
          </div>`;
        container.append(html);
      });
    },
    error: function () {
      $("#reviews-container").html("<p>No se pudieron cargar las reseñas.</p>");
    }
  });
});
