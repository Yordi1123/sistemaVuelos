<?php
/**
 * Router Principal - Punto de Entrada
 * Sistema de Reserva de Vuelos
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

// Cargar helpers
require_once __DIR__ . '/../src/helpers/session.php';
require_once __DIR__ . '/../src/helpers/security.php';
require_once __DIR__ . '/../src/helpers/validation.php';
require_once __DIR__ . '/../src/helpers/utils.php';

// Cargar modelos
require_once __DIR__ . '/../src/models/Database.php';
require_once __DIR__ . '/../src/models/User.php';

// Cargar controladores
require_once __DIR__ . '/../src/controllers/AuthController.php';

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);

// Remover el directorio base de la URI
$path = str_replace($script_name, '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);
$path = trim($path, '/');

// Si está vacío, es la página de inicio
if (empty($path)) {
    $path = 'home';
}

// Enrutamiento simple
try {
    switch ($path) {
        // Página de inicio
        case 'home':
        case '':
            require VIEWS_PATH . '/home.php';
            break;
        
        // Autenticación
        case 'login':
            $controller = new AuthController();
            if (is_post()) {
                $controller->login();
            } else {
                $controller->showLogin();
            }
            break;
        
        case 'register':
            $controller = new AuthController();
            if (is_post()) {
                $controller->register();
            } else {
                $controller->showRegister();
            }
            break;
        
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
        
        // Búsqueda de vuelos
        case 'flights/search':
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Airline.php';
            require_once __DIR__ . '/../src/models/Airport.php';
            require_once __DIR__ . '/../src/controllers/FlightController.php';
            
            $controller = new FlightController();
            if (is_post()) {
                $controller->search();
            } else {
                $controller->showSearch();
            }
            break;
        
        case 'flights/details':
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Airline.php';
            require_once __DIR__ . '/../src/models/Airport.php';
            require_once __DIR__ . '/../src/controllers/FlightController.php';
            
            $controller = new FlightController();
            $controller->details();
            break;
        
        // Búsqueda de vuelos (próximamente)
        case 'flights/search-old':
            echo "Búsqueda de vuelos - Próximamente en Módulo 2";
            break;
        
        // Pagos
        case 'payment/checkout':
            require_auth();
            require_once __DIR__ . '/../src/models/Payment.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/controllers/PaymentController.php';
            $controller = new PaymentController();
            $controller->checkout();
            break;
        
        case 'payment/process':
            require_auth();
            require_once __DIR__ . '/../src/models/Payment.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/controllers/PaymentController.php';
            $controller = new PaymentController();
            $controller->process();
            break;
        
        case 'payment/success':
            require_auth();
            require_once __DIR__ . '/../src/models/Payment.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/controllers/PaymentController.php';
            $controller = new PaymentController();
            $controller->success();
            break;
        
        // Perfil
        case 'profile/dashboard':
        case 'profile':
            require_auth();
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/controllers/ProfileController.php';
            $controller = new ProfileController();
            $controller->dashboard();
            break;
        
        case 'profile/edit':
            require_auth();
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/controllers/ProfileController.php';
            $controller = new ProfileController();
            $controller->edit();
            break;
        
        case 'profile/change-password':
            require_auth();
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/controllers/ProfileController.php';
            $controller = new ProfileController();
            $controller->changePassword();
            break;
        
        // Perfil (próximamente)
        case 'profile-old':
            require_auth();
            echo "Perfil de usuario - Próximamente en Módulo 4";
            break;
        
        // Reservas
        case 'booking/start':
            require_auth();
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/models/Seat.php';
            require_once __DIR__ . '/../src/models/Passenger.php';
            require_once __DIR__ . '/../src/controllers/BookingController.php';
            $controller = new BookingController();
            $controller->start();
            break;
        
        case 'booking/select-seats':
            require_auth();
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/models/Seat.php';
            require_once __DIR__ . '/../src/models/Passenger.php';
            require_once __DIR__ . '/../src/controllers/BookingController.php';
            $controller = new BookingController();
            $controller->selectSeats();
            break;
        
        case 'booking/passenger-info':
            require_auth();
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/models/Seat.php';
            require_once __DIR__ . '/../src/models/Passenger.php';
            require_once __DIR__ . '/../src/controllers/BookingController.php';
            $controller = new BookingController();
            $controller->passengerInfo();
            break;
        
        case 'booking/summary':
            require_auth();
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/models/Seat.php';
            require_once __DIR__ . '/../src/models/Passenger.php';
            require_once __DIR__ . '/../src/controllers/BookingController.php';
            $controller = new BookingController();
            $controller->summary();
            break;
        
        case 'booking/confirm':
            require_auth();
            require_once __DIR__ . '/../src/models/Flight.php';
            require_once __DIR__ . '/../src/models/Booking.php';
            require_once __DIR__ . '/../src/models/Seat.php';
            require_once __DIR__ . '/../src/models/Passenger.php';
            require_once __DIR__ . '/../src/controllers/BookingController.php';
            $controller = new BookingController();
            $controller->confirm();
            break;
        
        // Reservas (próximamente)
        case 'bookings':
            require_auth();
            echo "Mis reservas - Próximamente en Módulo 3";
            break;
        
        // 404 - Página no encontrada
        default:
            http_response_code(404);
            echo "<h1>404 - Página no encontrada</h1>";
            echo "<p>La página que buscas no existe.</p>";
            echo "<a href='" . url('/') . "'>Volver al inicio</a>";
            break;
    }
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "<h1>Error</h1>";
        echo "<pre>" . $e->getMessage() . "</pre>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        echo "<h1>Error del servidor</h1>";
        echo "<p>Ha ocurrido un error. Por favor, intenta más tarde.</p>";
    }
}
