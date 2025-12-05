-- ============================================
-- DATOS DE PRUEBA - SISTEMA DE RESERVA DE VUELOS
-- Vuelos de ejemplo para testing
-- ============================================

USE sistema_vuelos;

-- ============================================
-- INSERTAR VUELOS DE PRUEBA
-- ============================================

-- Vuelos Lima - Cusco (Ruta popular turística)
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2045', 1, 1, 2, '2025-12-10 06:00:00', '2025-12-10 07:20:00', 80, 'directo', 'programado', 'A12', '1', 180, 250.00),
('LA2047', 1, 1, 2, '2025-12-10 10:30:00', '2025-12-10 11:50:00', 80, 'directo', 'a_tiempo', 'A14', '1', 180, 280.00),
('LA2049', 1, 1, 2, '2025-12-10 15:00:00', '2025-12-10 16:20:00', 80, 'directo', 'programado', 'A16', '1', 180, 260.00),
('AV8520', 2, 1, 2, '2025-12-10 08:15:00', '2025-12-10 09:35:00', 80, 'directo', 'a_tiempo', 'B05', '2', 150, 240.00),
('H2301', 3, 1, 2, '2025-12-10 12:00:00', '2025-12-10 13:20:00', 80, 'directo', 'programado', 'C10', '1', 160, 220.00),
('VH105', 4, 1, 2, '2025-12-10 18:30:00', '2025-12-10 19:50:00', 80, 'directo', 'retrasado', 'A08', '1', 140, 200.00);

-- Vuelos Cusco - Lima (Retorno)
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2046', 1, 2, 1, '2025-12-10 08:00:00', '2025-12-10 09:20:00', 80, 'directo', 'a_tiempo', 'G02', '1', 180, 250.00),
('LA2048', 1, 2, 1, '2025-12-10 12:30:00', '2025-12-10 13:50:00', 80, 'directo', 'programado', 'G04', '1', 180, 280.00),
('AV8521', 2, 2, 1, '2025-12-10 10:15:00', '2025-12-10 11:35:00', 80, 'directo', 'a_tiempo', 'G06', '1', 150, 240.00);

-- Vuelos Lima - Arequipa
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2100', 1, 1, 3, '2025-12-10 07:00:00', '2025-12-10 08:30:00', 90, 'directo', 'a_tiempo', 'A20', '1', 180, 200.00),
('LA2102', 1, 1, 3, '2025-12-10 14:00:00', '2025-12-10 15:30:00', 90, 'directo', 'programado', 'A22', '1', 180, 210.00),
('H2401', 3, 1, 3, '2025-12-10 11:00:00', '2025-12-10 12:30:00', 90, 'directo', 'a_tiempo', 'C12', '1', 160, 180.00);

-- Vuelos Lima - Trujillo
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2200', 1, 1, 4, '2025-12-10 09:00:00', '2025-12-10 10:10:00', 70, 'directo', 'a_tiempo', 'A30', '1', 150, 180.00),
('VH201', 4, 1, 4, '2025-12-10 16:00:00', '2025-12-10 17:10:00', 70, 'directo', 'programado', 'A32', '1', 140, 160.00);

-- Vuelos Internacionales - Lima - Bogotá
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2500', 1, 1, 5, '2025-12-10 22:00:00', '2025-12-11 02:30:00', 270, 'directo', 'programado', 'D01', '2', 220, 450.00),
('AV9100', 2, 1, 5, '2025-12-10 23:30:00', '2025-12-11 04:00:00', 270, 'directo', 'programado', 'D03', '2', 200, 420.00);

-- Vuelos Lima - Santiago
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2600', 1, 1, 6, '2025-12-10 08:00:00', '2025-12-10 12:30:00', 270, 'directo', 'a_tiempo', 'D10', '2', 220, 380.00),
('H2501', 3, 1, 6, '2025-12-10 13:00:00', '2025-12-10 17:30:00', 270, 'directo', 'programado', 'D12', '2', 180, 350.00);

-- Vuelos para el día siguiente (2025-12-11)
INSERT INTO vuelos (numero_vuelo, id_aerolinea, id_aeropuerto_origen, id_aeropuerto_destino, 
                    fecha_salida, fecha_llegada, duracion_minutos, tipo_vuelo, estado_vuelo, 
                    puerta_embarque, terminal, capacidad_total, precio_base) VALUES
