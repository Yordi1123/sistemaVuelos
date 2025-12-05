<?php
/**
 * Controlador de Vuelos
 * Sistema de Reserva de Vuelos
 */

class FlightController {
    private $flightModel;
    private $airportModel;
    private $airlineModel;
    
    public function __construct() {
        $this->flightModel = new Flight();
        $this->airportModel = new Airport();
        $this->airlineModel = new Airline();
    }
    
    /**
     * Mostrar formulario de búsqueda
     */
    public function showSearch() {
        $airports = $this->airportModel->getAll();
        $airlines = $this->airlineModel->getAll();
        
        require VIEWS_PATH . '/flights/search.php';
    }
    
    /**
     * Procesar búsqueda de vuelos
     */
    public function search() {
        if (!is_post()) {
            redirect(url('/flights/search'));
            return;
        }
        
        // Obtener parámetros de búsqueda
        $origin_id = post_param('origin');
        $destination_id = post_param('destination');
        $date = post_param('date');
        $search_type = post_param('search_type', 'schedule'); // schedule, price, status
        
        // Validaciones
        $errors = [];
        
        if (empty($origin_id)) {
            $errors[] = 'Debe seleccionar un aeropuerto de origen';
        }
        
        if (empty($destination_id)) {
            $errors[] = 'Debe seleccionar un aeropuerto de destino';
        }
        
        if ($origin_id == $destination_id) {
            $errors[] = 'El origen y destino deben ser diferentes';
        }
        
        if (empty($date)) {
            $errors[] = 'Debe seleccionar una fecha';
        } elseif (!validate_date($date)) {
            $errors[] = 'Fecha inválida';
        } elseif (!validate_future_date($date) && $date != date('Y-m-d')) {
            $errors[] = 'La fecha no puede ser anterior a hoy';
        }
        
        if (!empty($errors)) {
            session_set('search_errors', $errors);
            session_set('search_data', $_POST);
            redirect(url('/flights/search'));
            return;
        }
        
        // Realizar búsqueda
        if ($search_type === 'status') {
            $flights = $this->flightModel->searchByStatus($date);
        } else {
            $order_by = ($search_type === 'price') ? 'price' : 'schedule';
            $flights = $this->flightModel->search($origin_id, $destination_id, $date, $order_by);
        }
        
        // Aplicar filtros opcionales
        $filters = [];
        
        if (!empty(post_param('airline'))) {
            $filters['airline'] = post_param('airline');
        }
        
        if (!empty(post_param('direct_only'))) {
            $filters['direct_only'] = true;
        }
        
        if (!empty(post_param('min_price'))) {
            $filters['min_price'] = floatval(post_param('min_price'));
        }
        
        if (!empty(post_param('max_price'))) {
            $filters['max_price'] = floatval(post_param('max_price'));
        }
        
        if (!empty($filters)) {
            $flights = $this->flightModel->applyFilters($flights, $filters);
        }
        
        // Guardar resultados en sesión
        session_set('search_results', $flights);
        session_set('search_params', [
            'origin_id' => $origin_id,
            'destination_id' => $destination_id,
            'date' => $date,
            'search_type' => $search_type
        ]);
        
        // Obtener datos de aeropuertos para mostrar
        $origin = $this->airportModel->getById($origin_id);
        $destination = $this->airportModel->getById($destination_id);
        
        // Mostrar resultados
        $airports = $this->airportModel->getAll();
        $airlines = $this->airlineModel->getAll();
        
        require VIEWS_PATH . '/flights/results.php';
    }
    
    /**
     * Mostrar detalles de un vuelo
     * @param int $flight_id
     */
    public function details($flight_id = null) {
        if ($flight_id === null) {
            $flight_id = get_param('id');
        }
        
        if (empty($flight_id)) {
            set_flash('error', 'Vuelo no encontrado');
            redirect(url('/flights/search'));
            return;
        }
        
        // Obtener detalles del vuelo
        $flight = $this->flightModel->getDetails($flight_id);
        
        if (!$flight) {
            set_flash('error', 'Vuelo no encontrado');
            redirect(url('/flights/search'));
            return;
        }
        
        // Obtener tarifas
        $fares = $this->flightModel->getFares($flight_id);
        
        require VIEWS_PATH . '/flights/details.php';
    }
}
