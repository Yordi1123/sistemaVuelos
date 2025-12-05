/**
 * JavaScript para Búsqueda de Vuelos
 * Sistema de Reserva de Vuelos
 */

document.addEventListener('DOMContentLoaded', function () {

    // Toggle filtros avanzados
    const toggleFiltersBtn = document.getElementById('toggleFilters');
    const advancedFilters = document.getElementById('advancedFilters');

    if (toggleFiltersBtn && advancedFilters) {
        toggleFiltersBtn.addEventListener('click', function () {
            if (advancedFilters.style.display === 'none') {
                advancedFilters.style.display = 'block';
                this.innerHTML = '<i class="fas fa-sliders-h"></i> Ocultar Filtros';
            } else {
                advancedFilters.style.display = 'none';
                this.innerHTML = '<i class="fas fa-sliders-h"></i> Filtros Avanzados';
            }
        });
    }

    // Intercambiar origen y destino
    const swapBtn = document.getElementById('swapAirports');
    const originSelect = document.getElementById('origin');
    const destinationSelect = document.getElementById('destination');

    if (swapBtn && originSelect && destinationSelect) {
        swapBtn.addEventListener('click', function () {
            const temp = originSelect.value;
            originSelect.value = destinationSelect.value;
            destinationSelect.value = temp;

            // Animación visual
            this.classList.add('rotating');
            setTimeout(() => {
                this.classList.remove('rotating');
            }, 300);
        });
    }

    // Validación del formulario de búsqueda
    const searchForm = document.getElementById('flightSearchForm');

    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            const origin = originSelect.value;
            const destination = destinationSelect.value;
            const date = document.getElementById('date').value;

            let errors = [];

            if (!origin) {
                errors.push('Debe seleccionar un aeropuerto de origen');
            }

            if (!destination) {
                errors.push('Debe seleccionar un aeropuerto de destino');
            }

            if (origin && destination && origin === destination) {
                errors.push('El origen y destino deben ser diferentes');
            }

            if (!date) {
                errors.push('Debe seleccionar una fecha');
            } else {
                const selectedDate = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    errors.push('La fecha no puede ser anterior a hoy');
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert('Errores en el formulario:\n\n' + errors.join('\n'));
                return false;
            }
        });
    }

    // Validación de rango de precios
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');

    if (minPriceInput && maxPriceInput) {
        function validatePriceRange() {
            const minPrice = parseFloat(minPriceInput.value) || 0;
            const maxPrice = parseFloat(maxPriceInput.value) || Infinity;

            if (minPrice > maxPrice && maxPrice > 0) {
                maxPriceInput.setCustomValidity('El precio máximo debe ser mayor al mínimo');
            } else {
                maxPriceInput.setCustomValidity('');
            }
        }

        minPriceInput.addEventListener('change', validatePriceRange);
        maxPriceInput.addEventListener('change', validatePriceRange);
    }

    // Autocompletar fecha con hoy si está vacía
    const dateInput = document.getElementById('date');
    if (dateInput && !dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }
});

// Agregar clase de rotación al CSS
const style = document.createElement('style');
style.textContent = `
    .rotating {
        animation: rotate 0.3s ease-in-out;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(180deg); }
    }
`;
document.head.appendChild(style);
