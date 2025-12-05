<?php
$page_title = 'Buscar Vuelos - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';

// Recuperar errores y datos previos si existen
$errors = session_get('search_errors', []);
$search_data = session_get('search_data', []);
session_delete('search_errors');
session_delete('search_data');
?>

<div class="search-container">
    <div class="search-header">
        <h1><i class="fas fa-search"></i> Buscar Vuelos</h1>
        <p>Encuentra el vuelo perfecto para tu próximo viaje</p>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?= escape_html($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <form action="<?= url('/flights/search') ?>" method="POST" class="search-form" id="flightSearchForm">
        <div class="search-main">
            <div class="form-row">
                <div class="form-group">
                    <label for="origin">
                        <i class="fas fa-plane-departure"></i>
                        Origen
                    </label>
                    <select id="origin" name="origin" required>
                        <option value="">Seleccionar aeropuerto</option>
                        <?php foreach ($airports as $airport): ?>
                        <option value="<?= $airport['id_aeropuerto'] ?>" 
                                <?= (isset($search_data['origin']) && $search_data['origin'] == $airport['id_aeropuerto']) ? 'selected' : '' ?>>
                            <?= escape_html($airport['ciudad']) ?> (<?= $airport['codigo_iata'] ?>) - <?= escape_html($airport['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="swap-button-container">
                    <button type="button" class="btn-swap" id="swapAirports" title="Intercambiar origen y destino">
                        <i class="fas fa-exchange-alt"></i>
                    </button>
                </div>
                
                <div class="form-group">
                    <label for="destination">
                        <i class="fas fa-plane-arrival"></i>
                        Destino
                    </label>
                    <select id="destination" name="destination" required>
                        <option value="">Seleccionar aeropuerto</option>
                        <?php foreach ($airports as $airport): ?>
                        <option value="<?= $airport['id_aeropuerto'] ?>"
                                <?= (isset($search_data['destination']) && $search_data['destination'] == $airport['id_aeropuerto']) ? 'selected' : '' ?>>
                            <?= escape_html($airport['ciudad']) ?> (<?= $airport['codigo_iata'] ?>) - <?= escape_html($airport['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date">
                        <i class="fas fa-calendar"></i>
                        Fecha de Salida
                    </label>
                    <input type="date" 
                           id="date" 
                           name="date" 
                           min="<?= date('Y-m-d') ?>"
                           value="<?= $search_data['date'] ?? date('Y-m-d') ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="search_type">
                        <i class="fas fa-filter"></i>
                        Tipo de Búsqueda
                    </label>
                    <select id="search_type" name="search_type">
                        <option value="schedule" <?= (isset($search_data['search_type']) && $search_data['search_type'] == 'schedule') ? 'selected' : '' ?>>
                            Por Horarios
                        </option>
                        <option value="price" <?= (isset($search_data['search_type']) && $search_data['search_type'] == 'price') ? 'selected' : '' ?>>
                            Por Tarifas (Menor Precio)
                        </option>
                        <option value="status" <?= (isset($search_data['search_type']) && $search_data['search_type'] == 'status') ? 'selected' : '' ?>>
                            Por Estado (Vuelos del Día)
                        </option>
                    </select>
                </div>
            </div>
            
            <div class="search-actions">
                <button type="button" class="btn-toggle-filters" id="toggleFilters">
                    <i class="fas fa-sliders-h"></i>
                    Filtros Avanzados
                </button>
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-search"></i>
                    Buscar Vuelos
                </button>
            </div>
        </div>
        
        <!-- Filtros Avanzados (Colapsable) -->
        <div class="search-filters" id="advancedFilters" style="display: none;">
            <h3><i class="fas fa-filter"></i> Filtros Opcionales</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="airline">
                        <i class="fas fa-plane"></i>
                        Aerolínea
                    </label>
                    <select id="airline" name="airline">
                        <option value="">Todas las aerolíneas</option>
                        <?php foreach ($airlines as $airline): ?>
                        <option value="<?= $airline['codigo_iata'] ?>"
                                <?= (isset($search_data['airline']) && $search_data['airline'] == $airline['codigo_iata']) ? 'selected' : '' ?>>
                            <?= escape_html($airline['nombre']) ?> (<?= $airline['codigo_iata'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="direct_only" 
                               value="1"
                               <?= (isset($search_data['direct_only'])) ? 'checked' : '' ?>>
                        <span>Solo vuelos directos</span>
                    </label>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="min_price">
                        <i class="fas fa-dollar-sign"></i>
                        Precio Mínimo (S/)
                    </label>
                    <input type="number" 
                           id="min_price" 
                           name="min_price" 
                           min="0" 
                           step="10"
                           value="<?= $search_data['min_price'] ?? '' ?>"
                           placeholder="0">
                </div>
                
                <div class="form-group">
                    <label for="max_price">
                        <i class="fas fa-dollar-sign"></i>
                        Precio Máximo (S/)
                    </label>
                    <input type="number" 
                           id="max_price" 
                           name="max_price" 
                           min="0" 
                           step="10"
                           value="<?= $search_data['max_price'] ?? '' ?>"
                           placeholder="Sin límite">
                </div>
            </div>
        </div>
    </form>
    
    <!-- Información adicional -->
    <div class="search-info">
        <div class="info-card">
            <i class="fas fa-clock"></i>
            <h4>Búsqueda por Horarios</h4>
            <p>Encuentra vuelos ordenados por hora de salida</p>
        </div>
        <div class="info-card">
            <i class="fas fa-tag"></i>
            <h4>Búsqueda por Tarifas</h4>
            <p>Encuentra las mejores ofertas ordenadas por precio</p>
        </div>
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <h4>Búsqueda por Estado</h4>
            <p>Ver disponibilidad y estado de vuelos del día</p>
        </div>
    </div>
</div>

<script src="<?= asset('js/flight_search.js') ?>"></script>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