('LA2051', 1, 1, 2, '2025-12-11 06:00:00', '2025-12-11 07:20:00', 80, 'directo', 'programado', 'A12', '1', 180, 250.00),
('LA2053', 1, 1, 2, '2025-12-11 10:30:00', '2025-12-11 11:50:00', 80, 'directo', 'programado', 'A14', '1', 180, 280.00),
('AV8522', 2, 1, 2, '2025-12-11 08:15:00', '2025-12-11 09:35:00', 80, 'directo', 'programado', 'B05', '2', 150, 240.00),
('LA2104', 1, 1, 3, '2025-12-11 07:00:00', '2025-12-11 08:30:00', 90, 'directo', 'programado', 'A20', '1', 180, 200.00),
('LA2602', 1, 1, 6, '2025-12-11 08:00:00', '2025-12-11 12:30:00', 270, 'directo', 'programado', 'D10', '2', 220, 380.00);

-- ============================================
-- INSERTAR TARIFAS POR VUELO
-- ============================================

-- Función para insertar tarifas de un vuelo
DELIMITER //
CREATE PROCEDURE insertar_tarifas_vuelo(IN p_id_vuelo INT, IN p_precio_base DECIMAL(10,2))
BEGIN
    DECLARE v_asientos_economica INT DEFAULT 120;
    DECLARE v_asientos_ejecutiva INT DEFAULT 40;
    DECLARE v_asientos_primera INT DEFAULT 20;
    
    -- Económica (precio base)
    INSERT INTO tarifas_vuelo (id_vuelo, id_categoria, precio, asientos_disponibles, asientos_totales)
    VALUES (p_id_vuelo, 1, p_precio_base, v_asientos_economica, v_asientos_economica);
    
    -- Ejecutiva (precio base * 2.5)
    INSERT INTO tarifas_vuelo (id_vuelo, id_categoria, precio, asientos_disponibles, asientos_totales)
    VALUES (p_id_vuelo, 2, p_precio_base * 2.5, v_asientos_ejecutiva, v_asientos_ejecutiva);
    
    -- Primera Clase (precio base * 4)
    INSERT INTO tarifas_vuelo (id_vuelo, id_categoria, precio, asientos_disponibles, asientos_totales)
    VALUES (p_id_vuelo, 3, p_precio_base * 4, v_asientos_primera, v_asientos_primera);
END//
DELIMITER ;

-- Insertar tarifas para todos los vuelos
CALL insertar_tarifas_vuelo(1, 250.00);
CALL insertar_tarifas_vuelo(2, 280.00);
CALL insertar_tarifas_vuelo(3, 260.00);
CALL insertar_tarifas_vuelo(4, 240.00);
CALL insertar_tarifas_vuelo(5, 220.00);
CALL insertar_tarifas_vuelo(6, 200.00);
CALL insertar_tarifas_vuelo(7, 250.00);
CALL insertar_tarifas_vuelo(8, 280.00);
CALL insertar_tarifas_vuelo(9, 240.00);
CALL insertar_tarifas_vuelo(10, 200.00);
CALL insertar_tarifas_vuelo(11, 210.00);
CALL insertar_tarifas_vuelo(12, 180.00);
CALL insertar_tarifas_vuelo(13, 180.00);
CALL insertar_tarifas_vuelo(14, 160.00);
CALL insertar_tarifas_vuelo(15, 450.00);
CALL insertar_tarifas_vuelo(16, 420.00);
CALL insertar_tarifas_vuelo(17, 380.00);
CALL insertar_tarifas_vuelo(18, 350.00);
CALL insertar_tarifas_vuelo(19, 250.00);
CALL insertar_tarifas_vuelo(20, 280.00);
CALL insertar_tarifas_vuelo(21, 240.00);
CALL insertar_tarifas_vuelo(22, 200.00);
CALL insertar_tarifas_vuelo(23, 380.00);

-- ============================================
-- GENERAR ASIENTOS PARA VUELOS
-- ============================================

