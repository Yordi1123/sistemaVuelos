-- ============================================
-- SISTEMA DE RESERVA DE VUELOS
-- Base de Datos MySQL
-- ============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_vuelos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_vuelos;

-- ============================================
-- TABLA: usuarios
-- Almacena información de clientes registrados
-- ============================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    fecha_nacimiento DATE,
    documento_identidad VARCHAR(50) UNIQUE,
    tipo_documento ENUM('DNI', 'Pasaporte', 'Carnet') DEFAULT 'DNI',
    direccion TEXT,
    ciudad VARCHAR(100),
    pais VARCHAR(100) DEFAULT 'Perú',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME,
    estado ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    INDEX idx_email (email),
    INDEX idx_documento (documento_identidad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: aerolineas
-- Catálogo de aerolíneas disponibles
-- ============================================
CREATE TABLE aerolineas (
    id_aerolinea INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    codigo_iata CHAR(2) NOT NULL UNIQUE COMMENT 'Código IATA de 2 letras',
    codigo_icao CHAR(3) UNIQUE COMMENT 'Código ICAO de 3 letras',
    pais_origen VARCHAR(100),
    telefono_contacto VARCHAR(50),
    email_contacto VARCHAR(150),
    sitio_web VARCHAR(255),
    logo_url VARCHAR(255),
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_codigo_iata (codigo_iata)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: aeropuertos
-- Catálogo de aeropuertos
-- ============================================
CREATE TABLE aeropuertos (
    id_aeropuerto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    codigo_iata CHAR(3) NOT NULL UNIQUE COMMENT 'Código IATA de 3 letras',
    codigo_icao CHAR(4) UNIQUE COMMENT 'Código ICAO de 4 letras',
    ciudad VARCHAR(100) NOT NULL,
    pais VARCHAR(100) NOT NULL,
    zona_horaria VARCHAR(50),
    latitud DECIMAL(10, 7),
    longitud DECIMAL(10, 7),
    estado ENUM('operativo', 'cerrado', 'mantenimiento') DEFAULT 'operativo',
    INDEX idx_codigo_iata (codigo_iata),
    INDEX idx_ciudad (ciudad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: categorias_asiento
-- Tipos de asientos disponibles
-- ============================================
CREATE TABLE categorias_asiento (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE COMMENT 'Ej: Económica, Ejecutiva, Primera Clase',
    descripcion TEXT,
    servicios_incluidos TEXT COMMENT 'Equipaje, comida, etc.',
    orden_visualizacion INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: vuelos
-- Información de vuelos programados
-- ============================================
CREATE TABLE vuelos (
    id_vuelo INT AUTO_INCREMENT PRIMARY KEY,
    numero_vuelo VARCHAR(20) NOT NULL COMMENT 'Ej: LA2045',
    id_aerolinea INT NOT NULL,
    id_aeropuerto_origen INT NOT NULL,
    id_aeropuerto_destino INT NOT NULL,
    fecha_salida DATETIME NOT NULL,
    fecha_llegada DATETIME NOT NULL,
    duracion_minutos INT COMMENT 'Duración estimada del vuelo',
    tipo_vuelo ENUM('directo', 'con_escalas') DEFAULT 'directo',
    estado_vuelo ENUM('programado', 'a_tiempo', 'retrasado', 'cancelado', 'completado') DEFAULT 'programado',
    minutos_retraso INT DEFAULT 0,
    puerta_embarque VARCHAR(10),
    terminal VARCHAR(10),
    capacidad_total INT NOT NULL DEFAULT 180,
    precio_base DECIMAL(10, 2) NOT NULL COMMENT 'Precio base para cálculos',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aerolinea) REFERENCES aerolineas(id_aerolinea) ON DELETE RESTRICT,
    FOREIGN KEY (id_aeropuerto_origen) REFERENCES aeropuertos(id_aeropuerto) ON DELETE RESTRICT,
    FOREIGN KEY (id_aeropuerto_destino) REFERENCES aeropuertos(id_aeropuerto) ON DELETE RESTRICT,
    INDEX idx_numero_vuelo (numero_vuelo),
    INDEX idx_fecha_salida (fecha_salida),
    INDEX idx_origen_destino (id_aeropuerto_origen, id_aeropuerto_destino),
    INDEX idx_estado (estado_vuelo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: tarifas_vuelo
-- Tarifas por categoría para cada vuelo
-- ============================================
CREATE TABLE tarifas_vuelo (
    id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
    id_vuelo INT NOT NULL,
    id_categoria INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    asientos_disponibles INT NOT NULL,
    asientos_totales INT NOT NULL,
    equipaje_bodega_kg INT DEFAULT 23,
    equipaje_mano_kg INT DEFAULT 8,
    permite_cambios BOOLEAN DEFAULT TRUE,
    permite_reembolso BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_vuelo) REFERENCES vuelos(id_vuelo) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categorias_asiento(id_categoria) ON DELETE RESTRICT,
    UNIQUE KEY unique_vuelo_categoria (id_vuelo, id_categoria),
    INDEX idx_precio (precio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: asientos
-- Asientos específicos de cada vuelo
-- ============================================
CREATE TABLE asientos (
    id_asiento INT AUTO_INCREMENT PRIMARY KEY,
    id_vuelo INT NOT NULL,
    numero_asiento VARCHAR(5) NOT NULL COMMENT 'Ej: 12A, 5F',
    id_categoria INT NOT NULL,
    fila INT NOT NULL,
    columna CHAR(1) NOT NULL COMMENT 'A, B, C, D, E, F',
    posicion ENUM('ventana', 'pasillo', 'medio') DEFAULT 'medio',
    estado ENUM('disponible', 'reservado', 'ocupado', 'bloqueado') DEFAULT 'disponible',
    FOREIGN KEY (id_vuelo) REFERENCES vuelos(id_vuelo) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categorias_asiento(id_categoria) ON DELETE RESTRICT,
    UNIQUE KEY unique_asiento_vuelo (id_vuelo, numero_asiento),
    INDEX idx_estado (estado),
    INDEX idx_vuelo_categoria (id_vuelo, id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: reservas
-- Reservas realizadas por usuarios
-- ============================================
CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    codigo_reserva VARCHAR(10) NOT NULL UNIQUE COMMENT 'Código alfanumérico único',
    id_usuario INT NOT NULL,
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_reserva ENUM('pendiente', 'confirmada', 'pagada', 'cancelada', 'expirada') DEFAULT 'pendiente',
    fecha_expiracion DATETIME COMMENT 'Reserva expira si no se paga',
    total_pasajeros INT NOT NULL DEFAULT 1,
    monto_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    observaciones TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_codigo_reserva (codigo_reserva),
    INDEX idx_usuario (id_usuario),
    INDEX idx_estado (estado_reserva),
    INDEX idx_fecha_reserva (fecha_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: detalle_reserva
-- Vuelos incluidos en cada reserva (soporte multi-vuelo)
-- ============================================
CREATE TABLE detalle_reserva (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    id_vuelo INT NOT NULL,
    orden_vuelo INT DEFAULT 1 COMMENT 'Para itinerarios con múltiples vuelos',
    id_tarifa INT NOT NULL,
    cantidad_pasajeros INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE CASCADE,
    FOREIGN KEY (id_vuelo) REFERENCES vuelos(id_vuelo) ON DELETE RESTRICT,
    FOREIGN KEY (id_tarifa) REFERENCES tarifas_vuelo(id_tarifa) ON DELETE RESTRICT,
    INDEX idx_reserva (id_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: pasajeros
-- Información de pasajeros en cada reserva
-- ============================================
CREATE TABLE pasajeros (
    id_pasajero INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    tipo_documento ENUM('DNI', 'Pasaporte', 'Carnet') DEFAULT 'DNI',
    numero_documento VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
    genero ENUM('M', 'F', 'Otro'),
    nacionalidad VARCHAR(100),
    email VARCHAR(150),
    telefono VARCHAR(20),
    tipo_pasajero ENUM('adulto', 'niño', 'infante') DEFAULT 'adulto',
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE CASCADE,
    INDEX idx_reserva (id_reserva),
    INDEX idx_documento (numero_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: asientos_reservados
-- Relación entre pasajeros y asientos asignados
-- ============================================
CREATE TABLE asientos_reservados (
    id_asiento_reservado INT AUTO_INCREMENT PRIMARY KEY,
    id_pasajero INT NOT NULL,
    id_asiento INT NOT NULL,
    id_detalle INT NOT NULL COMMENT 'Referencia al detalle de reserva',
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pasajero) REFERENCES pasajeros(id_pasajero) ON DELETE CASCADE,
    FOREIGN KEY (id_asiento) REFERENCES asientos(id_asiento) ON DELETE RESTRICT,
    FOREIGN KEY (id_detalle) REFERENCES detalle_reserva(id_detalle) ON DELETE CASCADE,
    UNIQUE KEY unique_asiento_reserva (id_asiento, id_detalle),
    INDEX idx_pasajero (id_pasajero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: tarjetas_credito
-- Información de tarjetas de crédito (simulación)
-- ============================================
CREATE TABLE tarjetas_credito (
    id_tarjeta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    numero_tarjeta VARCHAR(16) NOT NULL COMMENT 'Encriptado en producción',
    nombre_titular VARCHAR(150) NOT NULL,
    fecha_expiracion DATE NOT NULL,
    cvv VARCHAR(4) NOT NULL COMMENT 'Encriptado en producción',
    tipo_tarjeta ENUM('Visa', 'Mastercard', 'American Express', 'Diners') DEFAULT 'Visa',
    banco_emisor VARCHAR(100),
    es_principal BOOLEAN DEFAULT FALSE,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: pagos
-- Registro de pagos realizados
-- ============================================
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    id_tarjeta INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_pago ENUM('pendiente', 'aprobado', 'rechazado', 'reembolsado') DEFAULT 'pendiente',
    codigo_autorizacion VARCHAR(50),
    metodo_pago ENUM('tarjeta_credito', 'tarjeta_debito', 'transferencia') DEFAULT 'tarjeta_credito',
    observaciones TEXT,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE RESTRICT,
    FOREIGN KEY (id_tarjeta) REFERENCES tarjetas_credito(id_tarjeta) ON DELETE RESTRICT,
    INDEX idx_reserva (id_reserva),
    INDEX idx_estado (estado_pago),
    INDEX idx_fecha (fecha_pago)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: boletos
-- Boletos generados tras confirmación de pago
-- ============================================
CREATE TABLE boletos (
    id_boleto INT AUTO_INCREMENT PRIMARY KEY,
    codigo_boleto VARCHAR(15) NOT NULL UNIQUE COMMENT 'Código único del boleto',
    id_reserva INT NOT NULL,
    id_pasajero INT NOT NULL,
    id_vuelo INT NOT NULL,
    id_asiento INT NOT NULL,
    metodo_entrega ENUM('envio_domicilio', 'recogida_mostrador', 'electronico') DEFAULT 'electronico',
    direccion_envio TEXT,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_boleto ENUM('emitido', 'usado', 'cancelado') DEFAULT 'emitido',
    codigo_barras VARCHAR(100) COMMENT 'Para check-in',
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE RESTRICT,
    FOREIGN KEY (id_pasajero) REFERENCES pasajeros(id_pasajero) ON DELETE RESTRICT,
    FOREIGN KEY (id_vuelo) REFERENCES vuelos(id_vuelo) ON DELETE RESTRICT,
    FOREIGN KEY (id_asiento) REFERENCES asientos(id_asiento) ON DELETE RESTRICT,
    INDEX idx_codigo_boleto (codigo_boleto),
    INDEX idx_reserva (id_reserva),
    INDEX idx_pasajero (id_pasajero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: historial_cambios
-- Auditoría de cambios en reservas
-- ============================================
CREATE TABLE historial_cambios (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    id_usuario INT NOT NULL,
    tipo_cambio ENUM('creacion', 'modificacion', 'cancelacion', 'pago') NOT NULL,
    descripcion TEXT,
    fecha_cambio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_reserva) REFERENCES reservas(id_reserva) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_reserva (id_reserva),
    INDEX idx_fecha (fecha_cambio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS DE EJEMPLO
-- ============================================

-- Insertar categorías de asiento
INSERT INTO categorias_asiento (nombre, descripcion, servicios_incluidos, orden_visualizacion) VALUES
('Económica', 'Asiento estándar con servicios básicos', 'Equipaje de mano 8kg, 1 equipaje bodega 23kg', 1),
('Ejecutiva', 'Asiento con mayor espacio y servicios premium', 'Equipaje de mano 10kg, 2 equipajes bodega 32kg c/u, Comida premium, Acceso a sala VIP', 2),
('Primera Clase', 'Máximo confort y servicios exclusivos', 'Equipaje de mano 15kg, 3 equipajes bodega 32kg c/u, Menú gourmet, Acceso sala VIP, Asiento cama', 3);

-- Insertar aerolíneas de ejemplo
INSERT INTO aerolineas (nombre, codigo_iata, codigo_icao, pais_origen, telefono_contacto, email_contacto) VALUES
('LATAM Airlines', 'LA', 'LAN', 'Chile', '+51-1-213-8200', 'contacto@latam.com'),
('Avianca', 'AV', 'AVA', 'Colombia', '+51-1-511-8222', 'info@avianca.com'),
('Sky Airline', 'H2', 'SKU', 'Chile', '+51-1-705-1111', 'contacto@skyairline.com'),
('Viva Air', 'VH', 'VVC', 'Perú', '+51-1-700-5050', 'soporte@vivaair.com');

-- Insertar aeropuertos de ejemplo (Perú y principales de Sudamérica)
INSERT INTO aeropuertos (nombre, codigo_iata, codigo_icao, ciudad, pais, zona_horaria) VALUES
('Aeropuerto Internacional Jorge Chávez', 'LIM', 'SPJC', 'Lima', 'Perú', 'America/Lima'),
('Aeropuerto Internacional Alejandro Velasco Astete', 'CUZ', 'SPZO', 'Cusco', 'Perú', 'America/Lima'),
('Aeropuerto Internacional Rodríguez Ballón', 'AQP', 'SPQU', 'Arequipa', 'Perú', 'America/Lima'),
('Aeropuerto Internacional Capitán FAP Carlos Martínez de Pinillos', 'TRU', 'SPRU', 'Trujillo', 'Perú', 'America/Lima'),
('Aeropuerto Internacional El Dorado', 'BOG', 'SKBO', 'Bogotá', 'Colombia', 'America/Bogota'),
('Aeropuerto Internacional Arturo Merino Benítez', 'SCL', 'SCEL', 'Santiago', 'Chile', 'America/Santiago'),
('Aeropuerto Internacional Jorge Wilstermann', 'CBB', 'SLCB', 'Cochabamba', 'Bolivia', 'America/La_Paz');

-- ============================================
-- TRIGGERS Y PROCEDIMIENTOS ALMACENADOS
-- ============================================

-- Trigger: Generar código de reserva automáticamente
DELIMITER //
CREATE TRIGGER before_insert_reserva
BEFORE INSERT ON reservas
FOR EACH ROW
BEGIN
    IF NEW.codigo_reserva IS NULL OR NEW.codigo_reserva = '' THEN
        SET NEW.codigo_reserva = CONCAT('RV', LPAD(FLOOR(RAND() * 999999), 6, '0'));
    END IF;
    -- Establecer fecha de expiración (24 horas desde la reserva)
    IF NEW.fecha_expiracion IS NULL THEN
        SET NEW.fecha_expiracion = DATE_ADD(NOW(), INTERVAL 24 HOUR);
    END IF;
END//
DELIMITER ;

-- Trigger: Generar código de boleto automáticamente
DELIMITER //
CREATE TRIGGER before_insert_boleto
BEFORE INSERT ON boletos
FOR EACH ROW
BEGIN
    IF NEW.codigo_boleto IS NULL OR NEW.codigo_boleto = '' THEN
        SET NEW.codigo_boleto = CONCAT('BT', LPAD(FLOOR(RAND() * 9999999999), 10, '0'));
    END IF;
    IF NEW.codigo_barras IS NULL OR NEW.codigo_barras = '' THEN
        SET NEW.codigo_barras = CONCAT('BC', LPAD(FLOOR(RAND() * 99999999999999), 14, '0'));
    END IF;
END//
DELIMITER ;

-- Trigger: Actualizar estado de asiento al reservar
DELIMITER //
CREATE TRIGGER after_insert_asiento_reservado
AFTER INSERT ON asientos_reservados
FOR EACH ROW
BEGIN
    UPDATE asientos SET estado = 'reservado' WHERE id_asiento = NEW.id_asiento;
END//
DELIMITER ;

-- Trigger: Actualizar disponibilidad de asientos en tarifas
DELIMITER //
CREATE TRIGGER after_update_asiento_estado
AFTER UPDATE ON asientos
FOR EACH ROW
BEGIN
    IF NEW.estado != OLD.estado THEN
        UPDATE tarifas_vuelo 
        SET asientos_disponibles = (
            SELECT COUNT(*) 
            FROM asientos 
            WHERE id_vuelo = NEW.id_vuelo 
            AND id_categoria = NEW.id_categoria 
            AND estado = 'disponible'
        )
        WHERE id_vuelo = NEW.id_vuelo AND id_categoria = NEW.id_categoria;
    END IF;
END//
DELIMITER ;

-- Procedimiento: Actualizar estado de reserva a pagada
DELIMITER //
CREATE PROCEDURE actualizar_reserva_pagada(IN p_id_reserva INT)
BEGIN
    UPDATE reservas 
    SET estado_reserva = 'pagada' 
    WHERE id_reserva = p_id_reserva;
    
    -- Actualizar asientos a ocupado
    UPDATE asientos a
    INNER JOIN asientos_reservados ar ON a.id_asiento = ar.id_asiento
    INNER JOIN pasajeros p ON ar.id_pasajero = p.id_pasajero
    SET a.estado = 'ocupado'
    WHERE p.id_reserva = p_id_reserva;
END//
DELIMITER ;

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista: Vuelos disponibles con información completa
CREATE OR REPLACE VIEW vista_vuelos_disponibles AS
SELECT 
    v.id_vuelo,
    v.numero_vuelo,
    a.nombre AS aerolinea,
    a.codigo_iata AS codigo_aerolinea,
    ao.nombre AS aeropuerto_origen,
    ao.ciudad AS ciudad_origen,
    ao.codigo_iata AS codigo_origen,
    ad.nombre AS aeropuerto_destino,
    ad.ciudad AS ciudad_destino,
    ad.codigo_iata AS codigo_destino,
    v.fecha_salida,
    v.fecha_llegada,
    v.duracion_minutos,
    v.tipo_vuelo,
    v.estado_vuelo,
    v.puerta_embarque,
    v.terminal,
    MIN(tf.precio) AS precio_minimo,
    MAX(tf.precio) AS precio_maximo,
    SUM(tf.asientos_disponibles) AS total_asientos_disponibles
FROM vuelos v
INNER JOIN aerolineas a ON v.id_aerolinea = a.id_aerolinea
INNER JOIN aeropuertos ao ON v.id_aeropuerto_origen = ao.id_aeropuerto
INNER JOIN aeropuertos ad ON v.id_aeropuerto_destino = ad.id_aeropuerto
LEFT JOIN tarifas_vuelo tf ON v.id_vuelo = tf.id_vuelo
WHERE v.estado_vuelo IN ('programado', 'a_tiempo', 'retrasado')
AND v.fecha_salida > NOW()
GROUP BY v.id_vuelo;

-- Vista: Reservas con información de usuario
CREATE OR REPLACE VIEW vista_reservas_usuario AS
SELECT 
    r.id_reserva,
    r.codigo_reserva,
    r.fecha_reserva,
    r.estado_reserva,
    r.monto_total,
    r.total_pasajeros,
    u.nombre,
    u.apellido,
    u.email,
    COUNT(DISTINCT dr.id_vuelo) AS cantidad_vuelos
FROM reservas r
INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
LEFT JOIN detalle_reserva dr ON r.id_reserva = dr.id_reserva
GROUP BY r.id_reserva;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
