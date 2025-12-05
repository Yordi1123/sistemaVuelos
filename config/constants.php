<?php
/**
 * Constantes del Sistema
 * Sistema de Reserva de Vuelos
 */

// Estados de reserva
define('RESERVATION_STATUS_PENDING', 'pendiente');
define('RESERVATION_STATUS_CONFIRMED', 'confirmada');
define('RESERVATION_STATUS_PAID', 'pagada');
define('RESERVATION_STATUS_CANCELLED', 'cancelada');
define('RESERVATION_STATUS_EXPIRED', 'expirada');

// Estados de vuelo
define('FLIGHT_STATUS_SCHEDULED', 'programado');
define('FLIGHT_STATUS_ON_TIME', 'a_tiempo');
define('FLIGHT_STATUS_DELAYED', 'retrasado');
define('FLIGHT_STATUS_CANCELLED', 'cancelado');
define('FLIGHT_STATUS_COMPLETED', 'completado');

// Estados de asiento
define('SEAT_STATUS_AVAILABLE', 'disponible');
define('SEAT_STATUS_RESERVED', 'reservado');
define('SEAT_STATUS_OCCUPIED', 'ocupado');
define('SEAT_STATUS_BLOCKED', 'bloqueado');

// Estados de pago
define('PAYMENT_STATUS_PENDING', 'pendiente');
define('PAYMENT_STATUS_APPROVED', 'aprobado');
define('PAYMENT_STATUS_REJECTED', 'rechazado');
define('PAYMENT_STATUS_REFUNDED', 'reembolsado');

// Estados de boleto
define('TICKET_STATUS_ISSUED', 'emitido');
define('TICKET_STATUS_USED', 'usado');
define('TICKET_STATUS_CANCELLED', 'cancelado');

// Estados de usuario
define('USER_STATUS_ACTIVE', 'activo');
define('USER_STATUS_INACTIVE', 'inactivo');
define('USER_STATUS_SUSPENDED', 'suspendido');

// Tipos de pasajero
define('PASSENGER_TYPE_ADULT', 'adulto');
define('PASSENGER_TYPE_CHILD', 'niño');
define('PASSENGER_TYPE_INFANT', 'infante');

// Métodos de entrega
define('DELIVERY_METHOD_EMAIL', 'electronico');
define('DELIVERY_METHOD_PICKUP', 'recogida_mostrador');
define('DELIVERY_METHOD_SHIPPING', 'envio_domicilio');

// Mensajes de error comunes
define('ERROR_INVALID_CREDENTIALS', 'Email o contraseña incorrectos');
define('ERROR_EMAIL_EXISTS', 'El email ya está registrado');
define('ERROR_REQUIRED_FIELDS', 'Todos los campos son obligatorios');
define('ERROR_INVALID_EMAIL', 'Email inválido');
define('ERROR_PASSWORD_LENGTH', 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres');
define('ERROR_DATABASE', 'Error en la base de datos');
define('ERROR_UNAUTHORIZED', 'No autorizado');

// Mensajes de éxito
define('SUCCESS_REGISTER', 'Registro exitoso. Ya puedes iniciar sesión');
define('SUCCESS_LOGIN', 'Bienvenido al sistema');
define('SUCCESS_LOGOUT', 'Sesión cerrada correctamente');
