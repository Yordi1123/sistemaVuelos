<?php
$page_title = 'Iniciar Reserva - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';

$flight = session_get('booking_flight');
$fares = session_get('booking_fares');
?>

<div class="booking-container">
    <div class="booking-steps">
        <div class="step active">
            <div class="step-number">1</div>
            <div class="step-label">Seleccionar</div>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <div class="step-label">Asientos</div>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-label">Pasajeros</div>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-label">Confirmar</div>
        </div>
    </div>
    
    <div class="booking-content">
        <h1><i class="fas fa-ticket-alt"></i> Iniciar Reserva</h1>
        
        <!-- Información del vuelo -->
        <div class="flight-summary-card">
            <h2>Vuelo Seleccionado</h2>
            <div class="flight-info-grid">
                <div class="info-item">
                    <strong>Vuelo:</strong> <?= $flight['numero_vuelo'] ?>
                </div>
                <div class="info-item">
                    <strong>Aerolínea:</strong> <?= escape_html($flight['aerolinea']) ?>
                </div>
                <div class="info-item">
                    <strong>Ruta:</strong> 
                    <?= escape_html($flight['ciudad_origen']) ?> (<?= $flight['codigo_origen'] ?>) 
                    → 
                    <?= escape_html($flight['ciudad_destino']) ?> (<?= $flight['codigo_destino'] ?>)
                </div>
                <div class="info-item">
                    <strong>Fecha:</strong> <?= format_date($flight['fecha_salida'], 'd/m/Y') ?>
                </div>
                <div class="info-item">
                    <strong>Salida:</strong> <?= format_datetime($flight['fecha_salida'], 'H:i') ?>
                </div>
                <div class="info-item">
                    <strong>Llegada:</strong> <?= format_datetime($flight['fecha_llegada'], 'H:i') ?>
                </div>
            </div>
        </div>
        
        <!-- Formulario de selección -->
        <form action="<?= url('/booking/select-seats') ?>" method="POST" class="booking-form">
            <input type="hidden" name="flight_id" value="<?= $flight['id_vuelo'] ?>">
            
            <div class="form-section">
                <h3>Seleccione Clase y Número de Pasajeros</h3>
                
                <div class="fare-selection">
                    <?php foreach ($fares as $fare): ?>
                    <div class="fare-option">
                        <input type="radio" 
                               id="fare_<?= $fare['id_categoria'] ?>" 
                               name="category_id" 
                               value="<?= $fare['id_categoria'] ?>"
                               data-price="<?= $fare['precio'] ?>"
                               required>
                        <label for="fare_<?= $fare['id_categoria'] ?>">
                            <div class="fare-option-header">
                                <h4><?= escape_html($fare['categoria']) ?></h4>
                                <div class="fare-price"><?= format_price($fare['precio']) ?></div>
                            </div>
                            <div class="fare-option-body">
                                <p><?= escape_html($fare['descripcion']) ?></p>
                                <div class="fare-availability">
                                    <i class="fas fa-chair"></i>
                                    <?= $fare['asientos_disponibles'] ?> asientos disponibles
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="form-group">
                    <label for="num_passengers">
                        <i class="fas fa-users"></i>
                        Número de Pasajeros
                    </label>
                    <select id="num_passengers" name="num_passengers" required>
                        <option value="">Seleccionar...</option>
                        <?php for ($i = 1; $i <= 9; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> pasajero<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="price-summary" id="priceSummary" style="display: none;">
                    <div class="price-row">
                        <span>Precio por pasajero:</span>
                        <span id="pricePerPassenger">S/ 0.00</span>
                    </div>
                    <div class="price-row">
                        <span>Número de pasajeros:</span>
                        <span id="numPassengersDisplay">0</span>
                    </div>
                    <div class="price-row total">
                        <span>Total Estimado:</span>
                        <span id="totalPrice">S/ 0.00</span>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= url('/flights/details?id=' . $flight['id_vuelo']) ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-arrow-right"></i>
                    Continuar a Selección de Asientos
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fareRadios = document.querySelectorAll('input[name="category_id"]');
    const numPassengersSelect = document.getElementById('num_passengers');
    const priceSummary = document.getElementById('priceSummary');
    const pricePerPassenger = document.getElementById('pricePerPassenger');
    const numPassengersDisplay = document.getElementById('numPassengersDisplay');
    const totalPrice = document.getElementById('totalPrice');
    
    function updatePriceSummary() {
        const selectedFare = document.querySelector('input[name="category_id"]:checked');
        const numPassengers = parseInt(numPassengersSelect.value) || 0;
        
        if (selectedFare && numPassengers > 0) {
            const price = parseFloat(selectedFare.dataset.price);
            const total = price * numPassengers;
            
            pricePerPassenger.textContent = 'S/ ' + price.toFixed(2);
            numPassengersDisplay.textContent = numPassengers;
            totalPrice.textContent = 'S/ ' + total.toFixed(2);
            priceSummary.style.display = 'block';
        } else {
            priceSummary.style.display = 'none';
        }
    }
    
    fareRadios.forEach(radio => {
        radio.addEventListener('change', updatePriceSummary);
    });
    
    numPassengersSelect.addEventListener('change', updatePriceSummary);
});
</script>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
