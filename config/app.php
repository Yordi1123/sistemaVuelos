<?php
/**
 * Configuración General de la Aplicación
 * Sistema de Reserva de Vuelos
 */

// Información de la aplicación
define('APP_NAME', 'Sistema de Reserva de Vuelos');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/sistemaVuelos/public');

// Rutas del sistema
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('VIEWS_PATH', BASE_PATH . '/views');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Configuración de sesiones
define('SESSION_LIFETIME', 7200); // 2 horas en segundos
define('SESSION_NAME', 'FLIGHT_SESSION');

// Configuración de seguridad
define('PASSWORD_MIN_LENGTH', 8);
define('HASH_ALGORITHM', PASSWORD_BCRYPT);
define('HASH_COST', 12);

// Configuración de reservas
define('RESERVATION_EXPIRY_HOURS', 24); // Tiempo de expiración de reservas sin pagar

// Zona horaria
date_default_timezone_set('America/Lima');

// Modo de desarrollo (cambiar a false en producción)
define('DEBUG_MODE', true);

// Configuración de errores
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
