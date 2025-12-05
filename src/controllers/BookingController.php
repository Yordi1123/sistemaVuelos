<?php
/**
 * Controlador de Reservas
 * Sistema de Reserva de Vuelos
 */

class BookingController {
    private $bookingModel;
    private $flightModel;
    private $seatModel;
    private $passengerModel;
    private $db;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->flightModel = new Flight();
        $this->seatModel = new Seat();
        $this->passengerModel = new Passenger();
    }
    
    /**
     * Iniciar proceso de reserva
     */
    public function start() {
        require_auth();
        
        $flight_id = get_param('flight');
        $category_id = get_param('category');
        
        // Si no hay flight_id en parámetros, intentar obtener de sesión
        if (empty($flight_id)) {
            $flight_session = session_get('booking_flight');
            if ($flight_session && isset($flight_session['id_vuelo'])) {
                $flight_id = $flight_session['id_vuelo'];
            } else {
                set_flash('error', 'Debe seleccionar un vuelo');
                redirect(url('/flights/search'));
                return;
            }
        }
        
        // Obtener información del vuelo
        $flight = $this->flightModel->getDetails($flight_id);
        
        if (!$flight) {
            set_flash('error', 'Vuelo no encontrado');
            redirect(url('/flights/search'));
            return;
        }
        
        // Obtener tarifas
        $fares = $this->flightModel->getFares($flight_id);
        
        // Si se especificó categoría, filtrar
        if ($category_id) {
            $fares = array_filter($fares, function($fare) use ($category_id) {
                return $fare['id_categoria'] == $category_id;
            });
        }
        
        // Guardar en sesión
        session_set('booking_flight', $flight);
        session_set('booking_fares', $fares);
        
        require VIEWS_PATH . '/booking/start.php';
    }
    
    /**
     * Selección de asientos
     */
    public function selectSeats() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/booking/start'));
            return;
        }
        
        $flight_id = post_param('flight_id');
        $category_id = post_param('category_id');
        $num_passengers = post_param('num_passengers');
        
        // Validaciones
        if (empty($flight_id) || empty($category_id) || empty($num_passengers)) {
            set_flash('error', 'Datos incompletos');
            redirect(url('/booking/start'));
            return;
        }
        
        if ($num_passengers < 1 || $num_passengers > 9) {
            set_flash('error', 'Número de pasajeros inválido (1-9)');
            redirect(url('/booking/start'));
            return;
        }
        
        // Obtener información del vuelo y tarifa
        $flight = $this->flightModel->getDetails($flight_id);
        $fares = $this->flightModel->getFares($flight_id);
        
        $selected_fare = null;
        foreach ($fares as $fare) {
            if ($fare['id_categoria'] == $category_id) {
                $selected_fare = $fare;
                break;
            }
        }
        
        if (!$selected_fare) {
            set_flash('error', 'Tarifa no encontrada');
            redirect(url('/booking/start'));
            return;
        }
        
        // Verificar disponibilidad
        if ($selected_fare['asientos_disponibles'] < $num_passengers) {
            set_flash('error', 'No hay suficientes asientos disponibles');
            redirect(url('/booking/start'));
            return;
        }
        
        // Obtener mapa de asientos
        $seatMap = $this->seatModel->getSeatMap($flight_id, $category_id);
        
        // Guardar en sesión
        session_set('booking_num_passengers', $num_passengers);
        session_set('booking_category', $selected_fare);
        session_set('booking_seat_map', $seatMap);
        
        require VIEWS_PATH . '/booking/select_seats.php';
    }
    
    /**
     * Información de pasajeros
     */
    public function passengerInfo() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/booking/start'));
            return;
        }
        
        $selected_seats = post_param('selected_seats');
        
        if (empty($selected_seats)) {
            set_flash('error', 'Debe seleccionar asientos');
            redirect(url('/booking/select-seats'));
            return;
        }
        
        // Convertir a array si viene como string
        if (is_string($selected_seats)) {
            $selected_seats = explode(',', $selected_seats);
        }
        
        $num_passengers = session_get('booking_num_passengers');
        
        if (count($selected_seats) != $num_passengers) {
            set_flash('error', 'Debe seleccionar ' . $num_passengers . ' asiento(s)');
            redirect(url('/booking/select-seats'));
            return;
        }
        
        // Verificar disponibilidad de asientos
        if (!$this->seatModel->checkAvailability($selected_seats)) {
            set_flash('error', 'Uno o más asientos ya no están disponibles');
            redirect(url('/booking/select-seats'));
            return;
        }
        
        // Guardar asientos seleccionados
        session_set('booking_selected_seats', $selected_seats);
        
        // Obtener datos del usuario actual para prellenar
        $user = session_get('user');
        
        require VIEWS_PATH . '/booking/passenger_info.php';
    }
    
    /**
     * Resumen de reserva
     */
    public function summary() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/booking/start'));
            return;
        }
        
        $passengers_data = post_param('passengers');
        
        if (empty($passengers_data) || !is_array($passengers_data)) {
            set_flash('error', 'Debe ingresar datos de pasajeros');
            redirect(url('/booking/passenger-info'));
            return;
        }
        
        // Validar datos de pasajeros
        $num_passengers = session_get('booking_num_passengers');
        
        if (count($passengers_data) != $num_passengers) {
            set_flash('error', 'Datos de pasajeros incompletos');
            redirect(url('/booking/passenger-info'));
            return;
        }
        
        // Guardar datos de pasajeros
        session_set('booking_passengers', $passengers_data);
        
        // Calcular total
        $category = session_get('booking_category');
        $total = $category['precio'] * $num_passengers;
        
        session_set('booking_total', $total);
        
        require VIEWS_PATH . '/booking/summary.php';
    }
    
    /**
     * Confirmar reserva
     */
    public function confirm() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/booking/start'));
            return;
        }
        
        try {
            $this->db = Database::getInstance()->getConnection();
            $this->db->beginTransaction();
            
            // Obtener datos de sesión
            $user = session_get('user');
            $flight = session_get('booking_flight');
            $category = session_get('booking_category');
            $num_passengers = session_get('booking_num_passengers');
            $selected_seats = session_get('booking_selected_seats');
            $passengers_data = session_get('booking_passengers');
            $total = session_get('booking_total');
            
            // Verificar que todos los datos estén presentes con mensajes específicos
            $missing = [];
            if (!$user) $missing[] = 'usuario';
            if (!$flight) $missing[] = 'vuelo';
            if (!$category) $missing[] = 'categoría';
            if (!$num_passengers) $missing[] = 'número de pasajeros';
            if (!$selected_seats) $missing[] = 'asientos seleccionados';
            if (!$passengers_data) $missing[] = 'datos de pasajeros';
            
            if (!empty($missing)) {
                throw new Exception('Datos de reserva incompletos: ' . implode(', ', $missing) . '. Por favor, inicie el proceso de reserva nuevamente.');
            }
            
            // Verificar disponibilidad de asientos nuevamente
            if (!$this->seatModel->checkAvailability($selected_seats)) {
                throw new Exception('Uno o más asientos ya no están disponibles');
            }
            
            // Crear reserva
            $reserva_data = [
                'id_usuario' => $user['id_usuario'],
                'id_vuelo' => $flight['id_vuelo'],
                'id_tarifa' => $category['id_tarifa'],
                'total_pasajeros' => $num_passengers,
                'monto_total' => $total ?? ($category['precio'] * $num_passengers),
                'observaciones' => null
            ];
            
            $reserva_id = $this->bookingModel->create($reserva_data);
            
            if (!$reserva_id) {
                throw new Exception('Error al crear reserva');
            }
            
            // Obtener detalle de reserva ID
            $sql = "SELECT id_detalle FROM detalle_reserva WHERE id_reserva = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$reserva_id]);
            $detalle = $stmt->fetch();
            
            if (!$detalle) {
                throw new Exception('Error al obtener detalle de reserva');
            }
            
            $detalle_id = $detalle['id_detalle'];
            
            // Crear pasajeros y asignar asientos
            foreach ($passengers_data as $index => $passenger_data) {
                $passenger_data['id_reserva'] = $reserva_id;
                $passenger_id = $this->passengerModel->create($passenger_data);
                
                if (!$passenger_id) {
                    throw new Exception('Error al crear pasajero ' . ($index + 1));
                }
                
                // Asignar asiento
                if (isset($selected_seats[$index])) {
                    $seat_id = $selected_seats[$index];
                    if (!$this->seatModel->reserve([$seat_id], $passenger_id, $detalle_id)) {
                        throw new Exception('Error al reservar asiento para pasajero ' . ($index + 1));
                    }
                }
            }
            
            $this->db->commit();
            
            // Limpiar sesión de reserva
            session_delete('booking_flight');
            session_delete('booking_fares');
            session_delete('booking_num_passengers');
            session_delete('booking_category');
            session_delete('booking_seat_map');
            session_delete('booking_selected_seats');
            session_delete('booking_passengers');
            session_delete('booking_total');
            
            // Obtener reserva completa
            $booking = $this->bookingModel->getById($reserva_id);
            
            set_flash('success', 'Reserva creada exitosamente');
            
            require VIEWS_PATH . '/booking/confirmation.php';
            
        } catch (Exception $e) {
            if (isset($this->db)) {
                $this->db->rollBack();
            }
            
            set_flash('error', $e->getMessage());
            redirect(url('/booking/summary'));
        }
    }
    
    /**
     * Ver mis reservas
     */
    public function myBookings() {
        require_auth();
        
        $user = session_get('user');
        $bookings = $this->bookingModel->getUserBookings($user['id_usuario']);
        
        require VIEWS_PATH . '/booking/my_bookings.php';
    }
    
    /**
     * Ver detalle de reserva
     */
    public function viewBooking($booking_id = null) {
        require_auth();
        
        if ($booking_id === null) {
            $booking_id = get_param('id');
        }
        
        if (empty($booking_id)) {
            set_flash('error', 'Reserva no encontrada');
            redirect(url('/booking/my-bookings'));
            return;
        }
        
        $booking = $this->bookingModel->getById($booking_id);
        
        if (!$booking) {
            set_flash('error', 'Reserva no encontrada');
            redirect(url('/booking/my-bookings'));
            return;
        }
        
        // Verificar que la reserva pertenezca al usuario
        $user = session_get('user');
        if ($booking['id_usuario'] != $user['id_usuario']) {
            set_flash('error', 'No tiene permiso para ver esta reserva');
            redirect(url('/booking/my-bookings'));
            return;
        }
        
        $details = $this->bookingModel->getDetails($booking_id);
        $passengers = $this->passengerModel->getByReservation($booking_id);
        
        require VIEWS_PATH . '/booking/view.php';
    }
}
