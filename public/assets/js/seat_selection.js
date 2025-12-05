/**
 * JavaScript para Selección de Asientos
 * Sistema de Reserva de Vuelos
 */

document.addEventListener('DOMContentLoaded', function () {
    const maxPassengers = parseInt(document.querySelector('[data-seat-id]')?.closest('form')?.dataset.maxPassengers) ||
        parseInt(new URLSearchParams(window.location.search).get('passengers')) || 1;

    const selectedSeats = new Set();
    const seatButtons = document.querySelectorAll('.seat.available');
    const selectedSeatsInput = document.getElementById('selectedSeatsInput');
    const selectedSeatsSummary = document.getElementById('selectedSeatsSummary');
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const continueBtn = document.getElementById('continueBtn');

    // Manejar click en asiento
    seatButtons.forEach(button => {
        button.addEventListener('click', function () {
            const seatId = this.dataset.seatId;
            const seatNumber = this.dataset.seatNumber;

            if (selectedSeats.has(seatId)) {
                // Deseleccionar
                selectedSeats.delete(seatId);
                this.classList.remove('selected');
            } else {
                // Verificar límite
                if (selectedSeats.size >= maxPassengers) {
                    alert(`Solo puede seleccionar ${maxPassengers} asiento(s)`);
                    return;
                }

                // Seleccionar
                selectedSeats.add(seatId);
                this.classList.add('selected');
            }

            updateSummary();
        });
    });

    function updateSummary() {
        if (selectedSeats.size > 0) {
            // Mostrar resumen
            selectedSeatsSummary.style.display = 'block';

            // Actualizar lista
            const seatNumbers = Array.from(selectedSeats).map(id => {
                const btn = document.querySelector(`[data-seat-id="${id}"]`);
                return btn ? btn.dataset.seatNumber : id;
            });

            selectedSeatsList.innerHTML = seatNumbers.map(num =>
                `<span class="selected-seat-tag">${num}</span>`
            ).join(' ');

            // Actualizar input hidden
            selectedSeatsInput.value = Array.from(selectedSeats).join(',');

            // Habilitar botón continuar
            continueBtn.disabled = (selectedSeats.size !== maxPassengers);

            if (selectedSeats.size === maxPassengers) {
                continueBtn.classList.add('pulse');
            } else {
                continueBtn.classList.remove('pulse');
            }
        } else {
            selectedSeatsSummary.style.display = 'none';
            continueBtn.disabled = true;
            continueBtn.classList.remove('pulse');
        }
    }
});

// Animación de pulso para el botón
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
`;
document.head.appendChild(style);
