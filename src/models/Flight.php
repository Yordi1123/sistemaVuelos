<?php
/**
 * Modelo de Vuelo
 * Sistema de Reserva de Vuelos
 */

class Flight {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Búsqueda de vuelos por ruta y fecha
     * @param int $origin_id
     * @param int $destination_id
     * @param string $date
     * @param string $order_by ('schedule'|'price')
     * @return array
     */
    public function search($origin_id, $destination_id, $date, $order_by = 'schedule') {
        try {
            $sql = "SELECT 
                        v.id_vuelo,
                        v.numero_vuelo,
                        a.nombre AS aerolinea,
                        a.codigo_iata AS codigo_aerolinea,
                        a.logo_url,
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
                        v.minutos_retraso,
                        MIN(tf.precio) AS precio_minimo,
                        MAX(tf.precio) AS precio_maximo,
                        SUM(tf.asientos_disponibles) AS total_asientos_disponibles
                    FROM vuelos v
                    INNER JOIN aerolineas a ON v.id_aerolinea = a.id_aerolinea
                    INNER JOIN aeropuertos ao ON v.id_aeropuerto_origen = ao.id_aeropuerto
                    INNER JOIN aeropuertos ad ON v.id_aeropuerto_destino = ad.id_aeropuerto
                    LEFT JOIN tarifas_vuelo tf ON v.id_vuelo = tf.id_vuelo
                    WHERE v.id_aeropuerto_origen = :origin
                    AND v.id_aeropuerto_destino = :destination
                    AND DATE(v.fecha_salida) = :date
                    AND v.estado_vuelo IN ('programado', 'a_tiempo', 'retrasado')
                    GROUP BY v.id_vuelo";
            
            // Ordenamiento
            if ($order_by === 'price') {
                $sql .= " ORDER BY precio_minimo ASC, v.fecha_salida ASC";
            } else {
                $sql .= " ORDER BY v.fecha_salida ASC";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':origin' => $origin_id,
                ':destination' => $destination_id,
                ':date' => $date
            ]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error en búsqueda de vuelos: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Búsqueda de vuelos por estado (del día)
     * @param string $date
     * @return array
     */
    public function searchByStatus($date = null) {
        try {
            if ($date === null) {
                $date = date('Y-m-d');
            }
            
            $sql = "SELECT 
                        v.id_vuelo,
                        v.numero_vuelo,
                        a.nombre AS aerolinea,
                        a.codigo_iata AS codigo_aerolinea,
                        ao.ciudad AS ciudad_origen,
                        ao.codigo_iata AS codigo_origen,
                        ad.ciudad AS ciudad_destino,
                        ad.codigo_iata AS codigo_destino,
                        v.fecha_salida,
                        v.fecha_llegada,
                        v.estado_vuelo,
                        v.minutos_retraso,
                        v.puerta_embarque,
                        v.terminal,
                        SUM(tf.asientos_disponibles) AS total_asientos_disponibles
                    FROM vuelos v
                    INNER JOIN aerolineas a ON v.id_aerolinea = a.id_aerolinea
                    INNER JOIN aeropuertos ao ON v.id_aeropuerto_origen = ao.id_aeropuerto
                    INNER JOIN aeropuertos ad ON v.id_aeropuerto_destino = ad.id_aeropuerto
                    LEFT JOIN tarifas_vuelo tf ON v.id_vuelo = tf.id_vuelo
                    WHERE DATE(v.fecha_salida) = :date
                    GROUP BY v.id_vuelo
                    ORDER BY v.fecha_salida ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':date' => $date]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error en búsqueda por estado: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Obtener detalles completos de un vuelo
     * @param int $flight_id
     * @return array|false
     */
    public function getDetails($flight_id) {
        try {
            $sql = "SELECT 
                        v.*,
                        a.nombre AS aerolinea,
                        a.codigo_iata AS codigo_aerolinea,
                        a.codigo_icao,
                        a.logo_url,
                        ao.nombre AS aeropuerto_origen,
                        ao.ciudad AS ciudad_origen,
                        ao.codigo_iata AS codigo_origen,
                        ao.pais AS pais_origen,
                        ad.nombre AS aeropuerto_destino,
                        ad.ciudad AS ciudad_destino,
                        ad.codigo_iata AS codigo_destino,
                        ad.pais AS pais_destino
                    FROM vuelos v
                    INNER JOIN aerolineas a ON v.id_aerolinea = a.id_aerolinea
                    INNER JOIN aeropuertos ao ON v.id_aeropuerto_origen = ao.id_aeropuerto
                    INNER JOIN aeropuertos ad ON v.id_aeropuerto_destino = ad.id_aeropuerto
                    WHERE v.id_vuelo = :id
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $flight_id]);
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener detalles de vuelo: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Obtener tarifas de un vuelo por categoría
     * @param int $flight_id
     * @return array
     */
    public function getFares($flight_id) {
        try {
            $sql = "SELECT 
                        tf.*,
                        c.nombre AS categoria,
                        c.descripcion,
                        c.servicios_incluidos
                    FROM tarifas_vuelo tf
                    INNER JOIN categorias_asiento c ON tf.id_categoria = c.id_categoria
                    WHERE tf.id_vuelo = :id
                    ORDER BY c.orden_visualizacion ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $flight_id]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener tarifas: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Aplicar filtros a resultados de búsqueda
     * @param array $flights
     * @param array $filters
     * @return array
     */
    public function applyFilters($flights, $filters) {
        if (empty($flights)) {
            return [];
        }
        
        $filtered = $flights;
        
        // Filtro por aerolínea
        if (!empty($filters['airline'])) {
            $filtered = array_filter($filtered, function($flight) use ($filters) {
                return $flight['codigo_aerolinea'] === $filters['airline'];
            });
        }
        
        // Filtro solo vuelos directos
        if (!empty($filters['direct_only'])) {
            $filtered = array_filter($filtered, function($flight) {
                return $flight['tipo_vuelo'] === 'directo';
            });
        }
        
        // Filtro por rango de precio
        if (!empty($filters['min_price'])) {
            $filtered = array_filter($filtered, function($flight) use ($filters) {
                return $flight['precio_minimo'] >= $filters['min_price'];
            });
        }
        
        if (!empty($filters['max_price'])) {
            $filtered = array_filter($filtered, function($flight) use ($filters) {
                return $flight['precio_minimo'] <= $filters['max_price'];
            });
        }
        
        return array_values($filtered); // Reindexar array
    }
    
    /**
     * Verificar disponibilidad de asientos
     * @param int $flight_id
     * @param int $category_id
     * @return int
     */
    public function getAvailableSeats($flight_id, $category_id = null) {
        try {
            if ($category_id) {
                $sql = "SELECT asientos_disponibles 
                        FROM tarifas_vuelo 
                        WHERE id_vuelo = :flight_id AND id_categoria = :category_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':flight_id' => $flight_id,
                    ':category_id' => $category_id
                ]);
                $result = $stmt->fetch();
                return $result ? $result['asientos_disponibles'] : 0;
            } else {
                $sql = "SELECT SUM(asientos_disponibles) AS total
                        FROM tarifas_vuelo 
                        WHERE id_vuelo = :flight_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':flight_id' => $flight_id]);
                $result = $stmt->fetch();
                return $result ? $result['total'] : 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }
}
