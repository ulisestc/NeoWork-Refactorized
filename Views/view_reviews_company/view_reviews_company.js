$(document).ready(function () {
    const reviews = [
        {
            nombre_usuario: "Laura Méndez",
            puesto: "Diseñadora UX",
            meses_trabajados: 18,
            ambiente: 0.8,
            prestaciones: 0.6,
            salario: 0.7,
            fecha: "2025-03-01"
        },
        {
            nombre_usuario: "Carlos Pérez",
            puesto: "Desarrollador Backend",
            meses_trabajados: 24,
            ambiente: 0.9,
            prestaciones: 0.7,
            salario: 0.85,
            fecha: "2025-01-15"
        },
        {
            nombre_usuario: "Ana Torres",
            puesto: "Gerente de Marketing",
            meses_trabajados: 36,
            ambiente: 1,
            prestaciones: 0.9,
            salario: 0.95,
            fecha: "2024-12-10"
        },
        {
            nombre_usuario: "Miguel Luna",
            puesto: "Analista de Datos",
            meses_trabajados: 10,
            ambiente: 0.5,
            prestaciones: 0.4,
            salario: 0.6,
            fecha: "2024-11-05"
        },
        {
            nombre_usuario: "Paola García",
            puesto: "Recursos Humanos",
            meses_trabajados: 6,
            ambiente: 0.7,
            prestaciones: 0.8,
            salario: 0.75,
            fecha: "2024-10-20"
        }
    ];

    function convertToStars(rating) {
        const scaled = Math.round(rating * 5);
        let stars = "";
        for (let i = 1; i <= 5; i++) {
            stars += i <= scaled
                ? '<i class="fas fa-star text-warning"></i>'
                : '<i class="far fa-star text-warning"></i>';
        }
        return stars;
    }

    let html = "";
    reviews.forEach(review => {
        const tiempoLaborado = review.meses_trabajados >= 12
            ? `${Math.floor(review.meses_trabajados / 12)} año(s) ${review.meses_trabajados % 12} mes(es)`
            : `${review.meses_trabajados} mes(es)`;

        html += `
            <div class="d-flex align-items-start border-bottom pb-3">
                <div class="me-3">
                    <i class="fas fa-user-circle fa-3x"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${review.nombre_usuario}</strong><br>
                            <small class="text-muted">${review.fecha}</small>
                        </div>
                    </div>
                    <p class="mb-1"><strong>Puesto:</strong> ${review.puesto}</p>
                    <p class="mb-1"><strong>Tiempo laborado:</strong> ${tiempoLaborado}</p>

                    <p class="mb-1"><strong>Ambiente laboral:</strong><br>${convertToStars(review.ambiente)}</p>
                    <p class="mb-1"><strong>Prestaciones:</strong><br>${convertToStars(review.prestaciones)}</p>
                    <p class="mb-1"><strong>Salario:</strong><br>${convertToStars(review.salario)}</p>
                </div>
            </div>
        `;
    });

    $("#reviews-container").html(html);
});
