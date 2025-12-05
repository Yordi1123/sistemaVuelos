<?php
/**
 * Configuración de Base de Datos - EJEMPLO
 * Sistema de Reserva de Vuelos
 * 
 * INSTRUCCIONES:
 * 1. Copiar este archivo como database.php
 * 2. Modificar las credenciales según tu entorno
 */

// Configuración de conexión a MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_vuelos');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Opciones de PDO
define('PDO_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
]);
