<?php
$page_title = 'Resultados de Búsqueda - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';

$search_params = session_get('search_params', []);
?>

<div class="results-container">
    <div class="results-header">
        <h1><i class="fas fa-search"></i> Resultados de Búsqueda</h1>
        
        <?php if (!empty($origin) && !empty($destination)): ?>
        <div class="search-summary">
            <div class="route-summary">
                <span class="origin"><?= escape_html($origin['ciudad']) ?> (<?= $origin['codigo_iata'] ?>)</span>
                <i class="fas fa-arrow-right"></i>
                <span class="destination"><?= escape_html($destination['ciudad']) ?> (<?= $destination['codigo_iata'] ?>)</span>
            </div>
            <div class="date-summary">
                <i class="fas fa-calendar"></i>
                <?= format_date($search_params['date'], 'd \d\e F, Y') ?>
            </div>
        </div>
        <?php endif; ?>
        
        <a href="<?= url('/flights/search') ?>" class="btn btn-secondary">
            <i class="fas fa-edit"></i>
            Modificar Búsqueda
        </a>
    </div>
    
    <?php if (empty($flights)): ?>
    <!-- Sin resultados -->
    <div class="no-results">
        <i class="fas fa-search"></i>
        <h2>No se encontraron vuelos</h2>
        <p>No hay vuelos disponibles para los criterios de búsqueda seleccionados.</p>
        <div class="suggestions">
            <h3>Sugerencias:</h3>
            <ul>
                <li>Intenta con fechas diferentes</li>
                <li>Verifica que el origen y destino sean correctos</li>
                <li>Elimina algunos filtros para ampliar la búsqueda</li>
                <li>Prueba con aeropuertos cercanos</li>
            </ul>
        </div>
        <a href="<?= url('/flights/search') ?>" class="btn btn-primary btn-large">
            <i class="fas fa-search"></i>
            Nueva Búsqueda
        </a>
    </div>
    
    <?php else: ?>
    <!-- Resultados encontrados -->
    <div class="results-info">
        <div class="results-count">
            <i class="fas fa-plane"></i>
            <strong><?= count($flights) ?></strong> vuelo<?= count($flights) != 1 ? 's' : '' ?> encontrado<?= count($flights) != 1 ? 's' : '' ?>
        </div>
        
        <div class="results-sort">
            <label>
                <i class="fas fa-sort"></i>
                Ordenar por:
            </label>
            <select id="sortResults" onchange="sortFlights(this.value)">
                <option value="time" <?= ($search_params['search_type'] ?? 'schedule') === 'schedule' ? 'selected' : '' ?>>
                    Hora de salida
                </option>
                <option value="price" <?= ($search_params['search_type'] ?? '') === 'price' ? 'selected' : '' ?>>
                    Precio (menor a mayor)
                </option>
                <option value="duration">Duración</option>
                <option value="airline">Aerolínea</option>
            </select>
        </div>
    </div>
    
    <div class="results-list" id="flightsList">
        <?php foreach ($flights as $flight): ?>
            <?php include VIEWS_PATH . '/components/flight_card.php'; ?>
        <?php endforeach; ?>
    </div>
    
    <?php endif; ?>
</div>

<script>
// Ordenamiento de resultados en cliente
function sortFlights(criteria) {
    const list = document.getElementById('flightsList');
    const cards = Array.from(list.children);
    
    cards.sort((a, b) => {
        switch(criteria) {
            case 'time':
                return a.dataset.departure > b.dataset.departure ? 1 : -1;
            case 'price':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'duration':
                return parseInt(a.dataset.duration) - parseInt(b.dataset.duration);
            case 'airline':
                return a.dataset.airline > b.dataset.airline ? 1 : -1;
            default:
                return 0;
        }
    });
    
    list.innerHTML = '';
    cards.forEach(card => list.appendChild(card));
}

// Agregar data attributes a las tarjetas para ordenamiento
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.flight-card');
    cards.forEach(card => {
        const timeEl = card.querySelector('.route-point .time');
        const priceEl = card.querySelector('.price');
        const durationEl = card.querySelector('.duration');
        const airlineEl = card.querySelector('.airline-name');
        
        if (timeEl) card.dataset.departure = timeEl.textContent.trim();
        if (priceEl) card.dataset.price = priceEl.textContent.replace(/[^\d.]/g, '');
        if (durationEl) {
            const duration = durationEl.textContent.match(/(\d+)h\s*(\d+)m/);
            if (duration) {
                card.dataset.duration = parseInt(duration[1]) * 60 + parseInt(duration[2]);
            }
        }
        if (airlineEl) card.dataset.airline = airlineEl.textContent.trim();
    });
});
</script>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