DELIMITER //
CREATE PROCEDURE generar_asientos_vuelo(IN p_id_vuelo INT)
BEGIN
    DECLARE v_fila INT DEFAULT 1;
    DECLARE v_columna CHAR(1);
    DECLARE v_categoria INT;
    DECLARE v_posicion VARCHAR(10);
    
    -- Generar asientos
    WHILE v_fila <= 30 DO
        -- Determinar categoría según fila
        IF v_fila <= 5 THEN
            SET v_categoria = 3; -- Primera Clase (filas 1-5)
        ELSEIF v_fila <= 15 THEN
            SET v_categoria = 2; -- Ejecutiva (filas 6-15)
        ELSE
            SET v_categoria = 1; -- Económica (filas 16-30)
        END IF;
        
        -- Generar asientos A-F para cada fila
        SET v_columna = 'A';
        WHILE v_columna <= 'F' DO
            -- Determinar posición
            IF v_columna IN ('A', 'F') THEN
                SET v_posicion = 'ventana';
            ELSEIF v_columna IN ('C', 'D') THEN
                SET v_posicion = 'pasillo';
            ELSE
                SET v_posicion = 'medio';
            END IF;
            
            -- Insertar asiento
            INSERT INTO asientos (id_vuelo, numero_asiento, id_categoria, fila, columna, posicion, estado)
            VALUES (p_id_vuelo, CONCAT(v_fila, v_columna), v_categoria, v_fila, v_columna, v_posicion, 'disponible');
            
            -- Siguiente columna
            SET v_columna = CHAR(ASCII(v_columna) + 1);
        END WHILE;
        
        SET v_fila = v_fila + 1;
    END WHILE;
END//
DELIMITER ;

-- Generar asientos para todos los vuelos
CALL generar_asientos_vuelo(1);
CALL generar_asientos_vuelo(2);
CALL generar_asientos_vuelo(3);
CALL generar_asientos_vuelo(4);
CALL generar_asientos_vuelo(5);
CALL generar_asientos_vuelo(6);
CALL generar_asientos_vuelo(7);
CALL generar_asientos_vuelo(8);
CALL generar_asientos_vuelo(9);
CALL generar_asientos_vuelo(10);
CALL generar_asientos_vuelo(11);
CALL generar_asientos_vuelo(12);
CALL generar_asientos_vuelo(13);
CALL generar_asientos_vuelo(14);
CALL generar_asientos_vuelo(15);
CALL generar_asientos_vuelo(16);
CALL generar_asientos_vuelo(17);
CALL generar_asientos_vuelo(18);
CALL generar_asientos_vuelo(19);
CALL generar_asientos_vuelo(20);
CALL generar_asientos_vuelo(21);
CALL generar_asientos_vuelo(22);
CALL generar_asientos_vuelo(23);

-- Limpiar procedimientos temporales
DROP PROCEDURE IF EXISTS insertar_tarifas_vuelo;
DROP PROCEDURE IF EXISTS generar_asientos_vuelo;

-- ============================================
-- VERIFICACIÓN
-- ============================================

SELECT 'Vuelos insertados:' AS Info, COUNT(*) AS Total FROM vuelos;
SELECT 'Tarifas insertadas:' AS Info, COUNT(*) AS Total FROM tarifas_vuelo;
SELECT 'Asientos generados:' AS Info, COUNT(*) AS Total FROM asientos;

-- Ver resumen de vuelos
SELECT 
    v.numero_vuelo,
    a.codigo_iata AS aerolinea,
    ao.codigo_iata AS origen,
    ad.codigo_iata AS destino,
    DATE_FORMAT(v.fecha_salida, '%Y-%m-%d %H:%i') AS salida,
    v.estado_vuelo,
    MIN(tf.precio) AS precio_min,
    MAX(tf.precio) AS precio_max
FROM vuelos v
INNER JOIN aerolineas a ON v.id_aerolinea = a.id_aerolinea
INNER JOIN aeropuertos ao ON v.id_aeropuerto_origen = ao.id_aeropuerto
INNER JOIN aeropuertos ad ON v.id_aeropuerto_destino = ad.id_aeropuerto
LEFT JOIN tarifas_vuelo tf ON v.id_vuelo = tf.id_vuelo
GROUP BY v.id_vuelo
ORDER BY v.fecha_salida;
